@extends('backend.master')

@section('page-title', 'Passport Details')
@section('page-subtitle', 'Full record of a single passport')

@section('content')
{{-- ============================================================
     ডিজাইন নোট (পাসপোর্ট ডিটেইলস পেজে যা কাজ হয়েছে):
     • নতুন ডার্ক থিমে দুই কলামের লেআউট — বামে আবেদনকারী/ডকুমেন্ট তথ্য, ডানে ওয়ার্কফ্লো।
     • উপরে স্ট্যাটাস ব্যাজসহ পেজ হেডার + Edit / Back বাটন।
     • প্রতিটি তথ্য "label → value" সুন্দর গ্রিডে দেখানো হয়েছে।
     • প্রসেসিং/কলেকশনের সর্বশেষ রেকর্ড আগের লজিকেই দেখায়।
     ============================================================ --}}
<div class="container-fluid px-0">

  @php
    // Latest processing
    $processing = \App\Models\PassportProcessing::with('agency')
                    ->where('passport_id', $passport->id)
                    ->latest('id')
                    ->first();

    // Latest collection
    $collection = \App\Models\PassportCollection::where('passport_id', $passport->id)
                    ->latest('id')
                    ->first();

    // Main passport status
    $statusText  = $passport->status ?? 'RECEIVED_FROM_AGENT';
    $statusClass = match($passport->status) {
        'RECEIVED_FROM_AGENT'   => 'bg-info',
        'COLLECTED_FROM_AGENCY' => 'bg-success',
        'IN_PROCESS'            => 'bg-warning',
        default                 => 'bg-secondary'
    };

    // Processing status
    $procStatus = $processing?->status;
    $procBadge  = match($procStatus) {
        'PENDING'      => 'bg-secondary',
        'IN_PROGRESS'  => 'bg-warning',
        'DONE'         => 'bg-primary',
        'REJECTED'     => 'bg-danger',
        default        => 'bg-light'
    };
    $procLabel = $procStatus
        ? (($procStatus === 'IN_PROGRESS') ? 'On Going' : ucwords(strtolower($procStatus)))
        : '—';

    // Collected agency (if any)
    $collectedAgency = $collection && $processing?->agency?->name
        ? 'Collected from '.$processing->agency->name
        : null;
  @endphp

  {{-- Header --}}
  <div class="page-head d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div>
      <div class="d-flex align-items-center gap-2 flex-wrap">
        <h3 class="mb-0">{{ $passport->applicant_name }}</h3>
        <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
        <span class="badge {{ $procBadge }}">{{ $procLabel }}</span>
      </div>
      <p class="ph-desc">Passport No: <span class="fw-semibold" style="color:var(--text-soft)">{{ $passport->passport_number }}</span></p>
    </div>
    <div class="d-flex gap-2">
      <a href="{{ route('passports.edit', $passport->id) }}" class="btn btn-warning btn-sm">
        <i class="fas fa-pen me-1"></i> Edit
      </a>
      <a href="{{ route('passports.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Back to List
      </a>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="row g-3">
    <!-- Left: Applicant & Document Info -->
    <div class="col-lg-8">
      <div class="card h-100">
        <div class="card-header"><i class="fas fa-address-card me-2" style="color:var(--accent-2)"></i>Applicant &amp; Document</div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="detail-row"><span class="d-label">Applicant Name</span><span class="d-value">{{ $passport->applicant_name }}</span></div>
              <div class="detail-row"><span class="d-label">Passport Number</span><span class="d-value">{{ $passport->passport_number }}</span></div>
              <div class="detail-row"><span class="d-label">Phone</span><span class="d-value">{{ $passport->phone ?? '—' }}</span></div>
              <div class="detail-row"><span class="d-label">NID Number</span><span class="d-value">{{ $passport->nid_number ?? '—' }}</span></div>
              <div class="detail-row"><span class="d-label">Nationality</span><span class="d-value">{{ $passport->nationality ?? '—' }}</span></div>
              <div class="detail-row"><span class="d-label">Address</span><span class="d-value">{{ $passport->address ?? '—' }}</span></div>
            </div>
            <div class="col-md-6">
              <div class="detail-row"><span class="d-label">Date of Birth</span><span class="d-value">{{ optional($passport->date_of_birth)->format('d M Y') ?? '—' }}</span></div>
              <div class="detail-row"><span class="d-label">Issue Date</span><span class="d-value">{{ optional($passport->issue_date)->format('d M Y') ?? '—' }}</span></div>
              <div class="detail-row"><span class="d-label">Expiry Date</span><span class="d-value">{{ optional($passport->expiry_date)->format('d M Y') ?? '—' }}</span></div>
              <div class="detail-row"><span class="d-label">Main Status</span><span class="d-value"><span class="badge {{ $statusClass }}">{{ $statusText }}</span></span></div>
              <div class="detail-row"><span class="d-label">Processing Status</span><span class="d-value"><span class="badge {{ $procBadge }}">{{ $procLabel }}</span></span></div>
              @if($collectedAgency)
                <div class="detail-row"><span class="d-label">Collection</span><span class="d-value"><span class="badge bg-success">{{ $collectedAgency }}</span></span></div>
              @endif
              <div class="detail-row"><span class="d-label">Created</span><span class="d-value">{{ optional($passport->created_at)->timezone(config('app.timezone','Asia/Dhaka'))->format('d M Y, h:i A') }}</span></div>
            </div>
          </div>

          @if($passport->notes)
            <div class="alert alert-info mt-3 mb-0">
              <strong>Notes:</strong> {{ $passport->notes }}
            </div>
          @endif
        </div>
      </div>
    </div>

    <!-- Right: Workflow -->
    <div class="col-lg-4">
      <div class="card h-100">
        <div class="card-header"><i class="fas fa-diagram-project me-2" style="color:var(--accent-2)"></i>Workflow</div>
        <div class="card-body">
          <div class="detail-row"><span class="d-label">Agent</span><span class="d-value">{{ $passport->agent?->name ?? '—' }}</span></div>
          <div class="detail-row"><span class="d-label">Officer</span><span class="d-value">{{ $passport->passportOfficer?->name ?? '—' }}</span></div>
          <div class="detail-row"><span class="d-label">Employee (Received By)</span><span class="d-value">{{ $passport->employee?->name ?? '—' }}</span></div>

          @if($processing)
            <div class="subpanel mt-3">
              <div class="fw-semibold mb-1" style="font-size:.85rem"><i class="fas fa-gears me-2" style="color:var(--accent-2)"></i>Latest Processing</div>
              <div class="text-muted" style="font-size:.8rem">
                Agency: <span style="color:var(--text-soft)">{{ $processing->agency?->name ?? '—' }}</span><br>
                Status: <span class="badge {{ $procBadge }}">{{ $procLabel }}</span>
              </div>
            </div>
          @endif

          @if($collection)
            <div class="subpanel mt-2">
              <div class="fw-semibold mb-1" style="font-size:.85rem"><i class="fas fa-box-open me-2" style="color:var(--green)"></i>Collection</div>
              <div class="text-muted" style="font-size:.8rem">
                Collected by <span style="color:var(--text-soft)">{{ $collection->employee?->name ?? '—' }}</span>
                @if($collection->collected_at)
                  · {{ $collection->collected_at->format('d M Y, h:i A') }}
                @endif
              </div>
            </div>
          @endif
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
  .detail-row .d-label { color: var(--muted); font-size: .8rem; white-space: nowrap; }
  .detail-row .d-value { color: var(--text); font-size: .875rem; font-weight: 550; text-align: right; }
</style>
@endpush
@endsection
