@extends('backend.master')

@section('page-title', 'Processing Details')
@section('page-subtitle', 'Full record of a processing entry')

@section('content')
{{-- ============================================================
     ডিজাইন নোট (প্রসেসিং ডিটেইলস পেজে যা কাজ হয়েছে):
     • রঙিন লাইট কার্ড (নীল/সবুজ/বেগুনি) বাদ — নতুন ডার্ক থিমে ৩টি কার্ড।
     • কার্ড ১: প্রসেসিং তথ্য (স্ট্যাটাস, কর্মী, এজেন্সি, নোট)।
     • কার্ড ২: স্টেকহোল্ডার (এজেন্ট, অফিসার, প্রাপক)।
     • কার্ড ৩: পাসপোর্ট ডিটেইলস + ছবি + টাইমস্ট্যাম্প — সব তথ্য আগের মতোই।
     • kv-grid লেবেল→ভ্যালু গ্রিড ডার্ক স্টাইলে রাখা হয়েছে।
     ============================================================ --}}

<style>
  /* Dark kv-grid styles for this page */
  .kv-grid { display: grid; grid-template-columns: repeat(12, 1fr); gap: 12px; }
  @media (max-width: 991.98px) { .kv-grid { grid-template-columns: repeat(6, 1fr); } }
  @media (max-width: 575.98px) { .kv-grid { grid-template-columns: repeat(2, 1fr); } }

  .kv-item {
    grid-column: span 3;
    background: var(--card-2);
    border: 1px solid var(--line-soft);
    border-radius: 12px;
    padding: 16px 14px;
    min-height: 78px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    transition: border-color .2s ease, background .2s ease;
  }
  .kv-item:hover { border-color: var(--line-strong); background: var(--card-3); }
  .span-4 { grid-column: span 4; }
  .span-6 { grid-column: span 6; }
  .span-12 { grid-column: span 12; }
  @media (max-width: 575.98px) {
    .kv-item, .span-4, .span-6 { grid-column: span 2; }
    .span-12 { grid-column: span 2; }
  }

  .kv-label {
    font-size: 0.7rem;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: 0.07em;
    margin-bottom: 6px;
    font-weight: 650;
    display: flex;
    align-items: center;
    gap: 7px;
  }
  .kv-label i { color: var(--accent-2); font-size: .78rem; }
  .kv-value { font-weight: 600; color: var(--text); font-size: 0.94rem; line-height: 1.45; }
</style>

