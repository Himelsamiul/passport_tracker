@extends('backend.master')

@section('page-title', 'Edit Role')
@section('page-subtitle', 'Update a permission set')

@section('content')
{{-- ============================================================
     ডিজাইন নোট (রোল এডিট পেজে যা কাজ হয়েছে):
     • তৈরির পেজের মতোই — নাম/বর্ণনা + পারমিশন ম্যাট্রিক্স, আগের চেক অবস্থা লোড হয়।
     • সেভ করলে সাথে সাথে সব সংশ্লিষ্ট ইউজারের অ্যাকসেস পরিবর্তন হয় (Access::can fresh read করে)।
     ============================================================ --}}
<div class="container-fluid px-0" style="max-width:980px">

  <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div>
      <h3 class="mb-0">Edit Role — {{ $role->name }}</h3>
      <p class="ph-desc" style="color:var(--muted);font-size:.84rem;margin:2px 0 0">Changes apply to all users with this role immediately.</p>
    </div>
    <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary btn-sm">
      <i class="fas fa-arrow-left me-1"></i> Back to Roles
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

  <form action="{{ route('admin.roles.update', $role) }}" method="POST">
    @csrf @method('PUT')

    <div class="card mb-3">
      <div class="card-body p-3 p-md-4">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Role Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" required value="{{ old('name', $role->name) }}">
          </div>
          <div class="col-md-6">
            <label class="form-label">Description</label>
            <input type="text" name="description" class="form-control" value="{{ old('description', $role->description) }}">
          </div>
        </div>
      </div>
    </div>

    @include('backend.pages.admin.roles._permission_matrix')

    <div class="d-flex gap-2 mt-3">
      <button type="submit" class="btn btn-primary px-4"><i class="fas fa-arrows-rotate me-1"></i> Update Role</button>
      <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
  </form>
</div>

@push('scripts')
<script>
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