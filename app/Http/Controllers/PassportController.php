<?php

namespace App\Http\Controllers;

use App\Models\Passport;
use App\Models\Agent;
use App\Models\Employee;
use App\Models\PassportOfficer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PassportController extends Controller
{
    // LIST
public function index(Request $request)
{
    // Read inputs safely
    $search   = trim((string) $request->query('search', ''));
    $status   = $request->query('status', '');
    $agentId  = $request->query('agent_id', '');
    $offId    = $request->query('officer_id', '');
    $empId    = $request->query('employee_id', '');
    $dateFrom = $request->query('date_from');   // Y-m-d or null
    $dateTo   = $request->query('date_to');     // Y-m-d or null
    $perPage  = (int) $request->query('per_page', 25);
    $sortBy   = $request->query('sort_by', 'latest');

    // sanitize perPage
    if (! in_array($perPage, [10,25,50,100], true)) $perPage = 25;

    $q = Passport::with(['agent','employee','passportOfficer']);

    // SEARCH (multi-term: "abc 123" -> "%abc%123%")
    if ($search !== '') {
        $needle = '%'.preg_replace('/\s+/', '%', $search).'%';
        $q->where(function($qq) use ($needle){
            $qq->where('passport_number', 'like', $needle)
               ->orWhere('applicant_name', 'like', $needle)
               ->orWhere('phone', 'like', $needle)
               ->orWhere('address', 'like', $needle)
               ->orWhere('nid_number', 'like', $needle);
        });
    }

    // EXACT FILTERS (only if provided)
    if ($status !== '')   { $q->where('status', $status); }
    if ($agentId !== '')  { $q->where('agent_id', $agentId); }
    if ($offId !== '')    { $q->where('passport_officer_id', $offId); }
    if ($empId !== '')    { $q->where('employee_id', $empId); }

    // DATE RANGE (apply only when both are valid Y-m-d)
    $isYmd = fn($d)=> is_string($d) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $d);
    if ($isYmd($dateFrom) && $isYmd($dateTo)) {
        $q->whereDate('created_at', '>=', $dateFrom)
          ->whereDate('created_at', '<=', $dateTo);
    }

    // SORT
    switch ($sortBy) {
        case 'oldest':  $q->orderBy('created_at','asc'); break;
        case 'name_az': $q->orderBy('applicant_name','asc'); break;
        case 'name_za': $q->orderBy('applicant_name','desc'); break;
        default:        $q->orderBy('created_at','desc');
    }

    $passports = $q->paginate($perPage)->appends($request->query());

    // dropdown data
    $agents    = \App\Models\Agent::orderBy('name')->get(['id','name']);
    $employees = \App\Models\Employee::orderBy('name')->get(['id','name']);
    $officers  = \App\Models\PassportOfficer::orderBy('name')->get(['id','name']);

    return view('backend.pages.fixed.passports.index', compact(
        'passports','agents','employees','officers',
        'search','status','agentId','offId','empId','dateFrom','dateTo','perPage','sortBy'
    ));
}

