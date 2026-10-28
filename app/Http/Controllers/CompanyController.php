<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Validation\Rule;


class CompanyController extends Controller
{
   public function store(Request $request)
{
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
'industry' => [
    'required',
    'string',
    Rule::in([
        'Technology & Software',
        'Banking & Financial Services',
        'Insurance',
        'Healthcare & Life Sciences',
        'Manufacturing',
        'Consumer Goods & Retail',
        'Telecommunications',
        'Energy & Utilities',
        'Transportation & Logistics',
        'Professional Services & Consulting',
        'Media & Entertainment',
        'Real Estate & Construction',
        'Education',
        'Government & Public Sector',
        'Agriculture & Food',
        'Other',
    ]),
],        'country' => ['required', 'string', 'max:255'],
    ]);

    $company = Company::create([
        'name' => $validated['name'],
        'industry' => $validated['industry'],
        'country' => $validated['country'],
    ]);

    auth()->user()->update([
        'company_id' => $company->id,
    ]);

    if (session('assessment_intent') === 'transformation') {
        return redirect()->route('transformation.start');
    }

    if (session('assessment_intent') === 'assessment') {
        return redirect()->route('assessment.start');
    }

    return redirect('/dashboard');
}
}
