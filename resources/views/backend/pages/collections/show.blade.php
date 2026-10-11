@extends('backend.master')

@section('page-title', 'Collection Details')
@section('page-subtitle', 'Full record of a passport handover')

@section('content')
{{-- ============================================================
     ডিজাইন নোট (কলেকশন ডিটেইলস পেজে যা কাজ হয়েছে):
     • নতুন ডার্ক থিমে ৩টি কার্ড: স্টেকহোল্ডার, কলেকশন তথ্য, পাসপোর্ট তথ্য।
     • প্রতিটি তথ্য label→value রো স্টাইলে; তিন ধাপের কর্মী রো রঙিন ডট দিয়ে আলাদা করা।
     • সব ডেটা ও লজিক আগের মতোই।
     ============================================================ --}}
<div class="container-fluid px-0">

  <div class="page-head d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div>
      <h3>Passport Collection Details</h3>
      <p class="ph-desc">
        {{ $collection->passport->passport_number ?? '—' }} — {{ $collection->passport->applicant_name ?? '—' }}
      </p>
    </div>
    <a href="{{ route('collections.index') }}" class="btn btn-outline-secondary btn-sm">
      <i class="fas fa-arrow-left me-1"></i> Back to List
    </a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="row g-3">

    {{-- Stakeholder Info --}}
    <div class="col-lg-4">
      <div class="card h-100">
        <div class="card-header"><i class="fas fa-users me-2" style="color:var(--accent-2)"></i>Stakeholder Information</div>
        <div class="card-body">
          <div class="detail-row"><span class="d-label">Agent Name</span><span class="d-value">{{ $collection->passport->agent->name ?? '-' }}</span></div>
          <div class="detail-row"><span class="d-label">Passport Officer</span><span class="d-value">{{ $collection->passport->passportOfficer->name ?? '-' }}</span></div>
          <hr>
          <div class="detail-row"><span class="d-label" style="color:var(--green)">● Received from Agent</span><span class="d-value">{{ $collection->passport->employee->name ?? '-' }}</span></div>
          <div class="detail-row"><span class="d-label" style="color:var(--amber)">● Gave to Process</span><span class="d-value">{{ optional($collection->passport->processings->last()->employee)->name ?? '-' }}</span></div>
          <div class="detail-row"><span class="d-label" style="color:var(--red)">● Collected from Agency</span><span class="d-value">{{ $collection->employee->name ?? '-' }}</span></div>
        </div>
      </div>
    </div>

    {{-- Collection Info --}}
    <div class="col-lg-4">
      <div class="card h-100">
        <div class="card-header"><i class="fas fa-box-open me-2" style="color:var(--accent-2)"></i>Collection Information</div>
        <div class="card-body">
          <div class="detail-row"><span class="d-label">Collected By</span><span class="d-value">{{ $collection->employee->name ?? '-' }}</span></div>
          <div class="detail-row"><span class="d-label">Collected At</span><span class="d-value">{{ optional($collection->collected_at)->format('d M Y, h:i A') }}</span></div>
          <div class="detail-row"><span class="d-label">Notes</span><span class="d-value">{{ $collection->notes ?? '-' }}</span></div>
        </div>
      </div>
    </div>

    {{-- Passport Info --}}
    <div class="col-lg-4">
      <div class="card h-100">
        <div class="card-header"><i class="fas fa-passport me-2" style="color:var(--accent-2)"></i>Passport Information</div>
        <div class="card-body">
          <div class="detail-row"><span class="d-label">Holder Name</span><span class="d-value">{{ $collection->passport->applicant_name ?? '-' }}</span></div>
          <div class="detail-row"><span class="d-label">Passport Number</span><span class="d-value">{{ $collection->passport->passport_number ?? '-' }}</span></div>
          <div class="detail-row"><span class="d-label">Date of Birth</span><span class="d-value">{{ optional($collection->passport->date_of_birth)->format('d M Y') ?? '-' }}</span></div>
          <div class="detail-row"><span class="d-label">Nationality</span><span class="d-value">{{ $collection->passport->nationality ?? '-' }}</span></div>
          <div class="detail-row"><span class="d-label">Phone</span><span class="d-value">{{ $collection->passport->phone ?? '-' }}</span></div>
          <div class="detail-row"><span class="d-label">Address</span><span class="d-value">{{ $collection->passport->address ?? '-' }}</span></div>
          <div class="detail-row"><span class="d-label">Issue Date</span><span class="d-value">{{ optional($collection->passport->issue_date)->format('d M Y') ?? '-' }}</span></div>
          <div class="detail-row"><span class="d-label">Expiry Date</span><span class="d-value">{{ optional($collection->passport->expiry_date)->format('d M Y') ?? '-' }}</span></div>
          <div class="detail-row"><span class="d-label">NID Number</span><span class="d-value">{{ $collection->passport->nid_number ?? '-' }}</span></div>
        </div>
      </div>
    </div>

  </div>
</div>

@push('styles')
<style>
  .detail-row {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    gap: 14px;
    padding: 8px 0;
    border-bottom: 1px dashed var(--line-soft);
  }
  .detail-row:last-child { border-bottom: 0; }
  .detail-row .d-label { color: var(--muted); font-size: .8rem; }
  .detail-row .d-value { color: var(--text); font-size: .875rem; font-weight: 550; text-align: right; }
</style>
@endpush
@endsection
