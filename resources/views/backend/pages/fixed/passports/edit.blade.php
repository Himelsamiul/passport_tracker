@extends('backend.master')

@section('page-title', 'Edit Passport')
@section('page-subtitle', 'Update passport record')

@section('content')
{{-- ============================================================
     ডিজাইন নোট (পাসপোর্ট এডিট পেজে যা কাজ হয়েছে):
     • Notun: Country searchable dropdown (Select2) + passport number live duplicate check (nijer record bad diye) + unique validation.
     • নতুন ডার্ক থিমে ফর্ম কার্ড; উপরে পেজ হেডার + "Back to List" বাটন।
     • ফিল্ডগুলো সেকশনে সাজানো: Stakeholder + Passport Informations।
     • সব পুরনো ভ্যালু, ফিল্ড-নেম ও ভ্যালিডেশন আগের মতোই।
     ============================================================ --}}
<div class="container-fluid px-0" style="max-width:1100px">

  <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div>
      <h3 class="mb-0">Edit Passport</h3>
      <p class="ph-desc" style="color:var(--muted);font-size:.84rem;margin:2px 0 0">
        Updating record for <span class="fw-semibold" style="color:var(--text-soft)">{{ $passport->passport_number }}</span> — {{ $passport->applicant_name }}
      </p>
    </div>
    <div class="d-flex gap-2">
      <a href="{{ route('passports.show', $passport->id) }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-eye me-1"></i> View
      </a>
      <a href="{{ route('passports.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Back to List
      </a>
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
      <form action="{{ route('passports.update', $passport->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="section-title"><i class="fas fa-handshake"></i> Stakeholder</div>
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Agent</label>
            <select name="agent_id" class="form-select">
              <option value="">— No Agent —</option>
              @foreach($agents as $a)
                <option value="{{ $a->id }}" @selected(old('agent_id', $passport->agent_id)==$a->id)>{{ $a->name }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="section-title mt-4"><i class="fas fa-address-card"></i> Passport Informations</div>
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Applicant Name <span class="text-danger">*</span></label>
            <input type="text" name="applicant_name" value="{{ old('applicant_name', $passport->applicant_name) }}" class="form-control" required>
          </div>

          <div class="col-md-4">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" value="{{ old('phone', $passport->phone) }}" class="form-control">
          </div>

          <div class="col-md-4">
            <label class="form-label">Nationality</label>
            <input type="text" name="nationality" value="{{ old('nationality', $passport->nationality) }}" class="form-control">
          </div>

          {{-- Country — searchable dropdown --}}
          <div class="col-md-4">
            <label class="form-label">Country</label>
            <select name="country" class="form-select" id="country-select">
              <option value="">— Select Country —</option>
              @include('backend.pages.fixed.passports._countries', ['selected' => old('country', $passport->country)])
            </select>
          </div>

          <div class="col-md-6">
            <label class="form-label">Address</label>
            <input type="text" name="address" value="{{ old('address', $passport->address) }}" class="form-control">
          </div>

          <div class="col-md-3">
            <label class="form-label">Date of Birth</label>
            <input type="date" name="date_of_birth" value="{{ old('date_of_birth', optional($passport->date_of_birth)->format('Y-m-d')) }}" class="form-control">
          </div>

          <div class="col-md-3">
            <label class="form-label">Passport Number <span class="text-danger">*</span></label>
            <input type="text" name="passport_number" value="{{ old('passport_number', $passport->passport_number) }}"
                   id="passport_number" class="form-control" required autocomplete="off">
            <div class="field-error" id="pnError"><i class="fas fa-circle-exclamation me-1"></i>This passport number already exists.</div>
          </div>

          <div class="col-md-4">
            <label class="form-label">Issue Date</label>
            <input type="date" name="issue_date" value="{{ old('issue_date', optional($passport->issue_date)->format('Y-m-d')) }}" class="form-control">
          </div>

          <div class="col-md-4">
            <label class="form-label">Expiry Date</label>
            <input type="date" name="expiry_date" value="{{ old('expiry_date', optional($passport->expiry_date)->format('Y-m-d')) }}" class="form-control">
          </div>

          <div class="col-md-4">
            <label class="form-label">NID Number</label>
            <input type="text" name="nid_number" value="{{ old('nid_number', $passport->nid_number) }}" class="form-control">
          </div>
        </div>

        <div class="mt-4 d-flex gap-2">
          <button type="submit" class="btn btn-primary px-4">
            <i class="fas fa-arrows-rotate me-1"></i> Update Passport
          </button>
          <a href="{{ route('passports.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Select2 for the searchable Country dropdown --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    $('#country-select').select2({
      placeholder: "Type or select country...",
      allowClear: true,
      width: '100%'
    });

    // Live duplicate check (ignores this passport's own number)
    var pnInput = document.getElementById('passport_number');
    var pnError = document.getElementById('pnError');
    var pnTimer = null, pnDuplicate = false;
    var ignoreId = {{ $passport->id }};

    function checkPassportNumber() {
      var number = pnInput.value.trim();
      if (number === '') {
        pnDuplicate = false;
        pnInput.classList.remove('is-invalid');
        pnError.style.display = 'none';
        return;
      }
      fetch("{{ route('passports.check') }}?number=" + encodeURIComponent(number) + "&ignore_id=" + ignoreId)
        .then(function (r) { return r.json(); })
        .then(function (d) {
          pnDuplicate = !!d.exists;
          pnInput.classList.toggle('is-invalid', pnDuplicate);
          pnError.style.display = pnDuplicate ? 'block' : 'none';
        })
        .catch(function () {});
    }

    pnInput.addEventListener('input', function () {
      clearTimeout(pnTimer);
      pnTimer = setTimeout(checkPassportNumber, 350);
    });
    pnInput.addEventListener('blur', checkPassportNumber);

    document.querySelector('form').addEventListener('submit', function (e) {
      if (pnDuplicate) {
        e.preventDefault();
        pnInput.classList.add('is-invalid');
        pnError.style.display = 'block';
        pnInput.focus();
      }
    });
  });
</script>
@endpush
@endsection
