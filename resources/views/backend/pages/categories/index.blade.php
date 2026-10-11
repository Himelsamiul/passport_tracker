@extends('backend.master')

@section('page-title', 'Working Categories')
@section('page-subtitle', 'Service categories used for agencies')

@section('content')
{{-- ============================================================
     ডিজাইন নোট (ক্যাটাগরি লিস্টে যা কাজ হয়েছে):
     • নতুন ডার্ক থিমে পেজ হেডার + "Add Category" বাটন।
     • টেবিল কার্ডের ভেতরে; স্ট্যাটাস সফট ব্যাজে (Active=সবুজ, Inactive=ধূসর)।
     • "used/locked" চেক লজিক আগের মতোই; খালি অবস্থায় empty-state।
     ============================================================ --}}
<div class="container-fluid px-0">

  {{-- Page header --}}
  <div class="page-head d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div>
      <h3>Categories</h3>
      <p class="ph-desc">Working categories assigned to agencies.</p>
    </div>
    @if (can_do('categories.create'))
      <a href="{{ route('categories.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Add Category
      </a>
    @endif
  </div>

  {{-- Flash --}}
  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
  @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div>   @endif

  <div class="card p-2 p-md-3">
    <div class="table-responsive">
      <table class="table table-bordered align-middle text-center">
        <thead>
          <tr>
            <th style="width:56px">#</th>
            <th>Name</th>
            <th>Status</th>
            <th style="width:220px">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($categories as $key => $category)
            @php
              // Check if category is linked to any agency
              $isUsed = \App\Models\Agency::where('category_id', $category->id)->exists();
            @endphp

            <tr class="{{ $isUsed ? 'lock-row' : '' }}">
              <td>{{ $key + 1 }}</td>
              <td class="text-start fw-semibold">{{ $category->name }}</td>
              <td>
                <span class="badge bg-{{ $category->status == 'active' ? 'success' : 'secondary' }}">
                  {{ ucfirst($category->status) }}
                </span>
              </td>
              <td>
                <div class="d-flex justify-content-center flex-wrap gap-1">
                  @if (can_do('categories.edit'))
                    <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-warning btn-sm">
                      <i class="fas fa-pen"></i> Edit
                    </a>
                  @endif

                  @if($isUsed || !can_do('categories.delete'))
                    <button type="button"
                            class="btn btn-secondary btn-sm"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="{{ $isUsed ? 'You can’t delete this category — it’s already linked with one or more agencies.' : 'You do not have delete permission.' }}">
                      <i class="fas fa-lock"></i> Locked
                    </button>
                  @else
                    <a href="{{ route('categories.delete', $category->id) }}"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Are you sure you want to delete this category?')">
                      <i class="fas fa-trash-can"></i> Delete
                    </a>
                  @endif
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4">
                <div class="empty-state">
                  <div class="es-icon"><i class="fas fa-layer-group"></i></div>
                  <div class="fw-semibold mb-1" style="color:var(--text-soft)">No categories found</div>
                  <div>Add your first working category.</div>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
