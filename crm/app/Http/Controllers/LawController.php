<?php

namespace App\Http\Controllers;

use App\Models\Law;
use Illuminate\Http\Request;

class LawController extends Controller
{
    public function index()
    {
        $laws = Law::withCount('templates', 'projects')->orderBy('code')->get();
        return view('laws.index', compact('laws'));
    }

    public function create()
    {
        return view('laws.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate(['code' => 'required|unique:laws,code', 'name' => 'required', 'description' => 'nullable']);
        Law::create($data);
        return redirect()->route('laws.index')->with('success', 'Закон добавлен.');
    }

    public function show(Law $law)
    {
        $law->load(['templates' => fn($q) => $q->orderBy('category')->orderBy('title'), 'projects.company']);
        return view('laws.show', compact('law'));
    }

    public function edit(Law $law)
    {
        return view('laws.edit', compact('law'));
    }

    public function update(Request $request, Law $law)
    {
        $data = $request->validate(['code' => 'required|unique:laws,code,' . $law->id, 'name' => 'required', 'description' => 'nullable']);
        $law->update($data);
        return redirect()->route('laws.show', $law)->with('success', 'Обновлено.');
    }

    public function destroy(Law $law)
    {
        $law->delete();
        return redirect()->route('laws.index')->with('success', 'Удалено.');
    }
}
