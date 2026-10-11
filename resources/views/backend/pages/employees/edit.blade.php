@extends('backend.master')

@section('page-title', 'Edit Employee')
@section('page-subtitle', 'Update employee information')

@section('content')
{{-- ============================================================
     ডিজাইন নোট (কর্মী এডিট পেজে যা কাজ হয়েছে):
     • নতুন ডার্ক থিমে ফর্ম কার্ড — তৈরির পেজের সাথে একই ডিজাইন।
     • পুরনো ভ্যালু ও ফিল্ড-নেম অপরিবর্তিত।
     ============================================================ --}}
<div class="container-fluid px-0" style="max-width:760px">

  <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div>
      <h3 class="mb-0">Edit Employee</h3>
      <p class="ph-desc" style="color:var(--muted);font-size:.84rem;margin:2px 0 0">Update information for {{ $employee->name }}.</p>
    </div>
    <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary btn-sm">
      <i class="fas fa-arrow-left me-1"></i> Employee List
    </a>
  </div>

  <div class="card">
    <div class="card-body p-3 p-md-4">
      <form action="{{ route('employees.update', $employee->id) }}" method="POST">
        @csrf

        <div class="mb-3">
          <label class="form-label">Name <span class="text-danger">*</span></label>
          <input type="text" name="name" value="{{ old('name', $employee->name) }}" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Designation</label>
          <input type="text" name="designation" value="{{ old('designation', $employee->designation) }}" class="form-control">
        </div>

        <div class="mb-3">
          <label class="form-label">Phone <span class="text-danger">*</span></label>
          <input type="text" name="phone" value="{{ old('phone', $employee->phone) }}" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" value="{{ old('email', $employee->email) }}" class="form-control">
        </div>

        <div class="d-flex gap-2 mt-4">
          <button type="submit" class="btn btn-primary px-4">
            <i class="fas fa-arrows-rotate me-1"></i> Update Employee
          </button>
          <a href="{{ route('employees.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