public function show($id)
{
    $passport = Passport::with(['agent','passportOfficer','employee'])->findOrFail($id);
    return view('backend.pages.fixed.passports.show', compact('passport'));
}

    // CREATE FORM
    public function create()
    {
        $agents    = Agent::orderBy('name')->get(['id', 'name']);
        $employees = Employee::orderBy('name')->get(['id', 'name']);
        $officers  = PassportOfficer::orderBy('name')->get(['id', 'name']);
        return view('backend.pages.fixed.passports.create', compact('agents', 'employees', 'officers'));
    }

    // STORE
    public function store(Request $request)
    {
        $request->validate([
            'agent_id'             => ['required', 'exists:agents,id'],
            'passport_officer_id'  => ['nullable', 'exists:passport_officers,id'],
            'employee_id'          => ['required', 'exists:employees,id'],
            'applicant_name'       => ['required', 'string', 'max:255'],
            'address'              => ['nullable', 'string', 'max:255'],
            'phone'                => ['nullable', 'string', 'max:20'],
            'date_of_birth'        => ['nullable', 'date'],
            'passport_number'      => ['required', 'string', 'max:50', 'unique:passports,passport_number'],
            'nationality'          => ['nullable', 'string', 'max:100'],
            'passport_picture'     => ['nullable', 'image', 'max:2048'],
            'issue_date'           => ['nullable', 'date'],
            'expiry_date'          => ['nullable', 'date', 'after_or_equal:issue_date'],
            'nid_number'           => ['nullable', 'string', 'max:50'],
            'notes'                => ['nullable', 'string', 'max:1000'],
        ]);

        $path = null;
        if ($request->hasFile('passport_picture')) {
            $path = $request->file('passport_picture')->store('passports', 'public');
        }

        Passport::create([
            'agent_id'            => $request->agent_id,
            'passport_officer_id' => $request->passport_officer_id,
            'employee_id'         => $request->employee_id,
            'applicant_name'      => $request->applicant_name,
            'address'             => $request->address,
            'phone'               => $request->phone,
            'date_of_birth'       => $request->date_of_birth,
            'passport_number'     => $request->passport_number,
            'nationality'         => $request->nationality,
            'passport_picture'    => $path,
            'issue_date'          => $request->issue_date,
            'expiry_date'         => $request->expiry_date,
            'nid_number'          => $request->nid_number,
            'notes'               => $request->notes,
            'status'              => 'RECEIVED_FROM_AGENT',
        ]);

        return redirect()->route('passports.index')->with('success', 'Passport created successfully.');
    }

    // EDIT FORM
    public function edit($id)
    {
        $passport  = Passport::findOrFail($id);
        $agents    = Agent::orderBy('name')->get(['id', 'name']);
        $employees = Employee::orderBy('name')->get(['id', 'name']);
        $officers  = PassportOfficer::orderBy('name')->get(['id', 'name']);
        return view('backend.pages.fixed.passports.edit', compact('passport', 'agents', 'employees', 'officers'));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $passport = Passport::findOrFail($id);

        $request->validate([
            'agent_id'             => ['required', 'exists:agents,id'],
            'passport_officer_id'  => ['nullable', 'exists:passport_officers,id'],
            'employee_id'          => ['required', 'exists:employees,id'],
            'applicant_name'       => ['required', 'string', 'max:255'],
            'address'              => ['nullable', 'string', 'max:255'],
            'phone'                => ['nullable', 'string', 'max:20'],
            'date_of_birth'        => ['nullable', 'date'],
            'passport_number'      => ['required', 'string', 'max:50', 'unique:passports,passport_number,' . $passport->id],
            'nationality'          => ['nullable', 'string', 'max:100'],
            'passport_picture'     => ['nullable', 'image', 'max:2048'],
            'issue_date'           => ['nullable', 'date'],
            'expiry_date'          => ['nullable', 'date', 'after_or_equal:issue_date'],
            'nid_number'           => ['nullable', 'string', 'max:50'],
            'notes'                => ['nullable', 'string', 'max:1000'],
        ]);

        $path = $passport->passport_picture;
        if ($request->hasFile('passport_picture')) {
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            $path = $request->file('passport_picture')->store('passports', 'public');
        }

        $passport->update([
            'agent_id'            => $request->agent_id,
            'passport_officer_id' => $request->passport_officer_id,
            'employee_id'         => $request->employee_id,
            'applicant_name'      => $request->applicant_name,
            'address'             => $request->address,
            'phone'               => $request->phone,
            'date_of_birth'       => $request->date_of_birth,
            'passport_number'     => $request->passport_number,
            'nationality'         => $request->nationality,
            'passport_picture'    => $path,
            'issue_date'          => $request->issue_date,
            'expiry_date'         => $request->expiry_date,
            'nid_number'          => $request->nid_number,
            'notes'               => $request->notes,
        ]);

        return redirect()->route('passports.index')->with('success', 'Passport updated successfully.');
    }

    // DELETE
    public function destroy($id)
    {
        $passport = Passport::findOrFail($id);

        if ($passport->passport_picture && Storage::disk('public')->exists($passport->passport_picture)) {
            Storage::disk('public')->delete($passport->passport_picture);
        }

        $passport->delete();

        return redirect()->route('passports.index')->with('success', 'Passport deleted successfully.');
    }
}
