<?php
// app/Http/Controllers/PassportProcessingController.php
namespace App\Http\Controllers;

use App\Models\Passport;
use App\Models\Employee;
use App\Models\Agency;
use App\Models\PassportProcessing;
use Illuminate\Http\Request;

class PassportProcessingController extends Controller
{
public function index(\Illuminate\Http\Request $request)
{
    // 1) Read query params (filters)
    $status     = $request->query('status');        // PENDING | IN_PROGRESS | DONE | REJECTED
    $employeeId = $request->query('employee_id');   // int
    $agencyId   = $request->query('agency_id');     // int or "__NULL__" if you decide to support unassigned filter
    $search     = $request->query('q');             // applicant or passport no
    $dateFrom   = $request->query('date_from');     // YYYY-MM-DD
    $dateTo     = $request->query('date_to');       // YYYY-MM-DD

    // 2) Base query with eager loading (prevents N+1)
    $query = \App\Models\PassportProcessing::query()
        ->with([
            'passport:id,passport_number,applicant_name',
            'employee:id,name',
            'agency:id,name',
        ])
        ->orderByDesc('created_at'); // newest first

    // 3) Apply filters
    if ($status) {
        $query->where('status', $status);
    }

    if ($employeeId) {
        $query->where('employee_id', $employeeId);
    }

    if ($agencyId) {
        // If you want to support "Unassigned" filter later:
        if ($agencyId === '__NULL__') {
            $query->whereNull('agency_id');
        } else {
            $query->where('agency_id', $agencyId);
        }
    }

    if ($search) {
        $query->whereHas('passport', function ($q) use ($search) {
            $q->where('passport_number', 'like', "%{$search}%")
              ->orWhere('applicant_name', 'like', "%{$search}%");
        });
    }

    if ($dateFrom) {
        $query->whereDate('created_at', '>=', $dateFrom);
    }
    if ($dateTo) {
        $query->whereDate('created_at', '<=', $dateTo);
    }

    // 4) Data for dropdowns
    $employees = \App\Models\Employee::select('id','name')->orderBy('name')->get();
    $agencies  = \App\Models\Agency::select('id','name')->orderBy('name')->get();

    // 5) Summary counts (based on current filters)
    $totals = [
        'total'       => (clone $query)->count(),
        'pending'     => (clone $query)->where('status', 'PENDING')->count(),
        'in_progress' => (clone $query)->where('status', 'IN_PROGRESS')->count(),
        'done'        => (clone $query)->where('status', 'DONE')->count(),
        'rejected'    => (clone $query)->where('status', 'REJECTED')->count(),
    ];

    // 6) Pagination (keeps filters in URL)
    $processings = $query->paginate(20)->withQueryString();

    return view('backend.pages.processings.index', compact(
        'processings', 'employees', 'agencies', 'totals',
        'status','employeeId','agencyId','search','dateFrom','dateTo'
    ));
}

public function create()
{
    // Only passports that do NOT already have a processing record
    $passports = Passport::whereDoesntHave('processings')
        ->select('id','passport_number','applicant_name')
        ->latest()
        ->get();

    $employees = Employee::select('id','name')->orderBy('name')->get();
    $agencies  = Agency::select('id','name')->orderBy('name')->get();

    return view('backend.pages.processings.create', compact('passports','employees','agencies'));
}

    // returns ALL "Add Passport" fields for a given passport id
    public function passportDetails(Passport $passport)
    {
        $passport->load(['agent','employee','passportOfficer']); // make sure relations exist on Passport model

        return response()->json([
            // foreigns (names for display + ids in case you want to show them)
            'agent' => [
                'id'   => $passport->agent_id,
                'name' => optional($passport->agent)->name,
            ],
            'passport_officer' => [
                'id'   => $passport->passport_officer_id,
                'name' => optional($passport->passportOfficer)->name,
            ],
            'received_by' => [
                'id'   => $passport->employee_id,
                'name' => optional($passport->employee)->name,
            ],

            // core fields
            'applicant_name'  => $passport->applicant_name,
            'phone'           => $passport->phone,
            'address'         => $passport->address,
            'date_of_birth'   => optional($passport->date_of_birth)->format('d M Y'),
            'nationality'     => $passport->nationality,
            'passport_number' => $passport->passport_number,
            'issue_date'      => optional($passport->issue_date)->format('d M Y'),
            'expiry_date'     => optional($passport->expiry_date)->format('d M Y'),
            'nid_number'      => $passport->nid_number,
            'notes'           => $passport->notes,
            'picture_url'     => $passport->passport_picture ? asset('storage/'.$passport->passport_picture) : null,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'passport_id' => ['required','exists:passports,id'],
            'employee_id' => ['required','exists:employees,id'],  // picked up by
            'agency_id'   => ['nullable','exists:agencies,id'],
            'status'      => ['required','in:PENDING,IN_PROGRESS,DONE,REJECTED'],
            'notes'       => ['nullable','string'],
        ]);

        PassportProcessing::create($validated);

        return redirect()->route('processings.create')->with('success', 'Processing record created successfully.');
    }

    public function show(PassportProcessing $processing)
{
    // load EVERYTHING we need for full view
    $processing->load([
        'employee:id,name',
        'agency:id,name',
        'passport' => function($q){
            $q->select('id','agent_id','employee_id','passport_officer_id','applicant_name','phone','address',
                       'date_of_birth','nationality','passport_number','issue_date','expiry_date',
                       'nid_number','notes','passport_picture','status','created_at','updated_at')
              ->with([
                  'agent:id,name',
                  'employee:id,name',
                  'passportOfficer:id,name'
              ]);
        }
    ]);

    return view('backend.pages.processings.show', compact('processing'));
}

public function edit(PassportProcessing $processing)
{
    $processing->load(['employee:id,name','agency:id,name','passport:id,passport_number,applicant_name']);
    $employees = Employee::select('id','name')->orderBy('name')->get();
    $agencies  = Agency::select('id','name')->orderBy('name')->get();

    return view('backend.pages.processings.edit', compact('processing','employees','agencies'));
}

public function update(\Illuminate\Http\Request $request, PassportProcessing $processing)
{
    $validated = $request->validate([
        'employee_id' => ['required','exists:employees,id'],
        'agency_id'   => ['nullable','exists:agencies,id'],
        'status'      => ['required','in:PENDING,IN_PROGRESS,DONE,REJECTED'],
        'notes'       => ['nullable','string'],
    ]);

    $processing->update($validated);

    return redirect()->route('processings.index')->with('success','Processing updated successfully.');
}

public function destroy(PassportProcessing $processing)
{
    $processing->delete();
    return redirect()->route('processings.index')->with('success','Processing deleted.');
}
}
