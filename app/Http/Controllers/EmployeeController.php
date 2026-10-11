<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    // 🟢 LIST ALL EMPLOYEES
    public function index(Request $request)
    {
        // Filters: q, date_from/date_to (created_at), sort_by, per_page
        $search   = trim((string) $request->query('q', ''));
        $dateFrom = (string) $request->query('date_from', '');
        $dateTo   = (string) $request->query('date_to', '');
        $sortBy   = (string) $request->query('sort_by', 'latest');

        $q = Employee::query();

        if ($search !== '') {
            $q->where(function ($qq) use ($search) {
                $qq->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('designation', 'like', "%{$search}%");
            });
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

        $employees = $q->paginate($perPage)->withQueryString();

        return view('backend.pages.employees.index', compact(
            'employees', 'search', 'dateFrom', 'dateTo', 'sortBy', 'perPage'
        ));
    }

    // 🟢 SHOW CREATE FORM
    public function create()
    {
        return view('backend.pages.employees.create');
    }

    // 🟢 STORE NEW EMPLOYEE
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
        ]);

        Employee::create($request->all());

        return redirect()->route('employees.index')->with('success', 'Employee added successfully!');
    }

    // 🟢 SHOW EDIT FORM
    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        return view('backend.pages.employees.edit', compact('employee'));
    }

    // 🟢 UPDATE EMPLOYEE
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
        ]);

        $employee = Employee::findOrFail($id);
        $employee->update($request->all());

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully!');
    }

    // 🟢 DELETE EMPLOYEE
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully!');
    }
}
