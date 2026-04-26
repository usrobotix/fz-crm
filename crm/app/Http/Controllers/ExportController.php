<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Support\Str;
use ZipArchive;

class ExportController extends Controller
{
    public function projectZip(Project $project)
    {
        $project->load(['documents.latestVersion', 'company', 'law']);
        $zip = new ZipArchive();
        $tmpFile = tempnam(sys_get_temp_dir(), 'fzcrm_export_') . '.zip';
        $zip->open($tmpFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $indexLines = [
            "# Пакет документов: {$project->name}",
            "",
            "**Компания:** {$project->company->name}",
            "**Закон:** {$project->law->code} — {$project->law->name}",
            "**Статус проекта:** {$project->status}",
            "**Срок:** " . ($project->due_at ? $project->due_at->format('Y-m-d') : 'не указан'),
            "",
            "## Документы",
            "",
            "| Заголовок | Тип | Статус |",
            "|-----------|-----|--------|",
        ];

        foreach ($project->documents as $doc) {
            $body = $doc->latestVersion?->body ?? '';
            $filename = Str::slug($doc->title) . '.md';
            $zip->addFromString($filename, $body);
            $indexLines[] = "| [{$doc->title}]({$filename}) | {$doc->doc_type} | {$doc->status} |";
        }

        $zip->addFromString('INDEX.md', implode("\n", $indexLines));
        $zip->close();

        return response()->download($tmpFile, Str::slug($project->name) . '_export.zip')->deleteFileAfterSend(true);
    }
}
