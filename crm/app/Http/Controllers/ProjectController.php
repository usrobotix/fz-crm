<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Law;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('company', 'law')->orderBy('due_at')->paginate(20);
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        $companies = Company::orderBy('name')->get();
        $laws = Law::orderBy('code')->get();
        return view('projects.create', compact('companies', 'laws'));
    }

    public function store(Request $request)
    {
        $data = $request->validate(['company_id' => 'required|exists:companies,id', 'law_id' => 'required|exists:laws,id', 'name' => 'required', 'due_at' => 'nullable|date', 'status' => 'required|in:active,paused,completed,archived', 'notes' => 'nullable']);
        Project::create($data);
        return redirect()->route('projects.index')->with('success', 'Проект создан.');
    }

    public function show(Project $project)
    {
        $project->load(['company', 'law', 'documents.latestVersion', 'documents.assignee']);
        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        $companies = Company::orderBy('name')->get();
        $laws = Law::orderBy('code')->get();
        return view('projects.edit', compact('project', 'companies', 'laws'));
    }

    public function update(Request $request, Project $project)
    {
        $data = $request->validate(['company_id' => 'required|exists:companies,id', 'law_id' => 'required|exists:laws,id', 'name' => 'required', 'due_at' => 'nullable|date', 'status' => 'required|in:active,paused,completed,archived', 'notes' => 'nullable']);
        $project->update($data);
        return redirect()->route('projects.show', $project)->with('success', 'Обновлено.');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Удалено.');
    }
}
