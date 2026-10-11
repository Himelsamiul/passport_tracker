@extends('backend.master')

@section('page-title', 'New Collection')
@section('page-subtitle', 'Record a passport handover')

@section('content')
{{-- ============================================================
     ডিজাইন নোট (কলেকশন তৈরির পেজে যা কাজ হয়েছে):
     • টিল/লাইট কাস্টম CSS বাদ — নতুন ডার্ক থিম।
     • Select2 সার্চসহ পাসপোর্ট ড্রপডাউন (ডার্ক স্টাইল), ডেটা-JSON থেকে প্রিভিউ আগের মতো।
     • পাসপোর্ট সিলেক্ট করলে ৩টি প্রিভিউ কার্ড দেখায়: প্রসেসিং, স্টেকহোল্ডার, আবেদনকারী ডিটেইলস।
     • এক্সপায়ারি ডেটের কন্ডিশনাল রং (EXPIRED/Expires soon) ডার্ক ব্যাজে দেখায়।
     • সব ফিল্ড-নেম ও লজিক অপরিবর্তিত।
     ============================================================ --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

{{-- Select2 dark overrides --}}
<style>
  .select2-container--default .select2-selection--single {
    background: var(--card-2);
    border: 1px solid var(--line);
    border-radius: 10px;
    height: calc(1.5em + 1.04rem + 2px);
    outline: none;
  }
  .select2-container--default .select2-selection--single .select2-selection__rendered {
    color: var(--text);
    line-height: calc(1.5em + 1rem);
    padding-left: 12px;
    font-size: .875rem;
  }
  .select2-container--default .select2-selection--single .select2-selection__arrow b { border-color: var(--muted) transparent transparent transparent; }
  .select2-container--default.select2-container--focus .select2-selection--single,
  .select2-container--default.select2-container--open .select2-selection--single {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px var(--accent-ring);
  }
  .select2-dropdown {
    background: var(--bg-elevated);
    border: 1px solid var(--line);
    border-radius: 10px;
    box-shadow: var(--shadow-pop);
    overflow: hidden;
  }
  .select2-search--dropdown .select2-search__field {
    background: var(--card-2);
    border: 1px solid var(--line);
    border-radius: 8px;
    color: var(--text);
    margin: 8px;
    width: calc(100% - 16px) !important;
    outline: none;
  }
  .select2-results__option { color: var(--text-soft); font-size: .865rem; padding: 8px 14px; }
  .select2-container--default .select2-results__option--highlighted[aria-selected] { background: var(--accent); color: #fff; }

  .kv-box {
    background: var(--card-2);
    border: 1px solid var(--line-soft);
    border-radius: 10px;
    padding: 0.9rem 1rem;
    height: 100%;
    transition: border-color .2s ease, background .2s ease;
  }
  .kv-box:hover { border-color: var(--line-strong); background: var(--card-3); }
  .kv .label {
    font-size: 0.7rem;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: .06em;
    margin-bottom: 3px;
    font-weight: 650;
  }
  .kv .value { font-weight: 600; font-size: .9rem; color: var(--text); }

  /* Expiry conditional states (dark) */
  .kv-box.bg-danger-subtle { background: var(--red-soft) !important; border-color: rgba(240,97,109,.4) !important; }
  .kv-box.bg-warning-subtle { background: var(--amber-soft) !important; border-color: rgba(245,182,63,.4) !important; }
</style>

<div class="container-fluid px-0" style="max-width:1180px">

  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <div>
      <h3 class="mb-0"><i class="fas fa-handshake me-2" style="color:var(--accent-2)"></i>New Passport Collection</h3>
      <p class="ph-desc" style="color:var(--muted);font-size:.84rem;margin:2px 0 0">Hand a processed passport back to its owner and record it.</p>
    </div>
    <a href="{{ route('collections.index') }}" class="btn btn-outline-secondary btn-sm">
      <i class="fas fa-arrow-left me-1"></i> Back
    </a>
  </div>

  <div class="card">
    <div class="card-body p-3 p-md-4">
      <form action="{{ route('collections.store') }}" method="POST">
        @csrf

        <div class="row g-4">
          {{-- Form Fields --}}
          <div class="col-lg-6">
            <label class="form-label">Select Passport <span class="text-danger">*</span></label>
            <select id="passportSelect" name="passport_id" class="form-select" required>
              <option value="">-- Choose passport ready to collect --</option>
              @foreach($passports as $p)
                <option value="{{ $p->id }}" data-info='{
                    "status": "{{ $p->latest_processing_status ?? 'N/A' }}",
                    "created_at": "{{ $p->created_at }}",
                    "processing_employee": "{{ $p->latestProcessing->employee->name ?? 'N/A' }}",
                    "processing_agency": "{{ $p->latestProcessing->agency->name ?? 'N/A' }}",
                    "agent_name": "{{ $p->agent->name ?? 'N/A' }}",
                    "passport_officer": "{{ $p->passportOfficer->name ?? 'N/A' }}",
                    "received_by_original": "{{ $p->employee->name ?? 'N/A' }}",
                    "applicant_name": "{{ $p->applicant_name }}",
                    "passport_number": "{{ $p->passport_number }}",
                    "nationality": "{{ $p->nationality }}",
                    "phone": "{{ $p->phone }}",
                    "address": "{{ $p->address }}",
                    "date_of_birth": "{{ $p->date_of_birth }}",
                    "issue_date": "{{ $p->issue_date }}",
                    "expiry_date": "{{ $p->expiry_date }}",
                    "nid_number": "{{ $p->nid_number }}"
                }'>
                    {{ $p->passport_number }} — {{ $p->applicant_name }} (Agent: {{ $p->agent->name ?? 'N/A' }})
                </option>
              @endforeach
            </select>
            @error('passport_id') <small class="text-danger">{{ $message }}</small> @enderror
          </div>

          <div class="col-lg-3">
            <label class="form-label">Collected By (Employee) <span class="text-danger">*</span></label>
            <select name="employee_id" class="form-select" required>
              <option value="">-- Select employee --</option>
              @foreach($employees as $e)
                <option value="{{ $e->id }}">{{ $e->name }}</option>
              @endforeach
            </select>
            @error('employee_id') <small class="text-danger">{{ $message }}</small> @enderror
          </div>

          <div class="col-lg-3">
            <label class="form-label">Collection Date &amp; Time <span class="text-danger">*</span></label>
            <input type="datetime-local" name="collected_at" class="form-control" required value="{{ now()->format('Y-m-d\TH:i') }}">
            @error('collected_at') <small class="text-danger">{{ $message }}</small> @enderror
          </div>

          <div class="col-12">
            <label class="form-label">Notes <span class="text-muted">(Optional)</span></label>
            <textarea name="notes" rows="2" class="form-control" placeholder="Any remarks or special instructions..."></textarea>
          </div>
        </div>

        {{-- Auto-preview panel --}}
        <div id="preview" class="mt-4" style="display:none;">
          <div class="section-title mt-2"><i class="fas fa-eye"></i> Passport Preview</div>

          {{-- Processing Information --}}
          <div class="card mb-3">
            <div class="card-header"><i class="fas fa-chart-line me-2" style="color:var(--accent-2)"></i>Processing Information</div>
            <div class="card-body">
              <div class="row g-3">
                <div class="col-md-3">
                  <div class="kv kv-box"><div class="label">Status</div><div class="value" id="pv-status"></div></div>
                </div>
                <div class="col-md-3">
                  <div class="kv kv-box"><div class="label">Employee (Picked Up)</div><div class="value" id="pv-emp"></div></div>
                </div>
                <div class="col-md-3">
                  <div class="kv kv-box"><div class="label">Agency</div><div class="value" id="pv-agency"></div></div>
                </div>
                <div class="col-md-3">
                  <div class="kv kv-box" id="kv-created"><div class="label">Created</div><div class="value" id="pv-created"></div></div>
                </div>
              </div>
            </div>
          </div>

          {{-- Stakeholders --}}
          <div class="card mb-3">
            <div class="card-header"><i class="fas fa-user-tie me-2" style="color:var(--accent-2)"></i>Stakeholders</div>
            <div class="card-body">
              <div class="row g-3">
                <div class="col-md-4">
                  <div class="kv kv-box"><div class="label">Agent</div><div class="value" id="pv-agent"></div></div>
                </div>
                <div class="col-md-4">
                  <div class="kv kv-box"><div class="label">Passport Officer</div><div class="value" id="pv-officer"></div></div>
                </div>
                <div class="col-md-4">
                  <div class="kv kv-box"><div class="label">Received By (Original)</div><div class="value" id="pv-received"></div></div>
                </div>
              </div>
            </div>
          </div>

          {{-- Passport Details --}}
          <div class="card mb-3">
            <div class="card-header"><i class="fas fa-passport me-2" style="color:var(--accent-2)"></i>Applicant Details</div>
            <div class="card-body">
              <div class="row g-3">
                <div class="col-md-3">
                  <div class="kv kv-box"><div class="label">Applicant Name</div><div class="value" id="pv-name"></div></div>
                </div>
                <div class="col-md-3">
                  <div class="kv kv-box"><div class="label">Passport Number</div><div class="value" id="pv-number"></div></div>
                </div>
                <div class="col-md-3">
                  <div class="kv kv-box"><div class="label">Nationality</div><div class="value" id="pv-nationality"></div></div>
                </div>
                <div class="col-md-3">
                  <div class="kv kv-box" id="kv-dob"><div class="label">DOB</div><div class="value" id="pv-dob"></div></div>
                </div>
                <div class="col-md-4">
                  <div class="kv kv-box"><div class="label">Phone</div><div class="value" id="pv-phone"></div></div>
                </div>
                <div class="col-md-8">
                  <div class="kv kv-box"><div class="label">Address</div><div class="value" id="pv-address"></div></div>
                </div>
                <div class="col-md-4">
                  <div class="kv kv-box" id="kv-issue"><div class="label">Issue Date</div><div class="value" id="pv-issue"></div></div>
                </div>
                <div class="col-md-4">
                  <div class="kv kv-box" id="kv-expiry">
                    <div class="label">Expiry Date</div>
                    <div class="value" id="pv-expiry"></div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="kv kv-box"><div class="label">NID</div><div class="value" id="pv-nid"></div></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="mt-4 d-flex gap-2">
          <button type="submit" class="btn btn-success px-4">
            <i class="fas fa-truck-ramp-box me-1"></i> Finalize Collection
          </button>
          <a href="{{ route('collections.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@push('scripts')
<script>
    const pv = (id, v) => document.getElementById(id).innerText = (v && v !== 'null' && v !== 'N/A' && v !== '') ? v : '— N/A —';
    const pvHtml = (id, html) => {
        const el = document.getElementById(id);
        if (el) el.innerHTML = html;
    };

    // Dynamic date formatting with conditional styling for expiry
    const formatDynamicDate = (dateString, type = 'default') => {
        if (!dateString) return { html: '— N/A —', boxClass: '' };

        try {
            const date = new Date(dateString);
            if (isNaN(date.getTime())) return { html: dateString, boxClass: '' };

            const formattedDate = date.toLocaleDateString('en-US', {
                weekday: 'short', year: 'numeric', month: 'short', day: 'numeric'
            });

            let output = `<span class="fw-bold">${formattedDate}</span>`;
            let boxClass = '';
            let icon = `<i class="fas fa-calendar-alt me-1" style="color:var(--accent-2)"></i>`;

            if (type === 'created') {
                const formattedTime = date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
                output = `<span class="fw-bold">${formattedDate}</span><small class="d-block text-muted mt-1">@ ${formattedTime}</small>`;
                icon = `<i class="fas fa-clock me-1" style="color:var(--cyan)"></i>`;
            }

            if (type === 'dob') {
                icon = `<i class="fas fa-cake-candles me-1" style="color:var(--green)"></i>`;
            }

            if (type === 'issue') {
                icon = `<i class="fas fa-file-signature me-1" style="color:var(--cyan)"></i>`;
            }

            if (type === 'expiry') {
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                date.setHours(0, 0, 0, 0);

                const timeDifference = date.getTime() - today.getTime();
                const daysDifference = Math.ceil(timeDifference / (1000 * 60 * 60 * 24));
                const absDays = Math.abs(daysDifference);

                if (daysDifference < 0) {
                    boxClass = 'bg-danger-subtle border-danger';
                    output = `<span class="fw-bold" style="color:var(--red)">${formattedDate}</span><small class="d-block mt-1" style="color:var(--red)">EXPIRED (${absDays} days ago)</small>`;
                    icon = `<i class="fas fa-times-circle me-1" style="color:var(--red)"></i>`;
                } else if (daysDifference <= 180) {
                    boxClass = 'bg-warning-subtle border-warning';
                    output = `<span class="fw-bold" style="color:var(--amber)">${formattedDate}</span><small class="d-block mt-1" style="color:var(--amber)">Expires in ${daysDifference} days</small>`;
                    icon = `<i class="fas fa-triangle-exclamation me-1" style="color:var(--amber)"></i>`;
                } else {
                    icon = `<i class="fas fa-circle-check me-1" style="color:var(--green)"></i>`;
                }
            }

            return { html: `<div class="d-flex align-items-center">${icon}<div>${output}</div></div>`, boxClass: boxClass };

        } catch (e) {
            console.error("Date formatting error:", e);
            return { html: dateString, boxClass: '' };
        }
    };

    document.addEventListener('DOMContentLoaded', () => {
        const sel = document.getElementById('passportSelect');
        const preview = document.getElementById('preview');

        // Searchable dropdown
        $(sel).select2({
            placeholder: "-- Choose passport ready to collect --",
            allowClear: true,
            width: '100%'
        });

        // Handle selection change
        $(sel).on('change', function() {
            const selectedOption = $(this).find(':selected');
            const dataInfoString = selectedOption.attr('data-info');

            if (!dataInfoString) {
                preview.style.display = 'none';
                return;
            }

            let data;
            try {
                data = JSON.parse(dataInfoString);
            } catch (e) {
                console.error("Failed to parse passport data:", e);
                preview.innerHTML = `<div class="alert alert-danger" role="alert">Error loading passport details. Check console for details.</div>`;
                preview.style.display = 'block';
                return;
            }

            // Non-date fields
            pv('pv-status', data.status);
            pv('pv-emp', data.processing_employee);
            pv('pv-agency', data.processing_agency);
            pv('pv-agent', data.agent_name);
            pv('pv-officer', data.passport_officer);
            pv('pv-received', data.received_by_original);
            pv('pv-name', data.applicant_name);
            pv('pv-number', data.passport_number);
            pv('pv-nationality', data.nationality);
            pv('pv-phone', data.phone);
            pv('pv-address', data.address);
            pv('pv-nid', data.nid_number);

            // Dynamic date fields
            const createdInfo = formatDynamicDate(data.created_at, 'created');
            const dobInfo = formatDynamicDate(data.date_of_birth, 'dob');
            const issueInfo = formatDynamicDate(data.issue_date, 'issue');
            const expiryInfo = formatDynamicDate(data.expiry_date, 'expiry');

            pvHtml('pv-created', createdInfo.html);
            pvHtml('pv-dob', dobInfo.html);
            pvHtml('pv-issue', issueInfo.html);
            pvHtml('pv-expiry', expiryInfo.html);

            // Conditional styling on expiry box
            const expiryBox = document.getElementById('kv-expiry');
            if (expiryBox) {
                const dynamicClasses = ['bg-danger-subtle', 'border-danger', 'bg-warning-subtle', 'border-warning'];
                expiryBox.classList.remove(...dynamicClasses);
                if (expiryInfo.boxClass) {
                    expiryBox.classList.add(...expiryInfo.boxClass.split(' '));
                }
            }

            preview.style.display = 'block';
            preview.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });
</script>
@endpush
@endsection
