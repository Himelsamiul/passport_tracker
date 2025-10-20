<?php

namespace App\Http\Controllers;

use App\Models\Passport;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PassportController extends Controller
{
    // LIST
    public function index()
    {
        $passports = Passport::with('agent')->latest()->get();
        return view('backend.pages.fixed.passports.index', compact('passports'));
    }

    // CREATE FORM
    public function create()
    {
        $agents = Agent::orderBy('name')->get(['id','name']);
        return view('backend.pages.fixed.passports.create', compact('agents'));
    }

    // STORE
    public function store(Request $request)
    {
        $request->validate([
            'agent_id'        => ['required','exists:agents,id'],
            'applicant_name'  => ['required','string','max:255'],
            'address'         => ['nullable','string','max:255'],
            'phone'           => ['nullable','string','max:20'],
            'date_of_birth'   => ['nullable','date'],
            'passport_number' => ['required','string','max:50','unique:passports,passport_number'],
            'nationality'     => ['nullable','string','max:100'],
            'passport_picture'=> ['nullable','image','max:2048'], // 2MB
            'issue_date'      => ['nullable','date'],
            'expiry_date'     => ['nullable','date','after_or_equal:issue_date'],
            'nid_number'      => ['nullable','string','max:50'],
        ]);

        $path = null;
        if ($request->hasFile('passport_picture')) {
            $path = $request->file('passport_picture')->store('passports', 'public');
        }

        Passport::create([
            'agent_id'        => $request->agent_id,
            'applicant_name'  => $request->applicant_name,
            'address'         => $request->address,
            'phone'           => $request->phone,
            'date_of_birth'   => $request->date_of_birth,
            'passport_number' => $request->passport_number,
            'nationality'     => $request->nationality,
            'passport_picture'=> $path,
            'issue_date'      => $request->issue_date,
            'expiry_date'     => $request->expiry_date,
            'nid_number'      => $request->nid_number,
            'status'          => 'RECEIVED_FROM_AGENT',
        ]);

        return redirect()->route('passports.index')->with('success', 'Passport created.');
    }

    // EDIT FORM
    public function edit($id)
    {
        $passport = Passport::findOrFail($id);
        $agents   = Agent::orderBy('name')->get(['id','name']);
        return view('backend.pages.fixed.passports.edit', compact('passport','agents'));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $passport = Passport::findOrFail($id);

        $request->validate([
            'agent_id'        => ['required','exists:agents,id'],
            'applicant_name'  => ['required','string','max:255'],
            'address'         => ['nullable','string','max:255'],
            'phone'           => ['nullable','string','max:20'],
            'date_of_birth'   => ['nullable','date'],
            'passport_number' => ['required','string','max:50','unique:passports,passport_number,'.$passport->id],
            'nationality'     => ['nullable','string','max:100'],
            'passport_picture'=> ['nullable','image','max:2048'],
            'issue_date'      => ['nullable','date'],
            'expiry_date'     => ['nullable','date','after_or_equal:issue_date'],
            'nid_number'      => ['nullable','string','max:50'],
        ]);

        $path = $passport->passport_picture;
        if ($request->hasFile('passport_picture')) {
            // delete old if exists
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            $path = $request->file('passport_picture')->store('passports', 'public');
        }

        $passport->update([
            'agent_id'        => $request->agent_id,
            'applicant_name'  => $request->applicant_name,
            'address'         => $request->address,
            'phone'           => $request->phone,
            'date_of_birth'   => $request->date_of_birth,
            'passport_number' => $request->passport_number,
            'nationality'     => $request->nationality,
            'passport_picture'=> $path,
            'issue_date'      => $request->issue_date,
            'expiry_date'     => $request->expiry_date,
            'nid_number'      => $request->nid_number,
        ]);

        return redirect()->route('passports.index')->with('success', 'Passport updated.');
    }

    // DELETE
    public function destroy($id)
    {
        $passport = Passport::findOrFail($id);

        // delete stored file if any
        if ($passport->passport_picture && Storage::disk('public')->exists($passport->passport_picture)) {
            Storage::disk('public')->delete($passport->passport_picture);
        }

        $passport->delete();

        return redirect()->route('passports.index')->with('success', 'Passport deleted.');
    }
}
