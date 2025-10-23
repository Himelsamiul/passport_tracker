@extends('backend.master')

@section('content')
<div class="container-fluid py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">New Passport Collection</h3>
    <a href="{{ route('collections.index') }}" class="btn btn-outline-secondary">Back</a>
  </div>

  <form action="{{ route('collections.store') }}" method="POST">
    @csrf

    <div class="row g-3">
      <div class="col-lg-6">
        <label class="form-label fw-semibold">Select Passport</label>
        <select id="passportSelect" name="passport_id" class="form-select" required>
          <option value="">-- Choose passport ready to collect --</option>
          @foreach($passports as $p)
            <option value="{{ $p->id }}">
              {{ $p->passport_number }} — {{ $p->applicant_name }} (Agent: {{ $p->agent->name ?? 'N/A' }})
            </option>
          @endforeach
        </select>
        @error('passport_id') <small class="text-danger">{{ $message }}</small> @enderror
      </div>

      <div class="col-lg-3">
        <label class="form-label fw-semibold">Collected By (Employee)</label>
        <select name="employee_id" class="form-select" required>
          <option value="">-- Select employee --</option>
          @foreach($employees as $e)
            <option value="{{ $e->id }}">{{ $e->name }}</option>
          @endforeach
        </select>
        @error('employee_id') <small class="text-danger">{{ $message }}</small> @enderror
      </div>

      <div class="col-lg-3">
        <label class="form-label fw-semibold">Collection Date & Time</label>
        <input type="datetime-local" name="collected_at" class="form-control" required>
        @error('collected_at') <small class="text-danger">{{ $message }}</small> @enderror
      </div>

      <div class="col-12">
        <label class="form-label fw-semibold">Notes (optional)</label>
        <textarea name="notes" rows="2" class="form-control" placeholder="Any remarks"></textarea>
      </div>
    </div>

    {{-- Auto-preview panel (mirrors your Processing view style) --}}
    <div id="preview" class="mt-4" style="display:none;">
      {{-- Processing Information --}}
      <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-primary text-white">Processing Information</div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-3">
              <div class="kv box">
                <div class="label">Status</div>
                <div class="value" id="pv-status">-</div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="kv box">
                <div class="label">Employee (Picked Up)</div>
                <div class="value" id="pv-emp">-</div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="kv box">
                <div class="label">Agency</div>
                <div class="value" id="pv-agency">-</div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="kv box">
                <div class="label">Created</div>
                <div class="value" id="pv-created">-</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Stakeholders --}}
      <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-success text-white">Stakeholders</div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-4">
              <div class="kv box">
                <div class="label">Agent</div>
                <div class="value" id="pv-agent">-</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="kv box">
                <div class="label">Passport Officer</div>
                <div class="value" id="pv-officer">-</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="kv box">
                <div class="label">Received By (Original)</div>
                <div class="value" id="pv-received">-</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Passport Details --}}
      <div class="card border-0 shadow-sm">
        <div class="card-header text-white" style="background:#7c3aed;">Passport Details</div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-4">
              <div class="kv box"><div class="label">Applicant Name</div><div class="value" id="pv-name">-</div></div>
            </div>
            <div class="col-md-4">
              <div class="kv box"><div class="label">Passport Number</div><div class="value" id="pv-number">-</div></div>
            </div>
            <div class="col-md-4">
              <div class="kv box"><div class="label">Nationality</div><div class="value" id="pv-nationality">-</div></div>
            </div>
            <div class="col-md-4">
              <div class="kv box"><div class="label">Phone</div><div class="value" id="pv-phone">-</div></div>
            </div>
            <div class="col-md-4">
              <div class="kv box"><div class="label">Address</div><div class="value" id="pv-address">-</div></div>
            </div>
            <div class="col-md-4">
              <div class="kv box"><div class="label">DOB</div><div class="value" id="pv-dob">-</div></div>
            </div>
            <div class="col-md-4">
              <div class="kv box"><div class="label">Issue Date</div><div class="value" id="pv-issue">-</div></div>
            </div>
            <div class="col-md-4">
              <div class="kv box"><div class="label">Expiry Date</div><div class="value" id="pv-expiry">-</div></div>
            </div>
            <div class="col-md-4">
              <div class="kv box"><div class="label">NID</div><div class="value" id="pv-nid">-</div></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="mt-4 d-flex gap-2">
      <button type="submit" class="btn btn-success">Save Collection</button>
    </div>
  </form>
</div>


@push('styles')
<style>
  .kv .label { font-size: 12px; color: #6c757d; text-transform: uppercase; letter-spacing: .02em; margin-bottom: 4px; }
  .kv .value { font-weight: 600; font-size: 14px; }
  .kv .box { background: #fff; border: 1px solid #e9ecef; border-radius: .5rem; padding: .75rem .9rem; height: 100%; }
</style>
@endpush

@push('scripts')
<script>
  const sel = document.getElementById('passportSelect');
  const pv  = (id, v) => document.getElementById(id).innerText = (v && v !== 'null') ? v : '-';

  sel.addEventListener('change', async (e) => {
    const id = e.target.value;
    const preview = document.getElementById('preview');

    if (!id) { preview.style.display = 'none'; return; }

    try {
      const url = `{{ route('collections.passport.info', ':id') }}`.replace(':id', id);
      const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      const data = await res.json();

      // Processing Information (latest PassportProcessing)
      pv('pv-status',  data.status ?? '-');
      pv('pv-created', data.created_at ? new Date(data.created_at).toLocaleString() : '-');
      pv('pv-emp',     data.processing_employee ?? '-'); // Employee (Picked Up)
      pv('pv-agency',  data.processing_agency ?? '-');   // Agency

      // Stakeholders
      pv('pv-agent',   data.agent_name ?? '-');          // Agent (from Passport->agent)
      pv('pv-officer', data.passport_officer ?? '-');    // Passport Officer (from Passport->passportOfficer)
      pv('pv-received',data.received_by_original ?? '-');// Received By (Original) (from Passport->employee or column)

      // Passport details
      pv('pv-name',        data.applicant_name);
      pv('pv-number',      data.passport_number);
      pv('pv-nationality', data.nationality);
      pv('pv-phone',       data.phone);
      pv('pv-address',     data.address);
      pv('pv-dob',         data.date_of_birth);
      pv('pv-issue',       data.issue_date);
      pv('pv-expiry',      data.expiry_date);
      pv('pv-nid',         data.nid_number);

      preview.style.display = 'block';
    } catch (err) {
      console.error(err);
      preview.style.display = 'none';
      alert('Could not load passport info.');
    }
  });
</script>
@endpush
@endsection