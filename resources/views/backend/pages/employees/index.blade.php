@extends('backend.master')

@section('page-title', 'Employees')
@section('page-subtitle', 'Internal staff handling passport files')

@section('content')
{{-- ============================================================
     ডিজাইন নোট (কর্মী লিস্টে যা কাজ হয়েছে):
     • প্লেইন লাইট টেবিল বাদ — নতুন ডার্ক থিমে পেজ হেডার + কার্ডে টেবিল।
     • হেডারে "Add Employee" বাটন; টেবিলে সফট অ্যাকশন বাটন ও empty-state।
     • "used/locked" চেক লজিক আগের মতোই (পাসপোর্ট/প্রসেসিং/কলেকশনে লিংকড কিনা)।
     ============================================================ --}}
<div class="container-fluid px-0">

  {{-- Page header --}}
  <div class="page-head d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div>
      <h3>Employees</h3>
      <p class="ph-desc">Team members who receive and process passport files.</p>
    </div>
    @if (can_do('employees.create'))
      <a href="{{ route('employees.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Add Employee
      </a>
    @endif
  </div>

  {{-- Flash --}}
  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
  @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div>   @endif

  <div class="card p-2 p-md-3">
    <div class="table-responsive">
      <table class="table table-bordered align-middle">
        <thead>
          <tr>
            <th style="width:56px">#</th>
            <th>Name</th>
            <th>Designation</th>
            <th>Phone</th>
            <th>Email</th>
            <th style="width:220px">Actions</th>
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

            <tr class="{{ $isUsed ? 'lock-row' : '' }}">
              <td>{{ $key + 1 }}</td>
              <td class="fw-semibold">{{ $employee->name }}</td>
              <td>{{ $employee->designation }}</td>
              <td>{{ $employee->phone }}</td>
              <td>{{ $employee->email }}</td>
              <td>
                <div class="d-flex flex-wrap gap-1">
                  @if (can_do('employees.edit'))
                    <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-sm btn-warning">
                      <i class="fas fa-pen"></i> Edit
                    </a>
                  @endif

                  @if($isUsed || !can_do('employees.delete'))
                    <button type="button"
                            class="btn btn-sm btn-secondary"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="{{ $isUsed ? 'You can’t delete this employee — already linked with other records.' : 'You do not have delete permission.' }}">
                      <i class="fas fa-lock"></i> Locked
                    </button>
                  @else
                    <a href="{{ route('employees.delete', $employee->id) }}"
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Are you sure you want to delete this employee?')">
                      <i class="fas fa-trash-can"></i> Delete
                    </a>
                  @endif
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6">
                <div class="empty-state">
                  <div class="es-icon"><i class="fas fa-users"></i></div>
                  <div class="fw-semibold mb-1" style="color:var(--text-soft)">No employees found</div>
                  <div>Add your first employee to get started.</div>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
