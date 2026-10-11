<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Show list
    public function index(Request $request)
    {
        // Filters: q, status (active|inactive), date range, sort_by, per_page
        $search   = trim((string) $request->query('q', ''));
        $status   = (string) $request->query('status', '');
        $dateFrom = (string) $request->query('date_from', '');
        $dateTo   = (string) $request->query('date_to', '');
        $sortBy   = (string) $request->query('sort_by', 'latest');

        $q = Category::query();

        if ($search !== '') {
            $q->where('name', 'like', "%{$search}%");
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

        $categories = $q->paginate($perPage)->withQueryString();

        return view('backend.pages.categories.index', compact(
            'categories', 'search', 'status', 'dateFrom', 'dateTo', 'sortBy', 'perPage'
        ));
    }

    // Create form
    public function create()
    {
        return view('backend.pages.categories.create');
    }

    // Store new category
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        Category::create($request->all());

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    // Edit form
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('backend.pages.categories.edit', compact('category'));
    }

    // Update category
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $category = Category::findOrFail($id);
        $category->update($request->all());

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    // Delete
    public function destroy($id)
    {
        Category::findOrFail($id)->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}
