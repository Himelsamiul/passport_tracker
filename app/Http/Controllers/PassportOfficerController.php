<?php

namespace App\Http\Controllers;

use App\Models\PassportOfficer;
use Illuminate\Http\Request;

class PassportOfficerController extends Controller
{
    // LIST
    public function index()
    {
        $officers = PassportOfficer::latest()->get();
        return view('backend.pages.officers.index', compact('officers'));
    }

    // CREATE FORM
    public function create()
    {
        return view('backend.pages.officers.create');
    }

    // STORE
    public function store(Request $request)
    {
        $request->validate([
            'name'    => ['required','string','max:255'],
            'phone'   => ['nullable','string','max:30'],
            'email'   => ['nullable','email','max:255'],
            'address' => ['nullable','string','max:500'],
            'status'  => ['nullable','in:ACTIVE,INACTIVE'],
            'notes'   => ['nullable','string','max:1000'],
        ]);

        PassportOfficer::create([
            'name'    => $request->name,
            'phone'   => $request->phone,
            'email'   => $request->email,
            'address' => $request->address,
            'status'  => $request->status ?? 'ACTIVE',
            'notes'   => $request->notes,
        ]);

        return redirect()->route('officers.index')->with('success','Officer created successfully.');
    }

    // EDIT FORM
    public function edit($id)
    {
        $officer = PassportOfficer::findOrFail($id);
        return view('backend.pages.officers.edit', compact('officer'));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $officer = PassportOfficer::findOrFail($id);

        $request->validate([
            'name'    => ['required','string','max:255'],
            'phone'   => ['nullable','string','max:30'],
            'email'   => ['nullable','email','max:255'],
            'address' => ['nullable','string','max:500'],
            'status'  => ['nullable','in:ACTIVE,INACTIVE'],
            'notes'   => ['nullable','string','max:1000'],
        ]);

        $officer->update([
            'name'    => $request->name,
            'phone'   => $request->phone,
            'email'   => $request->email,
            'address' => $request->address,
            'status'  => $request->status ?? 'ACTIVE',
            'notes'   => $request->notes,
        ]);

        return redirect()->route('officers.index')->with('success','Officer updated successfully.');
    }

    // DELETE
    public function destroy($id)
    {
        PassportOfficer::findOrFail($id)->delete();
        return redirect()->route('officers.index')->with('success','Officer deleted successfully.');
    }
}
