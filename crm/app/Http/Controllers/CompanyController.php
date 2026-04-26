<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::withCount('projects')->orderBy('name')->paginate(20);
        return view('companies.index', compact('companies'));
    }

    public function create()
    {
        return view('companies.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => 'required', 'inn' => 'nullable', 'contact_person' => 'nullable', 'email' => 'nullable|email', 'phone' => 'nullable', 'notes' => 'nullable']);
        Company::create($data);
        return redirect()->route('companies.index')->with('success', 'Компания добавлена.');
    }

    public function show(Company $company)
    {
        $company->load('projects.law');
        return view('companies.show', compact('company'));
    }

    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $data = $request->validate(['name' => 'required', 'inn' => 'nullable', 'contact_person' => 'nullable', 'email' => 'nullable|email', 'phone' => 'nullable', 'notes' => 'nullable']);
        $company->update($data);
        return redirect()->route('companies.show', $company)->with('success', 'Обновлено.');
    }

    public function destroy(Company $company)
    {
        $company->delete();
        return redirect()->route('companies.index')->with('success', 'Удалено.');
    }
}
