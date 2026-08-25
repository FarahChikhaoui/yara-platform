<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;


class CompanyController extends Controller
{
   public function store(Request $request)
{
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'industry' => ['required', 'string', 'max:255'],
        'country' => ['required', 'string', 'max:255'],
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