<div class="container-fluid px-0">

  {{-- Header --}}
  <div class="page-head d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div>
      <h3>Processing Details</h3>
      <p class="ph-desc">
        {{ $processing->passport->passport_number ?? '—' }} — {{ $processing->passport->applicant_name ?? '—' }}
      </p>
    </div>
    <div class="d-flex gap-2">
      <a href="{{ route('processings.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Back
      </a>
      <a href="{{ route('processings.edit', $processing->id) }}" class="btn btn-warning btn-sm">
        <i class="fas fa-pen me-1"></i> Edit
      </a>
    </div>
  </div>

  {{-- CARD 1: Processing --}}
  <div class="card mb-3">
    <div class="card-header"><i class="fas fa-gears me-2" style="color:var(--accent-2)"></i>Processing Information</div>
    <div class="card-body">
      <div class="kv-grid">
        @php
          $statusClass = [
            'DONE'        => 'bg-success',
            'IN_PROGRESS' => 'bg-warning',
            'PENDING'     => 'bg-secondary',
            'REJECTED'    => 'bg-danger',
          ][$processing->status] ?? 'bg-secondary';
        @endphp
        <div class="kv-item">
          <div class="kv-label"><i class="fas fa-tag"></i> Status</div>
          <div class="kv-value"><span class="badge {{ $statusClass }}">{{ $processing->status }}</span></div>
        </div>

        <div class="kv-item">
          <div class="kv-label"><i class="fas fa-user"></i> Employee (Picked Up)</div>
          <div class="kv-value">{{ $processing->employee->name ?? '—' }}</div>
        </div>

        <div class="kv-item">
          <div class="kv-label"><i class="fas fa-building"></i> Agency</div>
          <div class="kv-value">{{ $processing->agency->name ?? 'Unassigned' }}</div>
        </div>

        <div class="kv-item">
          <div class="kv-label"><i class="fas fa-calendar-plus"></i> Created</div>
          <div class="kv-value">{{ $processing->created_at->format('d M Y, h:i A') }}</div>
        </div>

        <div class="kv-item span-12">
          <div class="kv-label"><i class="fas fa-note-sticky"></i> Notes</div>
          <div class="kv-value">{{ $processing->notes ?: '—' }}</div>
        </div>
      </div>
    </div>
  </div>

  {{-- CARD 2: Stakeholders --}}
  <div class="card mb-3">
    <div class="card-header"><i class="fas fa-users me-2" style="color:var(--accent-2)"></i>Stakeholders</div>
    <div class="card-body">
      <div class="kv-grid">
        <div class="kv-item span-4">
          <div class="kv-label"><i class="fas fa-user-tie"></i> Agent</div>
          <div class="kv-value">{{ optional($processing->passport->agent)->name ?: '—' }}</div>
        </div>

        <div class="kv-item span-4">
          <div class="kv-label"><i class="fas fa-passport"></i> Passport Officer</div>
          <div class="kv-value">{{ optional($processing->passport->passportOfficer)->name ?: '—' }}</div>
        </div>

        <div class="kv-item span-4">
          <div class="kv-label"><i class="fas fa-user-check"></i> Received By (Original)</div>
          <div class="kv-value">{{ optional($processing->passport->employee)->name ?: '—' }}</div>
        </div>
      </div>
    </div>
  </div>

  {{-- CARD 3: Passport details --}}
  <div class="card mb-3">
    <div class="card-header"><i class="fas fa-id-card me-2" style="color:var(--accent-2)"></i>Passport Details</div>
    <div class="card-body">
      <div class="kv-grid">
        <div class="kv-item span-4">
          <div class="kv-label"><i class="fas fa-user"></i> Applicant Name</div>
          <div class="kv-value">{{ $processing->passport->applicant_name }}</div>
        </div>
        <div class="kv-item span-4">
          <div class="kv-label"><i class="fas fa-id-card"></i> Passport Number</div>
          <div class="kv-value">{{ $processing->passport->passport_number }}</div>
        </div>
        <div class="kv-item span-4">
          <div class="kv-label"><i class="fas fa-flag"></i> Nationality</div>
          <div class="kv-value">{{ $processing->passport->nationality ?: '—' }}</div>
        </div>

        <div class="kv-item span-6">
          <div class="kv-label"><i class="fas fa-phone"></i> Phone</div>
          <div class="kv-value">{{ $processing->passport->phone ?: '—' }}</div>
        </div>
        <div class="kv-item span-6">
          <div class="kv-label"><i class="fas fa-location-dot"></i> Address</div>
          <div class="kv-value">{{ $processing->passport->address ?: '—' }}</div>
        </div>

        <div class="kv-item span-4">
          <div class="kv-label"><i class="fas fa-cake-candles"></i> Date of Birth</div>
          <div class="kv-value">{{ optional($processing->passport->date_of_birth)->format('d M Y') ?: '—' }}</div>
        </div>
        <div class="kv-item span-4">
          <div class="kv-label"><i class="fas fa-calendar-day"></i> Issue Date</div>
          <div class="kv-value">{{ optional($processing->passport->issue_date)->format('d M Y') ?: '—' }}</div>
        </div>
        <div class="kv-item span-4">
          <div class="kv-label"><i class="fas fa-calendar-xmark"></i> Expiry Date</div>
          <div class="kv-value">{{ optional($processing->passport->expiry_date)->format('d M Y') ?: '—' }}</div>
        </div>

        <div class="kv-item span-6">
          <div class="kv-label"><i class="fas fa-id-badge"></i> NID Number</div>
          <div class="kv-value">{{ $processing->passport->nid_number ?: '—' }}</div>
        </div>
        <div class="kv-item span-6">
          <div class="kv-label"><i class="fas fa-circle-info"></i> Passport Status</div>
          <div class="kv-value">
            @if($processing->passport->status)
              <span class="badge bg-light">{{ $processing->passport->status }}</span>
            @else
              —
            @endif
          </div>
        </div>

        <div class="kv-item span-12">
          <div class="kv-label"><i class="fas fa-note-sticky"></i> Passport Notes</div>
          <div class="kv-value">{{ $processing->passport->notes ?: '—' }}</div>
        </div>

        @if($processing->passport->passport_picture)
          <div class="span-12 text-center">
            <div class="kv-label justify-content-center"><i class="fas fa-image"></i> Passport Picture</div>
            <img src="{{ asset('storage/'.$processing->passport->passport_picture) }}"
                 class="img-fluid rounded-3"
                 style="max-height:280px;border:1px solid var(--line);"
                 alt="Passport Picture">
          </div>
        @endif

        <div class="kv-item span-6">
          <div class="kv-label"><i class="fas fa-calendar-plus"></i> Created At</div>
          <div class="kv-value">{{ $processing->passport->created_at->format('d M Y, h:i A') }}</div>
        </div>
        <div class="kv-item span-6">
          <div class="kv-label"><i class="fas fa-calendar-check"></i> Updated At</div>
          <div class="kv-value">{{ $processing->passport->updated_at->format('d M Y, h:i A') }}</div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
