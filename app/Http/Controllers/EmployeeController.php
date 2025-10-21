<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    // 🟢 LIST ALL EMPLOYEES
    public function index()
    {
        $employees = Employee::latest()->get();
        return view('backend.pages.employees.index', compact('employees'));
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
