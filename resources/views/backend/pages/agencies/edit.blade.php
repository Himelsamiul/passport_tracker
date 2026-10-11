@extends('backend.master')

@section('page-title', 'Edit Agency')
@section('page-subtitle', 'Update partner agency')

@section('content')
{{-- ============================================================
     ডিজাইন নোট (এজেন্সি এডিট পেজে যা কাজ হয়েছে):
     • নতুন ডার্ক থিমে ফর্ম কার্ড; পুরনো ভ্যালু ও লজিক অক্ষুণ্ণ।
     ============================================================ --}}
<div class="container-fluid px-0" style="max-width:900px">

  <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div>
      <h3 class="mb-0">Edit Agency</h3>
      <p class="ph-desc" style="color:var(--muted);font-size:.84rem;margin:2px 0 0">Update “{{ $agency->name }}”.</p>
    </div>
    <a href="{{ route('agencies.index') }}" class="btn btn-outline-secondary btn-sm">
      <i class="fas fa-arrow-left me-1"></i> Agencies List
    </a>
  </div>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="card">
    <div class="card-body p-3 p-md-4">
      <form action="{{ route('agencies.update', $agency->id) }}" method="POST">
        @csrf
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Agency Name <span class="text-danger">*</span></label>
            <input type="text" name="name" value="{{ old('name', $agency->name) }}" class="form-control" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Contact Person</label>
            <input type="text" name="contact_person" value="{{ old('contact_person', $agency->contact_person) }}" class="form-control">
          </div>

          <div class="col-md-6">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" value="{{ old('phone', $agency->phone) }}" class="form-control">
          </div>

          <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" name="email" value="{{ old('email', $agency->email) }}" class="form-control">
          </div>

          <div class="col-md-6">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
              <option value="ACTIVE" @selected(old('status', $agency->status) == 'ACTIVE')>ACTIVE</option>
              <option value="INACTIVE" @selected(old('status', $agency->status) == 'INACTIVE')>INACTIVE</option>
            </select>
          </div>

          <div class="col-md-6">
            <label class="form-label">Notes</label>
            <textarea name="notes" rows="2" class="form-control">{{ old('notes', $agency->notes) }}</textarea>
          </div>

          <div class="col-md-12">
            <label class="form-label">Address</label>
            <textarea name="address" rows="2" class="form-control">{{ old('address', $agency->address) }}</textarea>
          </div>
        </div>

        <div class="d-flex gap-2 mt-4">
          <button type="submit" class="btn btn-primary px-4">
            <i class="fas fa-arrows-rotate me-1"></i> Update Agency
          </button>
          <a href="{{ route('agencies.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
