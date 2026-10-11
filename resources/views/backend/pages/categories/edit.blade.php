@extends('backend.master')

@section('page-title', 'Edit Category')
@section('page-subtitle', 'Update working category')

@section('content')
{{-- ============================================================
     ডিজাইন নোট (ক্যাটাগরি এডিট পেজে যা কাজ হয়েছে):
     • নতুন ডার্ক থিমে ফর্ম কার্ড; পুরনো ভ্যালু ও লজিক অক্ষুণ্ণ।
     ============================================================ --}}
<div class="container-fluid px-0" style="max-width:640px">

  <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div>
      <h3 class="mb-0">Edit Category</h3>
      <p class="ph-desc" style="color:var(--muted);font-size:.84rem;margin:2px 0 0">Update “{{ $category->name }}”.</p>
    </div>
    <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary btn-sm">
      <i class="fas fa-arrow-left me-1"></i> Category List
    </a>
  </div>

  <div class="card">
    <div class="card-body p-3 p-md-4">
      <form action="{{ route('categories.update', $category->id) }}" method="POST">
        @csrf
        <div class="mb-3">
          <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
          <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" class="form-control" required>
        </div>

        <div class="mb-3">
          <label for="status" class="form-label">Status</label>
          <select name="status" id="status" class="form-select" required>
            <option value="active" {{ $category->status == 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ $category->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
          </select>
        </div>

        <div class="d-flex gap-2 mt-4">
          <button type="submit" class="btn btn-primary px-4">
            <i class="fas fa-arrows-rotate me-1"></i> Update Category
          </button>
          <a href="{{ route('categories.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
