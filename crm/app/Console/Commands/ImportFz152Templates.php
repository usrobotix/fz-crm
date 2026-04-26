<?php

namespace App\Console\Commands;

use App\Models\Law;
use App\Models\Template;
use App\Models\TemplateVersion;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ImportFz152Templates extends Command
{
    protected $signature = 'fz152:import {--dry-run : Preview without saving} {--token= : GitHub personal access token}';
    protected $description = 'Import template library from usrobotix/fz152 repository STATUS.md';

    private string $repo = 'usrobotix/fz152';
    private string $branch = 'main';

    public function handle(): int
    {
        $token = $this->option('token') ?: env('GITHUB_TOKEN');
        $dryRun = $this->option('dry-run');

        $this->info("Fetching STATUS.md from {$this->repo}...");
        $statusContent = $this->fetchFile('STATUS.md', $token);
        if (!$statusContent) {
            $this->error('Could not fetch STATUS.md from repository.');
            return 1;
        }

        $rows = $this->parseStatusTable($statusContent);
        $libraryRows = array_filter($rows, fn($r) => strtolower($r['category'] ?? '') === 'library');

        $this->info("Found " . count($libraryRows) . " Library rows.");

        if (!$dryRun) {
            $law = Law::firstOrCreate(['code' => 'fz152'], ['name' => 'Федеральный закон №152-ФЗ «О персональных данных»', 'description' => 'ФЗ-152 о защите персональных данных']);
        }

        $imported = 0;
        $skipped = 0;

        foreach ($libraryRows as $row) {
            $repoPath = $row['file_path'] ?? null;
            if (!$repoPath) { $skipped++; continue; }

            if ($dryRun) {
                $this->line("  [DRY] Would import: {$row['title']} (path: {$repoPath})");
                $imported++;
                continue;
            }

            if (Template::where('repo_path', $repoPath)->exists()) {
                $this->line("  [SKIP] Already imported: {$repoPath}");
                $skipped++;
                continue;
            }

            $body = $this->fetchFile($repoPath, $token) ?? '';

            $template = Template::create([
                'law_id'    => $law->id,
                'category'  => $row['category'] ?? 'Library',
                'title'     => $row['title'] ?? basename($repoPath, '.md'),
                'doc_type'  => $row['doc_type'] ?? null,
                'source'    => $row['source'] ?? null,
                'status'    => 'active',
                'repo_path' => $repoPath,
                'comment'   => $row['comment'] ?? null,
            ]);

            TemplateVersion::create([
                'template_id'    => $template->id,
                'user_id'        => null,
                'version_number' => 1,
                'body'           => $body,
                'change_note'    => 'Imported from fz152 repository',
            ]);

            $this->line("  [OK] Imported: {$row['title']}");
            $imported++;
        }

        $this->info("Done. Imported: {$imported}, Skipped: {$skipped}");
        return 0;
    }

    private function fetchFile(string $path, ?string $token): ?string
    {
        $url = "https://api.github.com/repos/{$this->repo}/contents/{$path}?ref={$this->branch}";
        $headers = ['Accept' => 'application/vnd.github.v3+json', 'User-Agent' => 'fz-crm-importer'];
        if ($token) $headers['Authorization'] = "token {$token}";

        try {
            $response = Http::withHeaders($headers)->get($url);
            if (!$response->successful()) return null;
            $data = $response->json();
            if (isset($data['content'])) {
                return base64_decode(str_replace("\n", '', $data['content']));
            }
        } catch (\Throwable $e) {
            $this->warn("Failed to fetch {$path}: " . $e->getMessage());
        }
        return null;
    }

    private function parseStatusTable(string $content): array
    {
        $rows = [];
        $lines = explode("\n", $content);
        $inTable = false;
        $headers = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if (!str_starts_with($line, '|')) { $inTable = false; continue; }

            $cells = array_map('trim', explode('|', trim($line, '|')));

            if (!$inTable) {
                $headers = array_map('strtolower', $cells);
                $inTable = true;
                continue;
            }

            if (preg_match('/^[\|\-\s:]+$/', $line)) continue;

            if (count($cells) < count($headers)) continue;

            $row = [];
            foreach ($headers as $i => $h) {
                $row[$h] = $cells[$i] ?? '';
            }

            $titleCell = $row['title'] ?? $row['name'] ?? $row['документ'] ?? '';
            if (preg_match('/\[([^\]]+)\]\(([^\)]+)\)/', $titleCell, $m)) {
                $row['title'] = $m[1];
                $row['file_path'] = ltrim($m[2], '/');
            } else {
                $row['title'] = $titleCell;
            }

            $rows[] = $row;
        }
        return $rows;
    }
}
