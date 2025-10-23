@extends('backend.master')

@section('content')
<div class="container py-4">
  <h3 class="mb-4">Employees</h3>

  <a href="{{ route('employees.create') }}" class="btn btn-primary mb-3">Add New Employee</a>

  {{-- Success / Error Messages --}}
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
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
        @php
          // Check if employee is used anywhere
          $isUsed = 
            \App\Models\Passport::where('employee_id', $employee->id)->exists() ||
            \App\Models\PassportProcessing::where('employee_id', $employee->id)->exists() ||
            \App\Models\PassportCollection::where('employee_id', $employee->id)->exists();
        @endphp

        <tr @if($isUsed) style="background-color:#f8f9fa;" @endif>
          <td>{{ $key + 1 }}</td>
          <td>{{ $employee->name }}</td>
          <td>{{ $employee->designation }}</td>
          <td>{{ $employee->phone }}</td>
          <td>{{ $employee->email }}</td>
          <td>
            <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-sm btn-warning">
              <i class="fas fa-edit"></i> Edit
            </a>

            @if($isUsed)
              {{-- 🔒 Locked button (Employee used elsewhere) --}}
              <button type="button" 
                      class="btn btn-sm btn-secondary"
                      data-bs-toggle="tooltip"
                      data-bs-placement="top"
                      title="You can’t delete this employee — already linked with other records.">
                <i class="fas fa-lock"></i> Locked
              </button>
            @else
              {{-- 🗑️ Delete button (safe to delete) --}}
              <a href="{{ route('employees.delete', $employee->id) }}" 
                 class="btn btn-sm btn-danger"
                 onclick="return confirm('Are you sure you want to delete this employee?')">
                <i class="fas fa-trash-alt"></i> Delete
              </a>
            @endif
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="6" class="text-center text-muted">No employees found</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection

@push('scripts')
<script>
  // Enable Bootstrap tooltips globally
  document.addEventListener('DOMContentLoaded', function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })
  });
</script>
@endpush
