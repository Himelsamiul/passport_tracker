@extends('backend.master')

@section('page-title', 'Edit Officer')
@section('page-subtitle', 'Update passport officer')

@section('content')
{{-- ============================================================
     ডিজাইন নোট (অফিসার এডিট পেজে যা কাজ হয়েছে):
     • নতুন ডার্ক থিমে ফর্ম কার্ড; পুরনো ভ্যালু ও লজিক অক্ষুণ্ণ।
     ============================================================ --}}
<div class="container-fluid px-0" style="max-width:900px">

  <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div>
      <h3 class="mb-0">Edit Passport Officer</h3>
      <p class="ph-desc" style="color:var(--muted);font-size:.84rem;margin:2px 0 0">Update “{{ $officer->name }}”.</p>
    </div>
    <a href="{{ route('officers.index') }}" class="btn btn-outline-secondary btn-sm">
      <i class="fas fa-arrow-left me-1"></i> Officer List
    </a>
  </div>

  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
  @endif

  <div class="card">
    <div class="card-body p-3 p-md-4">
      <form action="{{ route('officers.update', $officer->id) }}" method="POST">
        @csrf
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Name <span class="text-danger">*</span></label>
            <input type="text" name="name" value="{{ old('name', $officer->name) }}" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" value="{{ old('phone', $officer->phone) }}" class="form-control">
          </div>
          <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" name="email" value="{{ old('email', $officer->email) }}" class="form-control">
          </div>
          <div class="col-md-6">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
              <option value="ACTIVE" @selected(old('status', $officer->status)=='ACTIVE')>ACTIVE</option>
              <option value="INACTIVE" @selected(old('status', $officer->status)=='INACTIVE')>INACTIVE</option>
            </select>
          </div>
          <div class="col-12">
            <label class="form-label">Address</label>
            <textarea name="address" rows="2" class="form-control">{{ old('address', $officer->address) }}</textarea>
          </div>
          <div class="col-12">
            <label class="form-label">Notes</label>
            <textarea name="notes" rows="2" class="form-control">{{ old('notes', $officer->notes) }}</textarea>
          </div>
        </div>

        <div class="d-flex gap-2 mt-4">
          <button type="submit" class="btn btn-primary px-4">
            <i class="fas fa-arrows-rotate me-1"></i> Update Officer
          </button>
          <a href="{{ route('officers.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
