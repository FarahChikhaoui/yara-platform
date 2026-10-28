<?php

namespace App\Http\Controllers;

use App\Models\Dimension;
use App\Services\AuditLogger;
use Illuminate\Http\Request;

class DimensionController extends Controller
{
    public function index()
    {
        $dimensions = Dimension::all();

        return view('admin.dimensions.index', compact('dimensions'));
    }

    public function create()
    {
        return view('admin.dimensions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'code' => 'nullable',
            'weight' => 'nullable|numeric',
        ]);

        $dimension = Dimension::create([
            'code' => $request->code,
            'name' => $request->name,
            'weight' => $request->weight,
            'description' => $request->description,
        ]);

        AuditLogger::log(
            'dimension_created',
            $dimension,
            "Created assessment dimension: {$dimension->name}"
        );

        return redirect('/admin/dimensions');
    }

    public function edit(Dimension $dimension)
    {
        return view('admin.dimensions.edit', compact('dimension'));
    }

    public function update(Request $request, Dimension $dimension)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'code' => 'nullable',
            'weight' => 'nullable|numeric',
        ]);

        $dimension->update([
            'code' => $request->code,
            'name' => $request->name,
            'weight' => $request->weight,
            'description' => $request->description,
        ]);

        AuditLogger::log(
            'dimension_updated',
            $dimension,
            "Updated assessment dimension: {$dimension->name}"
        );

        return redirect('/admin/dimensions');
    }

    public function destroy(Dimension $dimension)
    {
        AuditLogger::log(
            'dimension_deleted',
            $dimension,
            "Deleted assessment dimension: {$dimension->name}"
        );

        $dimension->delete();

        return redirect('/admin/dimensions');
    }
}