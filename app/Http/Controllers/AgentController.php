<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use Illuminate\Http\Request;
use App\Models\Passport;
use App\Models\PassportProcessing;
use App\Models\PassportCollection;
use Illuminate\Support\Facades\DB;
class AgentController extends Controller
{
    // LIST

public function index(Request $request)
{
    // ------- Collect filters -------
    $search     = trim($request->get('search', ''));
    $used       = $request->get('used', '');     // '', 'used', 'unused'
    $dateFrom   = $request->get('date_from');    // Y-m-d
    $dateTo     = $request->get('date_to');      // Y-m-d
    $sortBy     = $request->get('sort_by', 'latest'); // 'latest','oldest','name_az','name_za'
    $perPage    = (int) $request->get('per_page', 10);

    // ------- Precompute "used" Agent IDs (any link in 3 tables) -------
    $usedFromPassports = Passport::query()->select('agent_id')->whereNotNull('agent_id');
    $usedFromProcessing = PassportProcessing::query()
        ->select('agency_id as agent_id')->whereNotNull('agency_id');
    $usedFromCollections = PassportCollection::query()
        ->join('passports', 'passport_collections.passport_id', '=', 'passports.id')
        ->whereNotNull('passports.agent_id')
        ->select('passports.agent_id as agent_id');

    $usedAgentIds = DB::query()
        ->fromSub(
            $usedFromPassports->union($usedFromProcessing)->union($usedFromCollections),
            'u'
        )
        ->pluck('agent_id')
        ->unique()
        ->values();

    // ------- Base query -------
    $q = Agent::query();

    // Search across name/phone/email/address
    if ($search !== '') {
        $q->where(function($qq) use ($search) {
            $like = "%{$search}%";
            $qq->where('name', 'like', $like)
               ->orWhere('phone', 'like', $like)
               ->orWhere('email', 'like', $like)
               ->orWhere('address', 'like', $like);
        });
    }

    // Date range on created_at
    if ($dateFrom) {
        $q->whereDate('created_at', '>=', $dateFrom);
    }
    if ($dateTo) {
        $q->whereDate('created_at', '<=', $dateTo);
    }

    // Used / Unused filter
    if ($used === 'used') {
        $q->whereIn('id', $usedAgentIds);
    } elseif ($used === 'unused') {
        // If none are used, this avoids passing empty [] to whereNotIn
        $q->when($usedAgentIds->isNotEmpty(), function($qq) use ($usedAgentIds) {
            $qq->whereNotIn('id', $usedAgentIds);
        }, function($qq) {
            // nobody is used => everyone is unused
            return $qq;
        });
    }

    // Sorting
    switch ($sortBy) {
        case 'oldest':
            $q->orderBy('created_at', 'asc');
            break;
        case 'name_az':
            $q->orderBy('name', 'asc');
            break;
        case 'name_za':
            $q->orderBy('name', 'desc');
            break;
        default: // latest
            $q->orderBy('created_at', 'desc');
    }

    // Pagination (persist filters)
    $agents = $q->paginate($perPage)->appends($request->query());

    // Used/Unused counts for badges (dynamic)
    $totalUsed   = Agent::whereIn('id', $usedAgentIds)->count();
    $totalUnused = Agent::when($usedAgentIds->isNotEmpty(), fn($qq)=>$qq->whereNotIn('id',$usedAgentIds))->count();
    $totalAll    = Agent::count();

    // A few dynamic options for UX (e.g., quick choices form)
    $namesForDatalist  = Agent::select('name')->whereNotNull('name')->distinct()->orderBy('name')->limit(50)->pluck('name');
    $phonesForDatalist = Agent::select('phone')->whereNotNull('phone')->distinct()->orderBy('phone')->limit(50)->pluck('phone');
    $emailsForDatalist = Agent::select('email')->whereNotNull('email')->distinct()->orderBy('email')->limit(50)->pluck('email');

    return view('backend.pages.fixed.agents.index', compact(
        'agents','usedAgentIds','totalUsed','totalUnused','totalAll',
        'search','used','dateFrom','dateTo','sortBy','perPage',
        'namesForDatalist','phonesForDatalist','emailsForDatalist'
    ));
}


    // CREATE FORM
    public function create()
    {
        return view('backend.pages.fixed.agents.create');
    }

    // STORE
    public function store(Request $request)
    {
        $request->validate([
            'name'    => ['required','string','max:255'],
            'phone'   => ['nullable','string','max:20'],
            'email'   => ['nullable','email','max:255'],
            'address' => ['nullable','string','max:255'],
        ]);

        Agent::create($request->only('name','phone','email','address'));

        return redirect()->route('agents.index')->with('success', 'Agent created.');
    }

    // EDIT FORM
    public function edit($id)
    {
        $agent = Agent::findOrFail($id);
        return view('backend.pages.fixed.agents.edit', compact('agent'));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'    => ['required','string','max:255'],
            'phone'   => ['nullable','string','max:20'],
            'email'   => ['nullable','email','max:255'],
            'address' => ['nullable','string','max:255'],
        ]);

        $agent = Agent::findOrFail($id);
        $agent->update($request->only('name','phone','email','address'));

        return redirect()->route('agents.index')->with('success', 'Agent updated.');
    }

    // DELETE
    public function destroy($id)
    {
        Agent::findOrFail($id)->delete();
        return redirect()->route('agents.index')->with('success', 'Agent deleted.');
    }
}
