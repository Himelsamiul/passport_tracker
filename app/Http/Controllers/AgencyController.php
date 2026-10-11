<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use Illuminate\Http\Request;
use App\Models\Category;
class AgencyController extends Controller
{
    // LIST
    public function index(Request $request)
    {
        // Filters: q, status (ACTIVE|INACTIVE), category, date range, sort_by, per_page
        $search   = trim((string) $request->query('q', ''));
        $status   = (string) $request->query('status', '');
        $categoryId = $request->query('category_id', '');
        $dateFrom = (string) $request->query('date_from', '');
        $dateTo   = (string) $request->query('date_to', '');
        $sortBy   = (string) $request->query('sort_by', 'latest');

        $q = Agency::with('category:id,name');

        if ($search !== '') {
            $q->where(function ($qq) use ($search) {
                $qq->where('name', 'like', "%{$search}%")
                    ->orWhere('contact_person', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if ($status !== '') {
            $q->where('status', $status);
        }

        if ($categoryId !== '') {
            $q->where('category_id', $categoryId);
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

        $agencies = $q->paginate($perPage)->withQueryString();
        $categories = \App\Models\Category::orderBy('name')->get(['id', 'name']);

        return view('backend.pages.agencies.index', compact(
            'agencies', 'categories', 'search', 'status', 'categoryId', 'dateFrom', 'dateTo', 'sortBy', 'perPage'
        ));
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
