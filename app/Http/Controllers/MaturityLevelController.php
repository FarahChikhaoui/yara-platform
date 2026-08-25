<?php

namespace App\Http\Controllers;

use App\Models\MaturityLevel;
use Illuminate\Http\Request;

class MaturityLevelController extends Controller
{
    public function index()
    {
        $maturityLevels = MaturityLevel::orderBy('level')->get();

        return view('admin.maturity-levels.index', compact('maturityLevels'));
    }

    public function create()
    {
        return view('admin.maturity-levels.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'level' => 'required|integer',
            'name' => 'required',
            'min_score' => 'required|numeric',
            'max_score' => 'required|numeric',
            'description' => 'nullable',
        ]);

        MaturityLevel::create([
            'level' => $request->level,
            'name' => $request->name,
            'min_score' => $request->min_score,
            'max_score' => $request->max_score,
            'description' => $request->description,
        ]);

        return redirect('/admin/maturity-levels');
    }

    public function edit(MaturityLevel $maturityLevel)
    {
        return view('admin.maturity-levels.edit', compact('maturityLevel'));
    }

    public function update(Request $request, MaturityLevel $maturityLevel)
    {
        $request->validate([
            'level' => 'required|integer',
            'name' => 'required',
            'min_score' => 'required|numeric',
            'max_score' => 'required|numeric',
            'description' => 'nullable',
        ]);

        $maturityLevel->update([
            'level' => $request->level,
            'name' => $request->name,
            'min_score' => $request->min_score,
            'max_score' => $request->max_score,
            'description' => $request->description,
        ]);

        return redirect('/admin/maturity-levels');
    }

    public function destroy(MaturityLevel $maturityLevel)
    {
        $maturityLevel->delete();

        return redirect('/admin/maturity-levels');
    }
}