<?php

namespace App\Http\Controllers;

use App\Models\Law;
use Illuminate\Http\Request;

class LawController extends Controller
{
    public function index(Request $request)
    {
        $query = Law::query()->withCount('templates', 'projects');

        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where(function ($sub) use ($q) {
                $sub->where('code', 'like', "%{$q}%")
                    ->orWhere('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%")
                    ->orWhere('comment', 'like', "%{$q}%");
            });
        }

        foreach (['country', 'type', 'status'] as $field) {
            if ($request->filled($field)) {
                $query->where($field, $request->input($field));
            }
        }

        $allowedSort = ['code', 'name', 'published_at', 'templates_count', 'projects_count'];
        $sort = $request->input('sort', 'code');
        $dir = $request->input('dir', 'asc');

        if (!in_array($sort, $allowedSort, true)) $sort = 'code';
        if (!in_array($dir, ['asc', 'desc'], true)) $dir = 'asc';

        $laws = $query
            ->orderBy($sort, $dir)
            ->paginate(30)
            ->withQueryString();

        // Для селектов фильтра — простые списки значений из БД
        $countries = Law::query()->select('country')->whereNotNull('country')->distinct()->orderBy('country')->pluck('country');
        $types     = Law::query()->select('type')->whereNotNull('type')->distinct()->orderBy('type')->pluck('type');
        $statuses  = collect(['draft', 'active', 'archived']);

        return view('laws.index', compact('laws', 'countries', 'types', 'statuses', 'sort', 'dir'));
    }

    public function create()
    {
        return view('laws.create');
    }

    public function store(Request $request)
    {
        $data = $this->validatedLaw($request);

        // tags из строки "a,b,c" -> ["a","b","c"]
        $data['tags'] = $this->normalizeTags($request->input('tags'));

        $law = Law::create($data);

        // экспертизу на create пока не добавляем (будет в edit)
        return redirect()->route('laws.show', $law)->with('success', 'Закон добавлен.');
    }

    public function show(Law $law)
    {
        $law->load([
            'templates' => fn($q) => $q->orderBy('category')->orderBy('title'),
            'projects.company',
            'expertOpinions',
        ]);

        return view('laws.show', compact('law'));
    }

    public function edit(Law $law)
    {
        $law->load('expertOpinions');
        return view('laws.edit', compact('law'));
    }

    public function update(Request $request, Law $law)
    {
        $data = $this->validatedLaw($request, $law->id);
        $data['tags'] = $this->normalizeTags($request->input('tags'));

        $law->update($data);

        // Сохранение экспертизы (массив групп)
        $expertise = $request->input('expertise', []);
        $this->syncExpertise($law, is_array($expertise) ? $expertise : []);

        return redirect()->route('laws.show', $law)->with('success', 'Обновлено.');
    }

    public function destroy(Law $law)
    {
        $law->delete();
        return redirect()->route('laws.index')->with('success', 'Удалено.');
    }

    private function validatedLaw(Request $request, ?int $lawId = null): array
    {
        $uniqueRule = 'unique:laws,code' . ($lawId ? ',' . $lawId : '');

        return $request->validate([
            'code' => ['required', $uniqueRule],
            'name' => ['required'],

            'country' => ['nullable', 'string', 'max:8'],
            'type' => ['nullable', 'string', 'max:32'],
            'status' => ['required', 'in:draft,active,archived'],
            'published_at' => ['nullable', 'date'],

            'official_url' => ['nullable', 'url', 'max:2048'],
            'word_url' => ['nullable', 'url', 'max:2048'],
            'consultant_url' => ['nullable', 'url', 'max:2048'],

            'description' => ['nullable', 'string'],
            'comment' => ['nullable', 'string'],

            // tags приходят строкой, нормализуем отдельно
            'tags' => ['nullable', 'string'],
        ]);
    }

    private function normalizeTags(?string $tags): array
    {
        if (!$tags) return [];
        $parts = array_map('trim', preg_split('/[,\n;]/', $tags));
        $parts = array_values(array_filter($parts, fn($t) => $t !== ''));
        // unique
        return array_values(array_unique($parts));
    }

    private function syncExpertise(Law $law, array $expertise): void
    {
        $idsToKeep = [];

        foreach (array_values($expertise) as $i => $row) {
            if (!is_array($row)) continue;

            $payload = [
                'expert_name' => $row['expert_name'] ?? null,
                'opinion' => $row['opinion'] ?? null,
                'video_url' => $row['video_url'] ?? null,
                'video_transcript' => $row['video_transcript'] ?? null,
                'file_path' => $row['file_path'] ?? null, // пока строкой, загрузку сделаем позже
                'resource_url' => $row['resource_url'] ?? null,
                'sort_order' => $i,
            ];

            $id = $row['id'] ?? null;

            if ($id) {
                $model = $law->expertOpinions()->whereKey($id)->first();
                if ($model) {
                    $model->update($payload);
                    $idsToKeep[] = $model->id;
                    continue;
                }
            }

            // создать новый, если есть хоть что-то заполненное
            $hasAny = collect($payload)->filter(fn($v) => $v !== null && $v !== '')->isNotEmpty();
            if ($hasAny) {
                $model = $law->expertOpinions()->create($payload);
                $idsToKeep[] = $model->id;
            }
        }

        // удалить то, что убрали из формы
        $law->expertOpinions()->whereNotIn('id', $idsToKeep)->delete();
    }
}