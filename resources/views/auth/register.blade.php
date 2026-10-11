@extends('backend.master')

@section('page-title', 'Create Registration')
@section('page-subtitle', 'Register a user through the admin panel')

@section('content')
{{-- ============================================================
     ডিজাইন নোট (রেজিস্ট্রেশন তৈরির পেজে যা কাজ হয়েছে):
     • সবুজ/বেগুনি গ্লাস কাস্টম CSS পুরো বাদ — নতুন ডার্ক থিমে ফর্ম কার্ড।
     • ফিল্ড: নাম (কর্মী তালিকা থেকে), ইমেইল, পাসওয়ার্ড+কনফার্ম (চোখ বাটনসহ), স্ট্যাটাস।
     • পাসওয়ার্ড দেখার টগল ও সব লজিক আগের মতোই।
     ============================================================ --}}
<div class="container-fluid px-0" style="max-width:820px">

  <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div>
      <h3 class="mb-0"><i class="fas fa-user-plus me-2" style="color:var(--accent-2)"></i>Create Registration</h3>
      <p class="ph-desc" style="color:var(--muted);font-size:.84rem;margin:2px 0 0">Add a user who can sign in to the platform.</p>
    </div>
    <a href="{{ route('register.list') }}" class="btn btn-outline-secondary btn-sm">
      <i class="fas fa-list me-1"></i> View List
    </a>
  </div>

  <div class="card">
    <div class="card-body p-3 p-md-4">
      @if(session('success'))
        <div class="alert alert-success d-flex align-items-center">
          <i class="fas fa-circle-check me-2"></i>
          <div>{{ session('success') }}</div>
        </div>
      @endif

      @if($errors->any())
        <div class="alert alert-danger">
          <div class="d-flex align-items-center mb-2">
            <i class="fas fa-triangle-exclamation me-2"></i>
            <strong>Please fix the following errors:</strong>
          </div>
          <ul class="m-0 ps-3">
            @foreach($errors->all() as $err)
              <li>{{ $err }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('register.store') }}" method="POST">
        @csrf
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Full Name <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-user"></i></span>
              <select name="name" class="form-select" required>
                <option value="">Select Employee</option>
                @foreach($employees as $emp)
                  <option value="{{ $emp->name }}" {{ old('name') == $emp->name ? 'selected' : '' }}>
                    {{ $emp->name }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="col-md-6">
            <label class="form-label">Email <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-envelope"></i></span>
              <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                     placeholder="Enter email address" required>
            </div>
          </div>

          <div class="col-md-6">
            <label class="form-label">Password <span class="text-danger">*</span></label>
            <div class="position-relative">
              <input type="password" name="password" id="password" class="form-control pe-5"
                     placeholder="Create a password" required>
              <button type="button" class="pw-toggle" id="togglePassword" aria-label="Show password">
                <i class="fas fa-eye"></i>
              </button>
            </div>
          </div>

          <div class="col-md-6">
            <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
            <div class="position-relative">
              <input type="password" name="password_confirmation" id="confirmPassword"
                     class="form-control pe-5" placeholder="Confirm your password" required>
              <button type="button" class="pw-toggle" id="toggleConfirmPassword" aria-label="Show password">
                <i class="fas fa-eye"></i>
              </button>
            </div>
          </div>

          <div class="col-md-6">
            <label class="form-label">Status</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-toggle-on"></i></span>
              <select name="status" class="form-select" required>
                <option value="active" {{ old('status','active')==='active'?'selected':'' }}>Active</option>
                <option value="inactive" {{ old('status')==='inactive'?'selected':'' }}>Inactive</option>
              </select>
            </div>
          </div>
        </div>

        <div class="d-flex gap-2 mt-4">
          <button class="btn btn-primary px-4" type="submit">
            <i class="fas fa-floppy-disk me-1"></i> Save Registration
          </button>
          <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    var togglePassword = document.getElementById('togglePassword');
    var password = document.getElementById('password');
    var toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    var confirmPassword = document.getElementById('confirmPassword');

    togglePassword.addEventListener('click', function () {
      var type = password.type === 'password' ? 'text' : 'password';
      password.type = type;
      this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
    });

    toggleConfirmPassword.addEventListener('click', function () {
      var type = confirmPassword.type === 'password' ? 'text' : 'password';
      confirmPassword.type = type;
      this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
    });
  });
</script>
@endpush
@endsection
