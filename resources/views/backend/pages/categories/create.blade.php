@extends('backend.master')

@section('page-title', 'Add Category')
@section('page-subtitle', 'Create a new working category')

@section('content')
{{-- ============================================================
     ডিজাইন নোট (ক্যাটাগরি তৈরির পেজে যা কাজ হয়েছে):
     • নতুন ডার্ক থিমে ফর্ম কার্ড; নাম + স্ট্যাটাস ফিল্ড, লজিক আগের মতো।
     ============================================================ --}}
<div class="container-fluid px-0" style="max-width:640px">

  <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div>
      <h3 class="mb-0">Add Category</h3>
      <p class="ph-desc" style="color:var(--muted);font-size:.84rem;margin:2px 0 0">Create a working category for agencies.</p>
    </div>
    <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary btn-sm">
      <i class="fas fa-arrow-left me-1"></i> Category List
    </a>
  </div>

  <div class="card">
    <div class="card-body p-3 p-md-4">
      <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        <div class="mb-3">
          <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
          <input type="text" name="name" id="name" class="form-control" placeholder="e.g. Work Permit" value="{{ old('name') }}" required>
        </div>

        <div class="mb-3">
          <label for="status" class="form-label">Status</label>
          <select name="status" id="status" class="form-select" required>
            <option value="active" @selected(old('status', 'active') === 'active')>Active</option>
            <option value="inactive" @selected(old('status', 'active') === 'inactive')>Inactive</option>
          </select>
        </div>

        <div class="d-flex gap-2 mt-4">
          <button type="submit" class="btn btn-primary px-4">
            <i class="fas fa-check me-1"></i> Save Category
          </button>
          <a href="{{ route('categories.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
