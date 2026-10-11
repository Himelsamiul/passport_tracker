@extends('backend.master')

@section('page-title', 'Edit Processing')
@section('page-subtitle', 'Update a processing entry')

@section('content')
{{-- ============================================================
     ডিজাইন নোট (প্রসেসিং এডিট পেজে যা কাজ হয়েছে):
     • নতুন ডার্ক থিমে ফর্ম কার্ড; উপরে Back/View বাটন ও পাসপোর্ট রেফারেন্স।
     • সব ফিল্ড ও লজিক (employee/agency/status/notes) অপরিবর্তিত।
     ============================================================ --}}
<div class="container-fluid px-0" style="max-width:1000px">

  <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div>
      <h3 class="mb-0">Edit Processing</h3>
      <p class="ph-desc" style="color:var(--muted);font-size:.84rem;margin:2px 0 0">
        Processing for <span class="fw-semibold" style="color:var(--text-soft)">{{ $processing->passport->passport_number ?? '—' }}</span>
        — {{ $processing->passport->applicant_name ?? '—' }}
      </p>
    </div>
    <div class="d-flex gap-2">
      <a href="{{ route('processings.show', $processing->id) }}" class="btn btn-info btn-sm"><i class="fas fa-eye me-1"></i> View</a>
      <a href="{{ route('processings.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> Back</a>
    </div>
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
      <form method="POST" action="{{ route('processings.update', $processing->id) }}">
        @csrf @method('PUT')
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Employee (Picked Up By) <span class="text-danger">*</span></label>
            <select name="employee_id" class="form-select" required>
              @foreach($employees as $e)
                <option value="{{ $e->id }}" @selected(old('employee_id', $processing->employee_id) == $e->id)>{{ $e->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-4">
            <label class="form-label">Agency</label>
            <select name="agency_id" class="form-select">
              <option value="">Unassigned</option>
              @foreach($agencies as $a)
                <option value="{{ $a->id }}" @selected(old('agency_id', $processing->agency_id) == $a->id)>{{ $a->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-4">
            <label class="form-label">Status <span class="text-danger">*</span></label>
            <select name="status" class="form-select" required>
              @foreach(['PENDING','IN_PROGRESS','DONE','REJECTED'] as $opt)
                <option value="{{ $opt }}" @selected(old('status', $processing->status) === $opt)>{{ $opt }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-12">
            <label class="form-label">Notes</label>
            <textarea name="notes" class="form-control" rows="3">{{ old('notes', $processing->notes) }}</textarea>
          </div>
        </div>

        <div class="d-flex gap-2 mt-4">
          <button class="btn btn-primary px-4" type="submit"><i class="fas fa-arrows-rotate me-1"></i> Update</button>
          <a href="{{ route('processings.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
