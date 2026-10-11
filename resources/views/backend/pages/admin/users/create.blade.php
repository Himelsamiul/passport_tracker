@extends('backend.master')

@section('page-title', 'New Admin User')
@section('page-subtitle', 'Create a panel login')

@section('content')
{{-- ============================================================
     ডিজাইন নোট (অ্যাডমিন ইউজার তৈরির পেজে যা কাজ হয়েছে):
     • নাম, ইমেইল, পাসওয়ার্ড ও রোল সিলেক্ট (খালি = Super Admin)।
     • পাসওয়ার্ড bcrypt দিয়ে হ্যাশ হয়।
     ============================================================ --}}
<div class="container-fluid px-0" style="max-width:720px">

  <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div>
      <h3 class="mb-0">New Admin User</h3>
      <p class="ph-desc" style="color:var(--muted);font-size:.84rem;margin:2px 0 0">Create a login and pick its role.</p>
    </div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
      <i class="fas fa-arrow-left me-1"></i> Back to Users
    </a>
  </div>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="card">
    <div class="card-body p-3 p-md-4">
      <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf

        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
          </div>
          <div class="col-md-6">
            <label class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
          </div>
          <div class="col-md-6">
            <label class="form-label">Password <span class="text-danger">*</span></label>
            <input type="password" name="password" class="form-control" required minlength="6">
          </div>
          <div class="col-md-6">
            <label class="form-label">Role</label>
            <select name="role_id" class="form-select">
              <option value="">— No role (Super Admin) —</option>
              @foreach ($roles as $role)
                <option value="{{ $role->id }}" @selected(old('role_id') == $role->id)>{{ $role->name }}</option>
              @endforeach
            </select>
            <small class="text-muted" style="font-size:.74rem">Leave empty only for yourself / trusted owners.</small>
          </div>
        </div>

        <div class="d-flex gap-2 mt-4">
          <button type="submit" class="btn btn-primary px-4"><i class="fas fa-check me-1"></i> Create User</button>
          <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
