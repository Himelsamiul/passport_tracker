@extends('backend.master')

@section('content')
<div class="container-fluid py-4">
  <h3 class="mb-4">Category List</h3>

  <a href="{{ route('categories.create') }}" class="btn btn-primary mb-3">Add New Category</a>

  {{-- Flash Messages --}}
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  <table class="table table-bordered align-middle text-center">
    <thead class="table-dark">
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($categories as $key => $category)
        @php
          // ✅ Check if category is linked to any agency
          $isUsed = \App\Models\Agency::where('category_id', $category->id)->exists();
        @endphp

        <tr @if($isUsed) style="background-color:#f8f9fa;" @endif>
          <td>{{ $key + 1 }}</td>
          <td>{{ $category->name }}</td>
          <td>
            <span class="badge bg-{{ $category->status == 'active' ? 'success' : 'secondary' }}">
              {{ ucfirst($category->status) }}
            </span>
          </td>
          <td>
            <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-warning btn-sm">
              <i class="fas fa-edit"></i> Edit
            </a>

            @if($isUsed)
              {{-- 🔒 Locked button (category used by agency) --}}
              <button type="button"
                      class="btn btn-secondary btn-sm"
                      data-bs-toggle="tooltip"
                      data-bs-placement="top"
                      title="You can’t delete this category — it’s already linked with one or more agencies.">
                <i class="fas fa-lock"></i> Locked
              </button>
            @else
              {{-- 🗑️ Delete button (safe to delete) --}}
              <a href="{{ route('categories.delete', $category->id) }}"
                 class="btn btn-danger btn-sm"
                 onclick="return confirm('Are you sure you want to delete this category?')">
                <i class="fas fa-trash-alt"></i> Delete
              </a>
            @endif
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="4" class="text-center text-muted">No categories found.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>


@push('scripts')
<script>
  // ✅ Enable Bootstrap tooltips
  document.addEventListener('DOMContentLoaded', function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })
  });
</script>
@endpush
@endsection