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
public function index(Request $request)
{
    $status     = strtoupper((string)$request->status);
    $employeeId = $request->employee_id;
    $agencyId   = $request->agency_id;
    $search     = trim($request->q ?? '');
    $dateFrom   = $request->date_from;
    $dateTo     = $request->date_to;
    $sortBy     = $request->sort_by ?? 'latest';
    $perPage    = (int)($request->per_page ?? 20);

    $q = PassportCollection::with(['passport.agent','passport.processings.agency','employee']);

    if ($search !== '') {
        $q->whereHas('passport', function($qq) use ($search) {
            $qq->where('passport_number','like',"%{$search}%")
               ->orWhere('applicant_name','like',"%{$search}%");
        });
    }

    if ($status) {
        // filter by latest processing status on that passport
        $q->whereHas('passport.processings', function($qq) use ($status) {
            $qq->where('status', $status);
        });
    }

    if ($employeeId) $q->where('employee_id', $employeeId);

    if ($agencyId) {
        $q->whereHas('passport.processings', function($qq) use ($agencyId) {
            $qq->where('agency_id', $agencyId);
        });
    }

    if ($dateFrom && $dateTo) {
        $q->whereBetween('collected_at', [$dateFrom.' 00:00:00', $dateTo.' 23:59:59']);
    } elseif ($dateFrom) {
        $q->where('collected_at', '>=', $dateFrom.' 00:00:00');
    } elseif ($dateTo) {
        $q->where('collected_at', '<=', $dateTo.' 23:59:59');
    }

    // sorting
    switch ($sortBy) {
        case 'oldest':  $q->oldest('id'); break;
        case 'name_az': $q->whereHas('passport', fn($qq)=>$qq->orderBy('applicant_name','asc')); break;
        case 'name_za': $q->whereHas('passport', fn($qq)=>$qq->orderBy('applicant_name','desc')); break;
        default:        $q->latest('id'); // latest
    }

    $collections = $q->paginate($perPage)->withQueryString();

    // lists for filters
    $employees = Employee::orderBy('name')->get(['id','name']);
    $agencies  = \App\Models\Agency::orderBy('name')->get(['id','name']);

    return view('backend.pages.collections.index', compact(
        'collections','employees','agencies',
        'search','status','employeeId','agencyId','dateFrom','dateTo','perPage','sortBy'
    ));
}


    // Create form
    public function create()
    {
        // Suggest only passports that are ready to be collected
        // adjust the status value to match your app (e.g., 'RECEIVED_FROM_AGENT')
$passports = Passport::with(['agent','employee'])
    ->where('status', 'RECEIVED_FROM_AGENT')
    ->whereHas('processings') // ✅ only passports that have at least one processing
    ->orderByDesc('id')
    ->get();

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

