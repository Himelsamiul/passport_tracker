@extends('backend.master')

@section('page-title', 'Edit Admin User')
@section('page-subtitle', 'Update a panel login')

@section('content')
{{-- ============================================================
     ডিজাইন নোট (অ্যাডমিন ইউজার এডিট পেজে যা কাজ হয়েছে):
     • নাম/ইমেইল/রোল বদলানো যায়; পাসওয়ার্ড খালি রাখলে অপরিবর্তিত থাকে।
     • রোল বদলালে সাথে সাথে পরের রিকোয়েস্ট থেকে নতুন পারমিশন কার্যকর হয়।
     ============================================================ --}}
<div class="container-fluid px-0" style="max-width:720px">

  <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div>
      <h3 class="mb-0">Edit Admin User</h3>
      <p class="ph-desc" style="color:var(--muted);font-size:.84rem;margin:2px 0 0">Update {{ $user->name }}.</p>
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
      <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf @method('PUT')

        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" required value="{{ old('name', $user->name) }}">
          </div>
          <div class="col-md-6">
            <label class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" name="email" class="form-control" required value="{{ old('email', $user->email) }}"
                   @if ($user->isMaster()) readonly @endif>
            @if ($user->isMaster())
              <small class="text-muted" style="font-size:.72rem"><i class="fas fa-lock me-1"></i>The master admin email is locked and cannot be changed.</small>
            @endif
          </div>
          <div class="col-md-6">
            <label class="form-label">New Password</label>
            <input type="password" name="password" class="form-control" minlength="6" placeholder="Leave empty to keep current">
          </div>
          <div class="col-md-6">
            <label class="form-label">Role</label>
            @if ($user->isMaster())
              <input type="text" class="form-control" value="Master Admin (locked)" readonly>
            @else
              <select name="role_id" class="form-select">
                <option value="" @selected(old('role_id', $user->role_id) === null || old('role_id') === '')>— No role (Master Admin access) —</option>
                @foreach ($roles as $role)
                  <option value="{{ $role->id }}" @selected(old('role_id', $user->role_id) == $role->id)>{{ $role->name }}</option>
                @endforeach
              </select>
            @endif
          </div>
        </div>

        <div class="d-flex gap-2 mt-4">
          <button type="submit" class="btn btn-primary px-4"><i class="fas fa-arrows-rotate me-1"></i> Update User</button>
          <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
