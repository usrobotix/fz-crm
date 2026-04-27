<?php

namespace App\Http\Controllers;

use App\Models\Law;
use App\Models\Template;
use App\Models\TemplateVersion;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function index(Request $request)
    {
        $query = Template::with('law', 'latestVersion');

        if ($request->filled('law_id')) {
            $query->where('law_id', $request->law_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }

        $templates = $query
            ->orderBy('category')
            ->orderBy('title')
            ->paginate(30)
            ->withQueryString();

        $laws = Law::orderBy('code')->get();

        return view('templates.index', compact('templates', 'laws'));
    }

    public function create()
    {
        $laws = Law::orderBy('code')->get();

        return view('templates.create', compact('laws'));
    }

    public function store(Request $request)
    {
        
        $bodyRaw = $request->input('body');

        \Log::info('TEMPLATE_BODY_DEBUG', [
            'has_body_key' => array_key_exists('body', $request->all()),
            'body_is_null' => $bodyRaw === null,
            'body_type' => gettype($bodyRaw),
            'body_len' => is_string($bodyRaw) ? mb_strlen($bodyRaw) : null,
            'body_preview' => is_string($bodyRaw) ? mb_substr($bodyRaw, 0, 80) : null,
        ]);

        $data = $request->validate([
            'law_id'    => 'required|exists:laws,id',
            'title'     => 'required',
            'category'  => 'nullable',
            'doc_type'  => 'nullable',
            'source'    => 'nullable',
            'status'    => 'required|in:draft,active,archived',
            'repo_path' => 'nullable|unique:templates,repo_path',
            'comment'   => 'nullable',
            'body'      => 'required',
        ]);

        \Log::info('TEMPLATE_STORE_VALIDATED', [
            'data' => $data,
        ]);

        $body = $data['body'];
        unset($data['body']);

        $template = Template::create($data);

        \Log::info('TEMPLATE_STORE_TEMPLATE_CREATED', [
            'template_id' => $template->id,
        ]);

        TemplateVersion::create([
            'template_id' => $template->id,
            'user_id' => auth()->id(),
            'version_number' => 1,
            'body' => $body,
            'change_note' => 'Первая версия',
        ]);

        \Log::info('TEMPLATE_STORE_VERSION_CREATED', [
            'template_id' => $template->id,
        ]);

        return redirect()->route('templates.show', $template)->with('success', 'Шаблон создан.');
    }

    public function show(Template $template)
    {
        $template->load(['law', 'versions.user']);
        $latest = $template->latestVersion;

        return view('templates.show', compact('template', 'latest'));
    }

    public function edit(Template $template)
    {
        $laws = Law::orderBy('code')->get();
        $latest = $template->latestVersion;

        return view('templates.edit', compact('template', 'laws', 'latest'));
    }

    public function update(Request $request, Template $template)
    {
        \Log::info('TEMPLATE_UPDATE_ENTER', [
            'user_id' => auth()->id(),
            'template_id' => $template->id,
        ]);

        $data = $request->validate([
            'law_id'      => 'required|exists:laws,id',
            'title'       => 'required',
            'category'    => 'nullable',
            'doc_type'    => 'nullable',
            'source'      => 'nullable',
            'status'      => 'required|in:draft,active,archived',
            'repo_path'   => 'nullable|unique:templates,repo_path,' . $template->id,
            'comment'     => 'nullable',
            'body'        => 'required',
            'change_note' => 'nullable',
        ]);

        $body = $data['body'];
        $changeNote = $data['change_note'] ?? null;

        unset($data['body'], $data['change_note']);

        $template->update($data);

        $lastVersion = $template->versions()->max('version_number') ?? 0;
       
        TemplateVersion::create([
            'template_id' => $template->id,
            'user_id' => auth()->id(),
            'version_number' => $lastVersion + 1,
            'body' => $body,
            'change_note' => $changeNote,
        ]);

        return redirect()->route('templates.show', $template)->with('success', 'Шаблон обновлён, создана новая версия.');
    }

    public function destroy(Template $template)
    {
        $template->delete();
        return redirect()->route('templates.index')->with('success', 'Удалено.');
    }
}