@extends('backend.master')

@section('content')
<div class="container py-4">
  <h3 class="mb-4">Employees</h3>

  <a href="{{ route('employees.create') }}" class="btn btn-primary mb-3">Add New Employee</a>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <table class="table table-bordered table-striped align-middle">
    <thead class="table-dark">
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Designation</th>
        <th>Phone</th>
        <th>Email</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($employees as $key => $employee)
      <tr>
        <td>{{ $key + 1 }}</td>
        <td>{{ $employee->name }}</td>
        <td>{{ $employee->designation }}</td>
        <td>{{ $employee->phone }}</td>
        <td>{{ $employee->email }}</td>
        <td>
          <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-sm btn-warning">Edit</a>
          <a href="{{ route('employees.delete', $employee->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="6" class="text-center">No employees found</td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
