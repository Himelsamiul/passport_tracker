<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use Illuminate\Http\Request;

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
        return view('backend.pages.agencies.create');
    }

    // STORE
    public function store(Request $request)
    {
        $request->validate([
            'name'           => ['required','string','max:255','unique:agencies,name'],
            'contact_person' => ['nullable','string','max:255'],
            'phone'          => ['nullable','string','max:30'],
            'email'          => ['nullable','email','max:255'],
            'address'        => ['nullable','string','max:500'],
            'status'         => ['nullable','in:ACTIVE,INACTIVE'],
            'notes'          => ['nullable','string','max:1000'],
        ]);

        Agency::create([
            'name'           => $request->name,
            'contact_person' => $request->contact_person,
            'phone'          => $request->phone,
            'email'          => $request->email,
            'address'        => $request->address,
            'status'         => $request->status ?? 'ACTIVE',
            'notes'          => $request->notes,
        ]);

        return redirect()->route('agencies.index')->with('success', 'Agency created successfully.');
    }

    // EDIT FORM
    public function edit($id)
    {
        $agency = Agency::findOrFail($id);
        return view('backend.pages.agencies.edit', compact('agency'));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $agency = Agency::findOrFail($id);

        $request->validate([
            'name'           => ['required','string','max:255','unique:agencies,name,'.$agency->id],
            'contact_person' => ['nullable','string','max:255'],
            'phone'          => ['nullable','string','max:30'],
            'email'          => ['nullable','email','max:255'],
            'address'        => ['nullable','string','max:500'],
            'status'         => ['nullable','in:ACTIVE,INACTIVE'],
            'notes'          => ['nullable','string','max:1000'],
        ]);

        $agency->update([
            'name'           => $request->name,
            'contact_person' => $request->contact_person,
            'phone'          => $request->phone,
            'email'          => $request->email,
            'address'        => $request->address,
            'status'         => $request->status ?? 'ACTIVE',
            'notes'          => $request->notes,
        ]);

        return redirect()->route('agencies.index')->with('success', 'Agency updated successfully.');
    }

    // DELETE
    public function destroy($id)
    {
        $agency = Agency::findOrFail($id);
        $agency->delete();

        return redirect()->route('agencies.index')->with('success', 'Agency deleted successfully.');
    }
}
