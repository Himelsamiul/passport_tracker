<?php

namespace App\Http\Controllers;

use App\Models\PassportOfficer;
use Illuminate\Http\Request;

class PassportOfficerController extends Controller
{
    // LIST
    public function index(Request $request)
    {
        // Filters: q, status (ACTIVE|INACTIVE), date range, sort_by, per_page
        $search   = trim((string) $request->query('q', ''));
        $status   = (string) $request->query('status', '');
        $dateFrom = (string) $request->query('date_from', '');
        $dateTo   = (string) $request->query('date_to', '');
        $sortBy   = (string) $request->query('sort_by', 'latest');

        $q = PassportOfficer::query();

        if ($search !== '') {
            $q->where(function ($qq) use ($search) {
                $qq->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if ($status !== '') {
            $q->where('status', $status);
        }

        if ($dateFrom !== '') {
            $q->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo !== '') {
            $q->whereDate('created_at', '<=', $dateTo);
        }

        match ($sortBy) {
            'oldest'  => $q->orderBy('created_at'),
            'name_az' => $q->orderBy('name'),
            'name_za' => $q->orderByDesc('name'),
            default   => $q->latest(),
        };

        $perPage = in_array((int) $request->query('per_page'), [10, 25, 50, 100], true)
            ? (int) $request->query('per_page')
            : 25;

        $officers = $q->paginate($perPage)->withQueryString();

        return view('backend.pages.officers.index', compact(
            'officers', 'search', 'status', 'dateFrom', 'dateTo', 'sortBy', 'perPage'
        ));
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
