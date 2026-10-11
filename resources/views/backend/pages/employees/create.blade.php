@extends('backend.master')

@section('page-title', 'Add Employee')
@section('page-subtitle', 'Register a new team member')

@section('content')
{{-- ============================================================
     ডিজাইন নোট (কর্মী তৈরির পেজে যা কাজ হয়েছে):
     • আগের বেগুনি/লাইট কাস্টম CSS ("employee-scope") পুরো বাদ।
     • নতুন ডার্ক থিমে আইকনসহ ফর্ম কার্ড — অন্য ফর্ম পেজের সাথে একই ডিজাইন।
     • ফিল্ড: নাম*, পদবি, ফোন*, ইমেইল* — আগের লজিক অক্ষুণ্ণ।
     ============================================================ --}}
<div class="container-fluid px-0" style="max-width:760px">

  <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div>
      <h3 class="mb-0"><i class="fas fa-user-plus me-2" style="color:var(--accent-2)"></i>Add New Employee</h3>
      <p class="ph-desc" style="color:var(--muted);font-size:.84rem;margin:2px 0 0">Register a team member who handles passport files.</p>
    </div>
    <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary btn-sm">
      <i class="fas fa-arrow-left me-1"></i> Employee List
    </a>
  </div>

  <div class="card">
    <div class="card-body p-3 p-md-4">
      @if($errors->any())
        <div class="alert alert-danger">
          <strong>Please fix the following errors:</strong>
          <ul class="mt-2 mb-0 ps-3">
            @foreach($errors->all() as $err)
              <li>{{ $err }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('employees.store') }}" method="POST">
        @csrf

        <!-- Name (required) -->
        <div class="mb-3">
          <label class="form-label">Name <span class="text-danger">*</span></label>
          <div class="input-group">
            <span class="input-group-text"><i class="fas fa-user"></i></span>
            <input type="text" name="name" class="form-control" placeholder="Enter full name" value="{{ old('name') }}" required>
          </div>
        </div>

        <!-- Designation (optional) -->
        <div class="mb-3">
          <label class="form-label">Designation</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fas fa-briefcase"></i></span>
            <input type="text" name="designation" class="form-control" placeholder="Enter designation" value="{{ old('designation') }}">
          </div>
        </div>

        <!-- Phone (required) -->
        <div class="mb-3">
          <label class="form-label">Phone <span class="text-danger">*</span></label>
          <div class="input-group">
            <span class="input-group-text"><i class="fas fa-phone"></i></span>
            <input type="text" name="phone" class="form-control" placeholder="Enter phone number" value="{{ old('phone') }}" required>
          </div>
        </div>

        <!-- Email (required) -->
        <div class="mb-3">
          <label class="form-label">Email <span class="text-danger">*</span></label>
          <div class="input-group">
            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
            <input type="email" name="email" class="form-control" placeholder="Enter email address" value="{{ old('email') }}" required>
          </div>
        </div>

        <div class="d-flex gap-2 mt-4">
          <button type="submit" class="btn btn-primary px-4">
            <i class="fas fa-check me-1"></i> Save Employee
          </button>
          <a href="{{ route('employees.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
