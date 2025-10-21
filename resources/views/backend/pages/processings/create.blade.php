@extends('backend.master')

@section('content')
<style>
  .readonly input, .readonly textarea, .readonly select {
    background-color: #f8f9fa !important;
    cursor: not-allowed;
    pointer-events: none; /* fully read-only UI-wise */
  }
  .thumb {
    max-height: 160px; border: 1px solid #dee2e6; border-radius: .5rem;
  }
</style>

<div class="container-fluid py-4">
  <h3 class="mb-3">Create Processing</h3>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  {{-- STEP 1: Select Passport --}}
  <div class="card mb-3">
    <div class="card-header fw-bold">1) Select Passport</div>
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Passport</label>
          <select id="passport_id_select" class="form-select" required>
            <option value="">-- Select a Passport --</option>
            @foreach($passports as $p)
              <option value="{{ $p->id }}">{{ $p->passport_number }} — {{ $p->applicant_name }}</option>
            @endforeach
          </select>
          <small class="text-muted">After selecting, all Add Passport fields will auto-load below (read-only).</small>
        </div>
      </div>
    </div>
  </div>

  {{-- STEP 1.5: Auto-loaded Add Passport content (READ ONLY) --}}
  <div id="passportAutoBlock" class="card mb-3" style="display:none;">
    <div class="card-header fw-bold">Passport (Auto-Loaded, Read-only)</div>
    <div class="card-body readonly">
      <div class="row g-3">
        <!-- Agent -->
        <div class="col-md-4">
          <label class="form-label">Agent</label>
          <input id="f_agent_name" class="form-control" readonly>
        </div>

        <!-- Passport Officer -->
        <div class="col-md-4">
          <label class="form-label">Passport Officer</label>
          <input id="f_officer_name" class="form-control" readonly>
        </div>

        <!-- Passport Received By -->
        <div class="col-md-4">
          <label class="form-label">Passport Received By</label>
          <input id="f_received_by_name" class="form-control" readonly>
        </div>

        <!-- Applicant Name -->
        <div class="col-md-4">
          <label class="form-label">Applicant Name</label>
          <input id="f_applicant_name" class="form-control" readonly>
        </div>

        <!-- Phone -->
        <div class="col-md-4">
          <label class="form-label">Phone</label>
          <input id="f_phone" class="form-control" readonly>
        </div>

        <!-- Address -->
        <div class="col-md-6">
          <label class="form-label">Address</label>
          <input id="f_address" class="form-control" readonly>
        </div>

        <!-- Date of Birth -->
        <div class="col-md-3">
          <label class="form-label">Date of Birth</label>
          <input id="f_dob" class="form-control" readonly>
        </div>

        <!-- Nationality -->
        <div class="col-md-3">
          <label class="form-label">Nationality</label>
          <input id="f_nationality" class="form-control" readonly>
        </div>

        <!-- Passport Number -->
        <div class="col-md-4">
          <label class="form-label">Passport Number</label>
          <input id="f_passport_no" class="form-control" readonly>
        </div>

        <!-- Issue Date -->
        <div class="col-md-4">
          <label class="form-label">Issue Date</label>
          <input id="f_issue" class="form-control" readonly>
        </div>

        <!-- Expiry Date -->
        <div class="col-md-4">
          <label class="form-label">Expiry Date</label>
          <input id="f_expiry" class="form-control" readonly>
        </div>

        <!-- NID -->
        <div class="col-md-6">
          <label class="form-label">NID Number</label>
          <input id="f_nid" class="form-control" readonly>
        </div>

        <!-- Notes -->
        <div class="col-md-6">
          <label class="form-label">Notes</label>
          <textarea id="f_notes" class="form-control" rows="2" readonly></textarea>
        </div>

        <!-- Picture -->
        <div class="col-12" id="picWrap" style="display:none;">
          <label class="form-label">Passport Picture</label><br>
          <img id="f_picture" class="thumb" alt="Passport Picture">
        </div>
      </div>
    </div>
  </div>

  {{-- STEP 2: Processing Details (the only fields you actually submit) --}}
  <form method="POST" action="{{ route('processings.store') }}">
    @csrf
    <input type="hidden" name="passport_id" id="passport_id_hidden">

    <div class="card mb-3">
      <div class="card-header fw-bold">2) Processing Details</div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Employee (Picked Up By)</label>
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
              <option value="">-- Select Agency (optional) --</option>
              @foreach($agencies as $a)
                <option value="{{ $a->id }}">{{ $a->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-4">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
              <option value="PENDING">PENDING</option>
              <option value="IN_PROGRESS">IN_PROGRESS</option>
              <option value="DONE">DONE</option>
              <option value="REJECTED">REJECTED</option>
            </select>
          </div>

          <div class="col-12">
            <label class="form-label">Notes</label>
            <textarea name="notes" class="form-control" rows="3" placeholder="Any notes..."></textarea>
          </div>
        </div>
      </div>
    </div>

    <div class="text-end">
      <button type="submit" class="btn btn-primary">Save Processing</button>
    </div>
  </form>
</div>

{{-- JS to auto-load full Add Passport content into the page --}}
<script>
  const sel   = document.getElementById('passport_id_select');
  const block = document.getElementById('passportAutoBlock');
  const hid   = document.getElementById('passport_id_hidden');

  sel.addEventListener('change', async function(){
    const id = this.value;
    hid.value = id || '';
    if(!id){
      block.style.display = 'none';
      return;
    }
    try{
      const url = "{{ route('processings.passport.details', ':id') }}".replace(':id', id);
      const res = await fetch(url);
      if(!res.ok) throw new Error('Failed to load passport details');
      const d = await res.json();

      // Fill all "Add Passport" fields (read-only)
      document.getElementById('f_agent_name').value       = d.agent?.name ?? '—';
      document.getElementById('f_officer_name').value     = d.passport_officer?.name ?? '—';
      document.getElementById('f_received_by_name').value = d.received_by?.name ?? '—';

      document.getElementById('f_applicant_name').value   = d.applicant_name ?? '';
      document.getElementById('f_phone').value            = d.phone ?? '';
      document.getElementById('f_address').value          = d.address ?? '';
      document.getElementById('f_dob').value              = d.date_of_birth ?? '';
      document.getElementById('f_nationality').value      = d.nationality ?? '';
      document.getElementById('f_passport_no').value      = d.passport_number ?? '';
      document.getElementById('f_issue').value            = d.issue_date ?? '';
      document.getElementById('f_expiry').value           = d.expiry_date ?? '';
      document.getElementById('f_nid').value              = d.nid_number ?? '';
      document.getElementById('f_notes').value            = d.notes ?? '';

      if(d.picture_url){
        document.getElementById('picWrap').style.display = 'block';
        document.getElementById('f_picture').src = d.picture_url;
      } else {
        document.getElementById('picWrap').style.display = 'none';
      }

      block.style.display = 'block';
    }catch(e){
      alert(e.message || 'Something went wrong.');
      block.style.display = 'none';
    }
  });
</script>
@endsection
