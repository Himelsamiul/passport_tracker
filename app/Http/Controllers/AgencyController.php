<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use Illuminate\Http\Request;
use App\Models\Category;
class AgencyController extends Controller
{
    // LIST
    public function index()
    {
        $agencies = Agency::latest()->get();
        return view('backend.pages.agencies.index', compact('agencies'));
    }

    // CREATE FORM
    public function create()
{
    // Only active categories in dropdown (change if you want all)
    $categories = Category::where('status', 'active')
                    ->orderBy('name')
                    ->get(['id','name']);
    return view('backend.pages.agencies.create', compact('categories'));
}

public function store(Request $request)
{
    $validated = $request->validate([
        'category_id'    => 'nullable|exists:categories,id', // use 'required' if you want it mandatory
        'name'           => 'required|string|max:255',
        'contact_person' => 'nullable|string|max:255',
        'phone'          => 'nullable|string|max:50',
        'email'          => 'nullable|email|max:255',
        'address'        => 'nullable|string|max:2000',
        'status'         => 'required|in:ACTIVE,INACTIVE',
        'notes'          => 'nullable|string|max:2000',
    ]);

    Agency::create($validated);

    return redirect()->route('agencies.index')->with('success','Agency created successfully.');
}

public function edit($id)
{
    $agency = Agency::findOrFail($id);
    $categories = Category::where('status', 'active')
                    ->orderBy('name')
                    ->get(['id','name']);
    return view('backend.pages.agencies.edit', compact('agency','categories'));
}

public function update(Request $request, $id)
{
    $validated = $request->validate([
        'category_id'    => 'nullable|exists:categories,id', // or 'required'
        'name'           => 'required|string|max:255',
        'contact_person' => 'nullable|string|max:255',
        'phone'          => 'nullable|string|max:50',
        'email'          => 'nullable|email|max:255',
        'address'        => 'nullable|string|max:2000',
        'status'         => 'required|in:ACTIVE,INACTIVE',
        'notes'          => 'nullable|string|max:2000',
    ]);

    $agency = Agency::findOrFail($id);
    $agency->update($validated);

    return redirect()->route('agencies.index')->with('success','Agency updated successfully.');
}
    // DELETE
    public function destroy($id)
    {
        $agency = Agency::findOrFail($id);
        $agency->delete();

        return redirect()->route('agencies.index')->with('success', 'Agency deleted successfully.');
    }
}
