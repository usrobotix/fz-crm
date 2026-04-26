<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentVersion;
use App\Models\Project;
use App\Models\Template;
use App\Models\User;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index(Project $project)
    {
        $project->load(['documents.latestVersion', 'documents.assignee', 'company', 'law']);
        return view('documents.index', compact('project'));
    }

    public function create(Project $project)
    {
        $templates = Template::where('law_id', $project->law_id)->where('status', 'active')->orderBy('title')->get();
        $users = User::orderBy('name')->get();
        return view('documents.create', compact('project', 'templates', 'users'));
    }

    public function store(Request $request, Project $project)
    {
        $data = $request->validate([
            'title'       => 'required',
            'template_id' => 'nullable|exists:templates,id',
            'doc_type'    => 'nullable',
            'assigned_to' => 'nullable|exists:users,id',
            'body'        => 'required',
        ]);
        $body = $data['body'];
        unset($data['body']);
        $data['project_id'] = $project->id;
        $data['status'] = 'draft';
        $document = Document::create($data);
        DocumentVersion::create(['document_id' => $document->id, 'user_id' => auth()->id(), 'version_number' => 1, 'body' => $body, 'change_note' => 'Первая версия']);
        return redirect()->route('projects.documents.show', [$project, $document])->with('success', 'Документ создан.');
    }

    public function show(Project $project, Document $document)
    {
        $document->load(['versions.user', 'assignee', 'template']);
        $latest = $document->latestVersion;
        return view('documents.show', compact('project', 'document', 'latest'));
    }

    public function edit(Project $project, Document $document)
    {
        $users = User::orderBy('name')->get();
        $latest = $document->latestVersion;
        return view('documents.edit', compact('project', 'document', 'users', 'latest'));
    }

    public function update(Request $request, Project $project, Document $document)
    {
        $data = $request->validate([
            'title'       => 'required',
            'doc_type'    => 'nullable',
            'status'      => 'required|in:' . implode(',', Document::STATUSES),
            'assigned_to' => 'nullable|exists:users,id',
            'body'        => 'required',
            'change_note' => 'nullable',
        ]);
        $body = $data['body'];
        $changeNote = $data['change_note'] ?? null;
        unset($data['body'], $data['change_note']);
        $document->update($data);
        $lastVersion = $document->versions()->max('version_number') ?? 0;
        DocumentVersion::create(['document_id' => $document->id, 'user_id' => auth()->id(), 'version_number' => $lastVersion + 1, 'body' => $body, 'change_note' => $changeNote]);
        return redirect()->route('projects.documents.show', [$project, $document])->with('success', 'Документ сохранён, создана новая версия.');
    }

    public function destroy(Project $project, Document $document)
    {
        $document->delete();
        return redirect()->route('projects.documents.index', $project)->with('success', 'Удалено.');
    }
}
