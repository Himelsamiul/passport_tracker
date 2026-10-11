@extends('backend.master')

@section('page-title', 'New Role')
@section('page-subtitle', 'Define a permission set')

@section('content')
{{-- ============================================================
     ডিজাইন নোট (রোল তৈরির পেজে যা কাজ হয়েছে):
     • নাম + বর্ণনা + পারমিশন ম্যাট্রিক্স (মডিউল × View/Create/Edit/Delete)।
     • Select all / Select none / View only শর্টকাট বাটন।
     • পারমিশন সার্ভারে Access::MODULES দিয়ে ভ্যালিডেট হয়।
     ============================================================ --}}
<div class="container-fluid px-0" style="max-width:980px">

  <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div>
      <h3 class="mb-0">New Role</h3>
      <p class="ph-desc" style="color:var(--muted);font-size:.84rem;margin:2px 0 0">Tick the abilities this role grants.</p>
    </div>
    <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary btn-sm">
      <i class="fas fa-arrow-left me-1"></i> Back to Roles
    </a>
  </div>

  <form action="{{ route('admin.roles.store') }}" method="POST">
    @csrf

    <div class="card mb-3">
      <div class="card-body p-3 p-md-4">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Role Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" placeholder="e.g. Operations" required value="{{ old('name') }}">
          </div>
          <div class="col-md-6">
            <label class="form-label">Description</label>
            <input type="text" name="description" class="form-control" placeholder="Short description (optional)" value="{{ old('description') }}">
          </div>
        </div>
      </div>
    </div>

    @include('backend.pages.admin.roles._permission_matrix')

    <div class="d-flex gap-2 mt-3">
      <button type="submit" class="btn btn-primary px-4"><i class="fas fa-check me-1"></i> Create Role</button>
      <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
  </form>
</div>

@push('scripts')
<script>
  // Matrix shortcuts
  document.getElementById('permAll').addEventListener('click', function () { setAll(true); });
  document.getElementById('permNone').addEventListener('click', function () { setAll(false); });
  document.getElementById('permViewOnly').addEventListener('click', function () {
    document.querySelectorAll('.perm-check').forEach(function (c) {
      c.checked = c.value.endsWith('.view');
    });
  });

  function setAll(state) {
    document.querySelectorAll('.perm-check').forEach(function (c) { c.checked = state; });
  }
</script>
@endpush
@endsection