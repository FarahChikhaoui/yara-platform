<?php

namespace App\Http\Controllers;

use App\Models\Dimension;
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

        Dimension::create([
            'code' => $request->code,
    'name' => $request->name,
    'weight' => $request->weight,
    'description' => $request->description,
        ]);

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

        return redirect('/admin/dimensions');
    }

    public function destroy(Dimension $dimension)
    {
        $dimension->delete();

        return redirect('/admin/dimensions');
    }
}