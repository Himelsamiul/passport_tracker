@extends('backend.master')

@section('page-title', 'Add Passport')
@section('page-subtitle', 'Register a new passport submission')

@section('content')
{{-- ============================================================
     ডিজাইন নোট (পাসপোর্ট তৈরির পেজে যা কাজ হয়েছে):
     • Notun: Country searchable dropdown (Select2) + passport number type korlei live duplicate check ("already exists" error niche dekhay) + server-side unique validation.
     • সবুজ/লাইট কাস্টম CSS পুরো বাদ — নতুন ডার্ক থিমে ফর্ম কার্ড।
     • ফর্ম দুইটি সেকশনে সাজানো: "Stakeholder Details" ও "Passport Informations"।
     • জাতীয়তা ড্রপডাউনে Select2 সার্চ আগের মতোই আছে — শুধু ডার্ক স্টাইল দেওয়া হয়েছে।
     • তারিখের ফিল্ডে Flatpickr (ডার্ক থিম) — DOB, Issue, Expiry।
     • সব ফিল্ডের নাম/ভ্যালিডেশন/রিকোয়্যার্ড আগের মতোই — লজিকে হাত দেওয়া হয়নি।
     ============================================================ --}}

{{-- Flatpickr CSS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
{{-- Select2 CSS --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

{{-- Select2 dark theme overrides --}}
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
  .select2-search__field::placeholder { color: var(--faint); }
  .select2-container--default .select2-search--dropdown .select2-search__field:focus { border-color: var(--accent); }
  .select2-results__option { color: var(--text-soft); font-size: .865rem; padding: 8px 14px; }
  .select2-container--default .select2-results__option--highlighted[aria-selected] {
    background: var(--accent);
    color: #fff;
  }
  .select2-container--default .select2-results__option[aria-selected=true] { color: var(--accent-2); }
  .select2-container--default .select2-selection--single .select2-selection__placeholder { color: var(--faint); }
</style>

<div class="container-fluid px-0" style="max-width:1100px">

  <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div>
      <h3 class="mb-0"><i class="fas fa-passport me-2" style="color:var(--accent-2)"></i>Add New Passport Submission</h3>
      <p class="ph-desc" style="color:var(--muted);font-size:.84rem;margin:2px 0 0">Register the applicant, document details and the team handling the file.</p>
    </div>
    <a href="{{ route('passports.index') }}" class="btn btn-outline-secondary btn-sm">
      <i class="fas fa-arrow-left me-1"></i> Back to List
    </a>
  </div>

  <div class="card">
    <div class="card-body p-3 p-md-4">

      @if ($errors->any())
        <div class="alert alert-danger mb-4">
          <ul class="mb-0 list-unstyled">
            @foreach ($errors->all() as $error)
              <li><i class="fas fa-exclamation-circle me-2"></i> {{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('passports.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-4">

          {{-- ===== Section: Stakeholders ===== --}}
          <div class="col-12">
            <div class="section-title mb-1"><i class="fas fa-handshake"></i> Stakeholder Details</div>
            <p class="text-muted mb-0" style="font-size:.8rem">Who is handling this passport file.</p>
          </div>

          <div class="col-md-4">
            <label class="form-label">Agent</label>
            <select name="agent_id" class="form-select">
              <option value="">— Select Agent —</option>
              @foreach($agents as $a)
                <option value="{{ $a->id }}" @selected(old('agent_id')==$a->id)>{{ $a->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-4">
            <label class="form-label">Passport Officer</label>
            <select name="passport_officer_id" class="form-select">
              <option value="">— Select Officer —</option>
              @foreach($officers as $o)
                <option value="{{ $o->id }}" @selected(old('passport_officer_id')==$o->id)>{{ $o->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-4">
            <label class="form-label">Passport Received By</label>
            <select name="employee_id" class="form-select">
              <option value="">— Select Employee —</option>
              @foreach($employees as $emp)
                <option value="{{ $emp->id }}" @selected(old('employee_id')==$emp->id)>{{ $emp->name }}</option>
              @endforeach
            </select>
          </div>

          {{-- ===== Section: Passport info ===== --}}
          <div class="col-12 mt-4">
            <div class="section-title mb-1"><i class="fas fa-address-card"></i> Passport Informations</div>
            <p class="text-muted mb-0" style="font-size:.8rem">Applicant personal and document details.</p>
          </div>

          <div class="col-md-4">
            <label class="form-label">Applicant Name <span class="text-danger">*</span></label>
            <input type="text" name="applicant_name" value="{{ old('applicant_name') }}" class="form-control" placeholder="Full Name" required>
          </div>

          <div class="col-md-4">
            <label class="form-label">Phone <span class="text-danger">*</span></label>
            <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" placeholder="Contact Number" required>
          </div>

          {{-- Nationality — searchable with Select2 --}}
          <div class="col-md-4">
            <label class="form-label">Nationality <span class="text-danger">*</span></label>
            <select name="nationality" class="form-select" id="nationality-select" required>
              <option value="">— Select Nationality —</option>
              @php
                $nationalities = [
                  'Afghan', 'Albanian', 'Algerian', 'American', 'Andorran', 'Angolan', 'Antiguan and Barbudan', 'Argentine',
                  'Armenian', 'Australian', 'Austrian', 'Azerbaijani', 'Bahamian', 'Bahraini', 'Bangladeshi', 'Barbadian',
                  'Belarusian', 'Belgian', 'Belizean', 'Beninese', 'Bhutanese', 'Bolivian', 'Bosnian and Herzegovinian',
                  'Botswana', 'Brazilian', 'British', 'Bruneian', 'Bulgarian', 'Burkinabe', 'Burundian', 'Cabo Verdean',
                  'Cambodian', 'Cameroonian', 'Canadian', 'Central African', 'Chadian', 'Chilean', 'Chinese', 'Colombian',
                  'Comoran', 'Congolese (Congo - Brazzaville)', 'Congolese (Congo - Kinshasa)', 'Costa Rican', 'Croatian',
                  'Cuban', 'Cypriot', 'Czech', 'Danish', 'Djiboutian', 'Dominican', 'Dominican (Dominica)', 'Dutch',
                  'East Timorese', 'Ecuadorian', 'Egyptian', 'Salvadoran', 'Equatorial Guinean', 'Eritrean', 'Estonian',
                  'Eswatini', 'Ethiopian', 'Fijian', 'Finnish', 'French', 'Gabonese', 'Gambian', 'Georgian', 'German',
                  'Ghanaian', 'Greek', 'Grenadian', 'Guatemalan', 'Guinean', 'Guinean (Bissau)', 'Guyanese', 'Haitian',
                  'Honduran', 'Hungarian', 'Icelandic', 'Indian', 'Indonesian', 'Iranian', 'Iraqi', 'Irish', 'Israeli',
                  'Italian', 'Ivorian', 'Jamaican', 'Japanese', 'Jordanian', 'Kazakhstani', 'Kenyan', 'Kiribati', 'Korean (North)',
                  'Korean (South)', 'Kosovan', 'Kuwaiti', 'Kyrgyz', 'Lao', 'Latvian', 'Lebanese', 'Lesotho', 'Liberian',
                  'Libyan', 'Liechtensteiner', 'Lithuanian', 'Luxembourgish', 'Malagasy', 'Malawian', 'Malaysian', 'Maldivian',
                  'Malian', 'Maltese', 'Marshallese', 'Mauritanian', 'Mauritian', 'Mexican', 'Micronesian', 'Moldovan',
                  'Monacan', 'Mongolian', 'Montenegrin', 'Moroccan', 'Mozambican', 'Myanmarese', 'Namibian', 'Nauru',
                  'Nepali', 'New Zealander', 'Nicaraguan', 'Nigerien', 'Nigerian', 'North Macedonian', 'Norwegian', 'Omani',
                  'Pakistani', 'Palauan', 'Palestinian', 'Panamanian', 'Papua New Guinean', 'Paraguayan', 'Peruvian',
                  'Philippine', 'Polish', 'Portuguese', 'Qatari', 'Romanian', 'Russian', 'Rwandan', 'Kittitian and Nevisian',
                  'Saint Lucian', 'Vincentian', 'Samoan', 'San Marino', 'Sao Tomean', 'Saudi Arabian', 'Senegalese', 'Serbian',
                  'Seychellois', 'Sierra Leonean', 'Singaporean', 'Slovak', 'Slovenian', 'Solomon Islander', 'Somali', 'South African',
                  'South Sudanese', 'Spanish', 'Sri Lankan', 'Sudanese', 'Surinamese', 'Swedish', 'Swiss', 'Syrian',
                  'Taiwanese', 'Tajikistani', 'Tanzanian', 'Thai', 'Togolese', 'Tongan', 'Trinidadian and Tobagonian', 'Tunisian',
                  'Turkish', 'Turkmen', 'Tuvaluan', 'Ugandan', 'Ukrainian', 'Emirati', 'British', 'Uruguayan', 'Uzbekistani',
                  'Vanuatuan', 'Venezuelan', 'Vietnamese', 'Yemeni', 'Zambian', 'Zimbabwean'
                ];
              @endphp
              @foreach($nationalities as $n)
                <option value="{{ $n }}" @selected(old('nationality')==$n)>{{ $n }}</option>
              @endforeach
            </select>
          </div>

          {{-- Country — searchable dropdown --}}
          <div class="col-md-4">
            <label class="form-label">Country</label>
            <select name="country" class="form-select" id="country-select">
              <option value="">— Select Country —</option>
              @include('backend.pages.fixed.passports._countries', ['selected' => old('country')])
            </select>
          </div>

          <div class="col-md-8">
            <label class="form-label">Address <span class="text-danger">*</span></label>
            <input type="text" name="address" value="{{ old('address') }}" class="form-control" placeholder="Street, City, Postal Code" required>
          </div>

          <div class="col-md-4">
            <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
            <input type="text" name="date_of_birth" value="{{ old('date_of_birth') }}" class="form-control flatpickr-date" placeholder="Select Date of Birth" required>
          </div>

          <div class="col-md-4">
            <label class="form-label">Passport Number <span class="text-danger">*</span></label>
            <input type="text" name="passport_number" value="{{ old('passport_number') }}" id="passport_number"
                   class="form-control" placeholder="A1234567" required autocomplete="off">
            <div class="field-error" id="pnError"><i class="fas fa-circle-exclamation me-1"></i>This passport number already exists.</div>
          </div>

          <div class="col-md-4">
            <label class="form-label">Issue Date <span class="text-danger">*</span></label>
            <input type="text" name="issue_date" value="{{ old('issue_date') }}" class="form-control flatpickr-date" placeholder="Select Issue Date" required>
          </div>

          <div class="col-md-4">
            <label class="form-label">Expiry Date <span class="text-danger">*</span></label>
            <input type="text" name="expiry_date" value="{{ old('expiry_date') }}" class="form-control flatpickr-date" placeholder="Select Expiry Date" required>
          </div>

          <div class="col-12">
            <label class="form-label">NID Number</label>
            <input type="text" name="nid_number" value="{{ old('nid_number') }}" class="form-control" placeholder="National ID or equivalent (Optional)">
          </div>

          <div class="col-12">
            <label class="form-label">Notes</label>
            <textarea name="notes" rows="3" class="form-control" placeholder="Add any relevant notes or special instructions (Optional)">{{ old('notes') }}</textarea>
          </div>
        </div>

        {{-- Action buttons --}}
        <div class="mt-4 d-flex gap-2">
          <button type="submit" class="btn btn-primary px-4">
            <i class="fas fa-check me-1"></i> Save Passport
          </button>
          <a href="{{ route('passports.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to List
          </a>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- jQuery (required by Select2) --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
{{-- Flatpickr --}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
{{-- Select2 --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Date pickers
    flatpickr(".flatpickr-date", {
      allowInput: true,
      dateFormat: "Y-m-d",
      altInput: true,
      altFormat: "F j, Y"
    });

    // Searchable nationality
    $('#nationality-select').select2({
      placeholder: "Type or select nationality...",
      allowClear: true,
      width: '100%'
    });

    // Searchable country
    $('#country-select').select2({
      placeholder: "Type or select country...",
      allowClear: true,
      width: '100%'
    });

    // Live duplicate check for the passport number — shows the error the
    // moment an existing number is typed, before the form is submitted.
    var pnInput = document.getElementById('passport_number');
    var pnError = document.getElementById('pnError');
    var pnTimer = null, pnDuplicate = false;

    function checkPassportNumber() {
      var number = pnInput.value.trim();
      if (number === '') {
        pnDuplicate = false;
        pnInput.classList.remove('is-invalid');
        pnError.style.display = 'none';
        return;
      }
      fetch("{{ route('passports.check') }}?number=" + encodeURIComponent(number))
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
