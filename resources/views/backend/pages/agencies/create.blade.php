@extends('backend.master')

@section('page-title', 'Add Agency')
@section('page-subtitle', 'Register a partner agency')

@section('content')
{{-- ============================================================
     ডিজাইন নোট (এজেন্সি তৈরির পেজে যা কাজ হয়েছে):
     • নতুন ডার্ক থিমে ফর্ম কার্ড; ক্যাটাগরি সিলেক্টের পাশে "+ New" শর্টকাট আগের মতো।
     • সব ফিল্ড ও ভ্যালিডেশন অপরিবর্তিত।
     ============================================================ --}}
<div class="container-fluid px-0" style="max-width:900px">

  <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div>
      <h3 class="mb-0">Add New Agency</h3>
      <p class="ph-desc" style="color:var(--muted);font-size:.84rem;margin:2px 0 0">Register a partner agency that processes passports abroad.</p>
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
      <form action="{{ route('agencies.store') }}" method="POST">
        @csrf
        <div class="row g-3">

          {{-- Category --}}
          <div class="col-md-6">
            <label class="form-label">Category</label>
            <div class="d-flex gap-2">
              <select name="category_id" class="form-select">
                <option value="">-- Select Category --</option>
                @foreach ($categories as $cat)
                  <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>
                    {{ $cat->name }}
                  </option>
                @endforeach
              </select>
              <a href="{{ route('categories.create') }}" class="btn btn-outline-secondary text-nowrap">
                <i class="fas fa-plus me-1"></i> New
              </a>
            </div>
            @error('category_id') <small class="text-danger">{{ $message }}</small> @enderror
          </div>

          <div class="col-md-6">
            <label class="form-label">Agency Name <span class="text-danger">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Contact Person</label>
            <input type="text" name="contact_person" value="{{ old('contact_person') }}" class="form-control">
          </div>

          <div class="col-md-6">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" value="{{ old('phone') }}" class="form-control">
          </div>

          <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="form-control">
          </div>

          <div class="col-md-6">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
              <option value="ACTIVE" @selected(old('status') == 'ACTIVE')>ACTIVE</option>
              <option value="INACTIVE" @selected(old('status') == 'INACTIVE')>INACTIVE</option>
            </select>
          </div>

          <div class="col-md-12">
            <label class="form-label">Address</label>
            <textarea name="address" rows="2" class="form-control">{{ old('address') }}</textarea>
          </div>

          <div class="col-md-12">
            <label class="form-label">Notes</label>
            <textarea name="notes" rows="2" class="form-control">{{ old('notes') }}</textarea>
          </div>
        </div>

        <div class="d-flex gap-2 mt-4">
          <button type="submit" class="btn btn-primary px-4">
            <i class="fas fa-check me-1"></i> Save Agency
          </button>
          <a href="{{ route('agencies.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
