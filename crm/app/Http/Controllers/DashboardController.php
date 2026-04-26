<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentVersion;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $statusCounts = Document::select('status', DB::raw('count(*) as total'))
            ->whereHas('project', fn($q) => $q->where('status', 'active'))
            ->groupBy('status')->get()->pluck('total', 'status');

        $upcomingProjects = Project::with('company', 'law')
            ->where('status', 'active')
            ->whereNotNull('due_at')
            ->orderBy('due_at')
            ->take(5)->get();

        $recentVersions = DocumentVersion::with(['document.project', 'user'])
            ->orderByDesc('created_at')->take(10)->get();

        return view('dashboard', compact('statusCounts', 'upcomingProjects', 'recentVersions'));
    }
}
