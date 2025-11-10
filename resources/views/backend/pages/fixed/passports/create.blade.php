@extends('backend.master')

@section('content')
{{-- Load Flatpickr CSS for the Calendar Design --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_green.css">

{{-- Load Select2 CSS for Nationality Search Dropdown --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

{{-- Custom CSS for global design, card, and animations --}}
<style>
    /* 1. Global Light Background */
    body {
        background-color: #f0f2f5 !important; /* Very light, soft grey background */
    }

    /* 2. Form Card Design (Explicit White Background) */
    .passport-form-card {
        background-color: #ffffff; /* Explicitly set card background */
        border-radius: 20px; /* More rounded */
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15); /* Stronger, softer shadow */
        padding: 45px;
        transition: transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94), box-shadow 0.4s ease;
    }
    .passport-form-card:hover {
        transform: translateY(-5px); /* Lift on hover */
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
    }

    /* 3. Accent Colors and Typography */
    .form-label {
        color: #344767;
        font-weight: 700; /* Bolder labels */
        margin-bottom: 8px;
        transition: color 0.3s ease;
    }
    .form-label:hover {
        color: #00897B; /* Subtle label hover effect */
    }
    
    /* Colored Section Headers */
    .passport-form-card h5 {
        color: #00897B !important; /* Teal accent color for headers */
        font-weight: 800; /* Extra bold */
        font-size: 1.35rem;
        border-bottom: 3px solid #E0F2F1 !important; /* Thicker border */
    }

    /* 4. Input Focus Effect - More pronounced bounce and rounded corners */
    .form-control, .form-select, .flatpickr-input, .select2-selection {
        border-radius: 8px !important; /* Apply to all form elements */
        border: 1px solid #ddd;
        transition: border-color 0.3s, transform 0.3s, box-shadow 0.3s;
    }
    .form-control:focus, .form-select:focus, .flatpickr-input:focus, 
    .input-focused {
        border-color: #4CAF50 !important;
        box-shadow: 0 0 0 0.3rem rgba(76, 175, 80, 0.35) !important;
        transform: scale(1.005); /* Very subtle, smooth scale */
    }
    
    /* Select2 Custom Styling for consistency */
    .select2-container--default .select2-selection--single {
        height: calc(1.5em + 0.75rem + 2px);
        border-radius: 8px !important; 
        padding-top: 0.375rem;
    }

    /* 5. Custom Button Design (Pill shape, lifted shadow) */
    .btn-success {
        background-color: #4CAF50 !important;
        border: none;
        border-radius: 50px; /* Pill shape */
        box-shadow: 0 4px 15px rgba(76, 175, 80, 0.4);
        transition: all 0.3s ease;
    }
    .btn-success:hover {
        background-color: #388E3C !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(76, 175, 80, 0.6);
    }
    .btn-secondary {
        border-radius: 50px;
        transition: all 0.3s ease;
    }
    .btn-secondary:hover {
        transform: translateY(-2px);
    }
    
    /* 6. Initial Fade-in (Existing) */
    .animated-item {
        opacity: 0;
        transform: translateY(15px);
        transition: opacity 0.6s ease-out, transform 0.6s ease-out;
    }
    .animated-item:nth-child(1) { transition-delay: 0.0s; }
    .animated-item:nth-child(2) { transition-delay: 0.1s; }
    .animated-item:nth-child(3) { transition-delay: 0.2s; }
    .animated-item:nth-child(4) { transition-delay: 0.3s; }
    .animated-item:nth-child(5) { transition-delay: 0.4s; }
</style>

<div class="container-fluid py-5">
    <h3 class="mb-5 text-center" style="color: #4CAF50; font-weight: 800; font-size: 2rem;">
        <i class="fas fa-file-invoice me-3"></i> Add New Passport Submission
    </h3>

    <div class="passport-form-card mx-auto" style="max-width: 1100px;">

        @if ($errors->any())
            <div class="alert alert-danger mb-4 animated-item">
                <ul class="mb-0 list-unstyled">
                    @foreach ($errors->all() as $error)
                        <li><i class="fas fa-exclamation-circle me-2"></i> {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('passports.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-4"> {{-- Increased gutter size for structured spacing --}}

                {{-- Group 1: Stakeholders (Agent, Officer, Employee) --}}
                <h5 class="border-bottom pb-2 mt-4 animated-item">
                    <i class="fas fa-handshake me-2"></i> Stakeholder Details
                </h5>
                <div class="col-md-4 animated-item">
                    <label class="form-label">Agent <span class="text-danger">*</span></label>
                    <select name="agent_id" class="form-select" required>
                        <option value="">— Select Agent —</option>
                        {{-- Assuming $agents, $officers, $employees are passed from controller --}}
                        @foreach($agents as $a)
                            <option value="{{ $a->id }}" @selected(old('agent_id')==$a->id)>{{ $a->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 animated-item">
                    <label class="form-label">Passport Officer <span class="text-danger">*</span></label>
                    <select name="passport_officer_id" class="form-select" required>
                        <option value="">— Select Officer —</option>
                        @foreach($officers as $o)
                            <option value="{{ $o->id }}" @selected(old('passport_officer_id')==$o->id)>{{ $o->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 animated-item">
                    <label class="form-label">Passport Received By <span class="text-danger">*</span></label>
                    <select name="employee_id" class="form-select" required>
                        <option value="">— Select Employee —</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" @selected(old('employee_id')==$emp->id)>{{ $emp->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                {{-- Combined Group: Passport Information (Applicant and Document) --}}
                <h5 class="border-bottom pb-2 mt-5 animated-item">
                    <i class="fas fa-address-card me-2"></i> Passport Informations
                </h5>

                {{-- Applicant Personal Details --}}
                <div class="col-md-4 animated-item">
                    <label class="form-label">Applicant Name <span class="text-danger">*</span></label>
                    <input type="text" name="applicant_name" value="{{ old('applicant_name') }}" class="form-control" placeholder="Full Name" required>
                </div>

                <div class="col-md-4 animated-item">
                    <label class="form-label">Phone <span class="text-danger">*</span></label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" placeholder="Contact Number" required>
                </div>
                
                {{-- Nationality Dropdown (Full List) - Now Searchable with Select2 --}}
                <div class="col-md-4 animated-item">
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

                {{-- Applicant Address and DOB --}}
                <div class="col-md-8 animated-item">
                    <label class="form-label">Address <span class="text-danger">*</span></label>
                    <input type="text" name="address" value="{{ old('address') }}" class="form-control" placeholder="Street, City, Postal Code" required>
                </div>

                <div class="col-md-4 animated-item">
                    <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                    {{-- The 'flatpickr-date' class triggers the JS initialization --}}
                    <input type="text" name="date_of_birth" value="{{ old('date_of_birth') }}" class="form-control flatpickr-date" placeholder="Select Date of Birth" required>
                </div>

                {{-- Document Information --}}
                <div class="col-md-4 animated-item">
                    <label class="form-label">Passport Number <span class="text-danger">*</span></label>
                    <input type="text" name="passport_number" value="{{ old('passport_number') }}" class="form-control" placeholder="A1234567" required>
                </div>

                {{-- Issue Date (Flatpickr) --}}
                <div class="col-md-4 animated-item">
                    <label class="form-label">Issue Date <span class="text-danger">*</span></label>
                    <input type="text" name="issue_date" value="{{ old('issue_date') }}" class="form-control flatpickr-date" placeholder="Select Issue Date" required>
                </div>

                {{-- Expiry Date (Flatpickr) --}}
                <div class="col-md-4 animated-item">
                    <label class="form-label">Expiry Date <span class="text-danger">*</span></label>
                    <input type="text" name="expiry_date" value="{{ old('expiry_date') }}" class="form-control flatpickr-date" placeholder="Select Expiry Date" required>
                </div>

                {{-- NID Number (Not required) --}}
                <div class="col-md-12 animated-item">
                    <label class="form-label">NID Number</label>
                    <input type="text" name="nid_number" value="{{ old('nid_number') }}" class="form-control" placeholder="National ID or equivalent (Optional)">
                </div>

                {{-- Notes Section (Not required) --}}
                <div class="col-12 animated-item">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" rows="3" class="form-control" placeholder="Add any relevant notes or special instructions (Optional)">{{ old('notes') }}</textarea>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="mt-5 d-flex justify-content-center animated-item">
                <button type="submit" class="btn btn-lg btn-success me-3" style="min-width: 180px;">
                    <i class="fas fa-check-circle me-2"></i> Save Passport
                </button>
                <a href="{{ route('passports.index') }}" class="btn btn-lg btn-secondary" style="min-width: 180px;">
                    <i class="fas fa-arrow-left me-2"></i> Back to List
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Load jQuery (required by Select2) --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
{{-- Load Flatpickr JS --}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
{{-- Load Select2 JS --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', (event) => {

        // 1. Initialize Flatpickr for all date inputs
        flatpickr(".flatpickr-date", {
            allowInput: true,
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "F j, Y",
            theme: "material_green"
        });

        // 2. Initialize Select2 for Nationality Dropdown
        $('#nationality-select').select2({
            placeholder: "Type or select nationality...",
            allowClear: true,
            width: '100%',
            // Filter the options to show only the top 5 when the dropdown is not searched.
            // This requires modifying the default Select2 behavior, which is complex. 
            // The default Select2 provides the search bar immediately, which is better UX 
            // than showing only 5 options and then searching the rest. 
            // I'm keeping the immediate search for best usability.
        });

        // 3. Dynamic Fade-in Effect
        const animatedItems = document.querySelectorAll('.animated-item');
        animatedItems.forEach(item => {
            item.style.opacity = '1';
            item.style.transform = 'translateY(0)';
        });

        // 4. Input Focus Animation (JS for class management on non-standard inputs)
        // Note: The main focus effect is now handled purely by CSS using :focus, 
        // but this JS is maintained for the Select2 container consistency.
        $('#nationality-select').on('select2:open', function() {
            $('.select2-container').find('.select2-selection--single').addClass('input-focused');
        }).on('select2:close', function() {
            $('.select2-container').find('.select2-selection--single').removeClass('input-focused');
        });
    });
</script>
@endsection