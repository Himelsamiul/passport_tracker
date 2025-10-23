<?php

// app/Http/Controllers/PassportCollectionController.php
namespace App\Http\Controllers;

use App\Models\Passport;
use App\Models\Employee;
use App\Models\PassportCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PassportCollectionController extends Controller
{
    // List of collections
public function index()
{
    $collections = \App\Models\PassportCollection::with([
        'passport.agent',
        'passport.processings.agency', // ✅ load agency name from processing
        'employee'
    ])->latest()->paginate(20);

    return view('backend.pages.collections.index', compact('collections'));
}

    // Create form
    public function create()
    {
        // Suggest only passports that are ready to be collected
        // adjust the status value to match your app (e.g., 'RECEIVED_FROM_AGENT')
        $passports = Passport::with(['agent','employee'])
            ->where('status', 'RECEIVED_FROM_AGENT')
            ->orderByDesc('id')->get();

        $employees = Employee::orderBy('name')->get();

        return view('backend.pages.collections.create', compact('passports','employees'));
    }

    // Store
   // Store
public function store(Request $request)
{
    $data = $request->validate([
        'passport_id'  => 'required|exists:passports,id',
        'employee_id'  => 'required|exists:employees,id',
        'collected_at' => 'required|date',
        'notes'        => 'nullable|string',
    ]);

    $collection = PassportCollection::create($data);

    Passport::where('id', $data['passport_id'])->update([
        'status' => 'COLLECTED_FROM_AGENCY'
    ]);

    // ✅ redirect to list (no ID param)
    return redirect()->route('collections.index')
        ->with('success', 'Passport collection recorded successfully.');
}


    // Detail page
public function show($id)
{
    $collection = PassportCollection::with([
        'passport.agent',
        'passport.passportOfficer',
        'passport.employee',
        'employee', // who collected
    ])->findOrFail($id);

    return view('backend.pages.collections.show', compact('collection'));
}

// DELETE (optional)


public function destroy($id)
{
    DB::transaction(function () use ($id) {
        $collection = \App\Models\PassportCollection::findOrFail($id);
        $passportId = $collection->passport_id;

        $collection->delete(); // delete the collection record

        // revert passport status so it is selectable again
        \App\Models\Passport::where('id', $passportId)->update([
            'status' => 'RECEIVED_FROM_AGENT',
        ]);
    });

    return redirect()->route('collections.index')
        ->with('success', 'Passport collection deleted and passport is now available for collection again.');
}

    // AJAX: return single passport with relations to auto-fill the preview
    public function passportInfo(\App\Models\Passport $passport)
{
    // Load main relations
    $passport->load(['agent', 'employee', 'passportOfficer']);

    // Try to load the latest processing (to get Employee + Agency)
    $latestProcessing = $passport->processings()
        ->with(['employee', 'agency'])
        ->latest('id')
        ->first();

    return response()->json([
        // Processing info
        'status'              => $passport->status,
        'created_at'          => $passport->created_at,
        'processing_employee' => optional($latestProcessing?->employee)->name,
        'processing_agency'   => optional($latestProcessing?->agency)->name,

        // Stakeholders
        'agent_name'          => optional($passport->agent)->name,
        'passport_officer'    => optional($passport->passportOfficer)->name,
        'received_by_original'=> optional($passport->employee)->name,

        // Passport details
        'applicant_name'      => $passport->applicant_name,
        'passport_number'     => $passport->passport_number,
        'nationality'         => $passport->nationality,
        'phone'               => $passport->phone,
        'address'             => $passport->address,
        'date_of_birth'       => $passport->date_of_birth,
        'issue_date'          => $passport->issue_date,
        'expiry_date'         => $passport->expiry_date,
        'nid_number'          => $passport->nid_number,
    ]);
}
}

