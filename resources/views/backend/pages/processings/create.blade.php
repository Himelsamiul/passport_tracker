@extends('backend.master')

@section('page-title', 'New Processing')
@section('page-subtitle', 'Create a processing entry for a passport')

@section('content')
{{-- ============================================================
     ডিজাইন নোট (প্রসেসিং তৈরির পেজে যা কাজ হয়েছে):
     • পুরনো পার্পল গ্র্যাডিয়েন্ট/গ্লাস/শাইমার CSS (৪৫০+ লাইন) পুরো বাদ।
     • নতুন ডার্ক থিমে ৩-ধাপের ফর্ম: ১) পাসপোর্ট সিলেক্ট  ২) অটো-লোড ডিটেইলস  ৩) প্রসেসিং ডিটেইলস।
     • পাসপোর্ট সিলেক্ট করলেই AJAX দিয়ে নিচে সব তথ্য অটো লোড হয় (আগের মতোই)।
     • তারিখ-সময় ফিল্ডে Flatpickr + Today / Tomorrow / +7 days / Clear চিপ।
     • সব ফিল্ড-নেম, রুট ও ভ্যালিডেশন আগের মতোই।
     ============================================================ --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />

<div class="container-fluid px-0" style="max-width:1100px">

  {{-- Header --}}
  <div class="page-head d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div>
      <h3>Create Processing</h3>
      <p class="ph-desc">Pick a passport, verify the auto-loaded details, then set the processing plan.</p>
    </div>
    <a href="{{ route('processings.index') }}" class="btn btn-outline-secondary btn-sm">
      <i class="fas fa-arrow-left me-1"></i> Back to List
    </a>
  </div>

  {{-- Flash --}}
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  {{-- STEP 1: Select Passport --}}
  <div class="card mb-3">
    <div class="card-header"><i class="fas fa-magnifying-glass me-2" style="color:var(--accent-2)"></i>Select Passport</div>
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-8">
          <label class="form-label">Passport Selection <span class="text-danger">*</span></label>
          <select id="passport_id_select" class="form-select" required>
            <option value="">-- Select a Passport --</option>
            @foreach($passports as $p)
              <option value="{{ $p->id }}">{{ $p->passport_number }} — {{ $p->applicant_name }}</option>
            @endforeach
          </select>
          <small class="text-muted mt-2 d-block">Select a passport to automatically load all details below.</small>
        </div>
      </div>
    </div>
  </div>

  {{-- STEP 2: Auto-loaded Passport Details --}}
  <div id="passportAutoBlock" class="card mb-3" style="display:none;">
    <div class="card-header"><i class="fas fa-address-card me-2" style="color:var(--accent-2)"></i>Passport Details <span class="badge bg-success ms-2">Loaded</span></div>
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Agent</label>
          <input id="f_agent_name" class="form-control" readonly>
        </div>
        <div class="col-md-4">
          <label class="form-label">Officer</label>
          <input id="f_officer_name" class="form-control" readonly>
        </div>
        <div class="col-md-4">
          <label class="form-label">Received By</label>
          <input id="f_received_by_name" class="form-control" readonly>
        </div>

        <div class="col-md-4">
          <label class="form-label">Applicant Name</label>
          <input id="f_applicant_name" class="form-control" readonly>
        </div>
        <div class="col-md-4">
          <label class="form-label">Phone Number</label>
          <input id="f_phone" class="form-control" readonly>
        </div>
        <div class="col-md-4">
          <label class="form-label">Address</label>
          <input id="f_address" class="form-control" readonly>
        </div>

        <div class="col-md-3">
          <label class="form-label">Date of Birth</label>
          <input id="f_dob" class="form-control" readonly>
        </div>
        <div class="col-md-3">
          <label class="form-label">Nationality</label>
          <input id="f_nationality" class="form-control" readonly>
        </div>
        <div class="col-md-3">
          <label class="form-label">Passport Number</label>
          <input id="f_passport_no" class="form-control" readonly>
        </div>
        <div class="col-md-3">
          <label class="form-label">NID Number</label>
          <input id="f_nid" class="form-control" readonly>
        </div>

        <div class="col-md-4">
          <label class="form-label">Issue Date</label>
          <input id="f_issue" class="form-control" readonly>
        </div>
        <div class="col-md-4">
          <label class="form-label">Expiry Date</label>
          <input id="f_expiry" class="form-control" readonly>
        </div>
        <div class="col-md-4">
          <label class="form-label">Notes</label>
          <textarea id="f_notes" class="form-control" rows="1" readonly></textarea>
        </div>

        <div class="col-12" id="picWrap" style="display:none;">
          <label class="form-label">Passport Picture</label><br>
          <img id="f_picture" alt="Passport Picture" style="max-width: 200px; height: auto;" class="rounded-3 mt-1" style="border:1px solid var(--line)">
        </div>
      </div>
    </div>
  </div>

  {{-- STEP 3: Processing Details --}}
  <form method="POST" action="{{ route('processings.store') }}" id="processingForm">
    @csrf
    <input type="hidden" name="passport_id" id="passport_id_hidden">

    <div class="card mb-3">
      <div class="card-header"><i class="fas fa-gears me-2" style="color:var(--accent-2)"></i>Processing Details</div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Employee <span class="text-danger">*</span></label>
            <select name="employee_id" class="form-select" required>
              <option value="">-- Select Employee --</option>
              @foreach($employees as $e)
                <option value="{{ $e->id }}">{{ $e->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Agency</label>
            <select name="agency_id" class="form-select">
              <option value="">-- Optional --</option>
              @foreach($agencies as $a)
                <option value="{{ $a->id }}">{{ $a->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Status <span class="text-danger">*</span></label>
            <select name="status" class="form-select" required>
              <option value="PENDING">PENDING</option>
              <option value="IN_PROGRESS">IN_PROGRESS</option>
              <option value="DONE">DONE</option>
              <option value="REJECTED">REJECTED</option>
            </select>
          </div>

          <div class="col-md-6">
            <label class="form-label">Processing Date &amp; Time <span class="text-danger">*</span></label>
            <input type="text" id="processed_at_view" class="form-control" placeholder="Select date & time" required>
            <input type="hidden" name="processed_at" id="processed_at">

            <div class="d-flex flex-wrap gap-2 mt-2">
              <button type="button" class="preset" data-add="0"><i class="fas fa-calendar-day me-1"></i> Today</button>
              <button type="button" class="preset" data-add="1"><i class="fas fa-sun me-1"></i> Tomorrow</button>
              <button type="button" class="preset" data-add="7"><i class="fas fa-calendar-week me-1"></i> +7 days</button>
              <button type="button" class="preset" id="clearProcDate"><i class="fas fa-xmark me-1"></i> Clear</button>
            </div>
          </div>

          <div class="col-12">
            <label class="form-label">Processing Notes</label>
            <textarea name="notes" class="form-control" rows="3" placeholder="Any additional notes about this processing..."></textarea>
          </div>
        </div>

        <div class="d-flex gap-2 mt-4">
          <button type="submit" class="btn btn-primary px-4">
            <i class="fas fa-floppy-disk me-1"></i> Save Processing
          </button>
          <a href="{{ route('processings.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </div>
    </div>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@push('scripts')
<script>
  // === AJAX AUTO-LOAD ===
  const sel = document.getElementById('passport_id_select');
  const block = document.getElementById('passportAutoBlock');
  const hid = document.getElementById('passport_id_hidden');

  sel.addEventListener('change', async function() {
    const id = this.value;
    hid.value = id || '';

    if (!id) {
      block.style.display = 'none';
      return;
    }

    try {
      const url = "{{ route('processings.passport.details', ':id') }}".replace(':id', id);
      const res = await fetch(url);

      if (!res.ok) throw new Error('Failed to load passport details');
      const d = await res.json();

      // Populate fields
      document.getElementById('f_agent_name').value = d.agent?.name ?? '—';
      document.getElementById('f_officer_name').value = d.passport_officer?.name ?? '—';
      document.getElementById('f_received_by_name').value = d.received_by?.name ?? '—';
      document.getElementById('f_applicant_name').value = d.applicant_name ?? '';
      document.getElementById('f_phone').value = d.phone ?? '';
      document.getElementById('f_address').value = d.address ?? '';
      document.getElementById('f_dob').value = d.date_of_birth ?? '';
      document.getElementById('f_nationality').value = d.nationality ?? '';
      document.getElementById('f_passport_no').value = d.passport_number ?? '';
      document.getElementById('f_issue').value = d.issue_date ?? '';
      document.getElementById('f_expiry').value = d.expiry_date ?? '';
      document.getElementById('f_nid').value = d.nid_number ?? '';
      document.getElementById('f_notes').value = d.notes ?? '';

      const picWrap = document.getElementById('picWrap');
      if (d.picture_url) {
        picWrap.style.display = 'block';
        document.getElementById('f_picture').src = d.picture_url;
      } else {
        picWrap.style.display = 'none';
      }

      block.style.display = 'block';
    } catch(e) {
      alert(e.message || 'Something went wrong.');
      block.style.display = 'none';
    }
  });

  // === DYNAMIC DATE PICKER ===
  const pv = document.getElementById('processed_at_view');
  const ph = document.getElementById('processed_at');

  const fp = flatpickr(pv, {
    enableTime: true,
    dateFormat: "d M Y, h:i K",
    minDate: "today",
    time_24hr: false,
    onChange: function(sel) {
      if (sel.length) {
        const d = sel[0];
        const pad = n => String(n).padStart(2, '0');
        ph.value = `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())} ${pad(d.getHours())}:${pad(d.getMinutes())}:00`;
      } else {
        ph.value = '';
      }
    }
  });

  function setDatePlus(days) {
    const base = new Date();
    base.setHours(10, 0, 0, 0);
    base.setDate(base.getDate() + days);
    fp.setDate(base, true);
  }

  document.querySelectorAll('.preset[data-add]').forEach(ch => {
    ch.addEventListener('click', () => setDatePlus(parseInt(ch.dataset.add, 10)));
  });

  document.getElementById('clearProcDate').addEventListener('click', () => {
    fp.clear();
    ph.value = '';
    pv.value = '';
  });

  // Submit state
  document.getElementById('processingForm').addEventListener('submit', function() {
    const btn = this.querySelector('button[type="submit"]');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Saving...';
    btn.disabled = true;
  });
</script>
@endpush
@endsection
