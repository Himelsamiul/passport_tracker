@extends('backend.master')

@section('content')
{{-- Load jQuery and Select2 for enhanced dropdown search --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
    /* 1. Global Background */
    body {
        background-color: #f5f7fa !important; /* Soft light background */
    }

    /* 2. Main Card Styling */
    .collection-form-card {
        background-color: #ffffff;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        padding: 40px;
        margin-top: 20px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .collection-form-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }

    /* 3. Input Styling and Focus/Hover */
    .form-control, .form-select, .select2-selection {
        border-radius: 10px !important;
        border: 1px solid #e0e0e0;
        padding: 0.75rem 1rem;
        transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }
    .form-control:focus, .form-select:focus, .input-focused {
        border-color: #00796B !important; /* Teal focus color */
        box-shadow: 0 0 0 0.25rem rgba(0, 121, 107, 0.25) !important;
        transform: scale(1.005);
    }
    /* Select2 specific styling */
    .select2-container--default .select2-selection--single {
        height: auto;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        padding-top: 5px;
        padding-bottom: 5px;
    }

    /* 4. Header and Title */
    .main-title {
        color: #00796B; /* Teal header color */
        font-weight: 700;
        font-size: 2rem;
    }
    .card-header {
        font-weight: 700 !important;
        border-top-left-radius: 15px !important;
        border-top-right-radius: 15px !important;
        padding: 0.8rem 1.25rem;
    }
    .card-body {
        padding: 1.5rem;
    }

    /* 5. Key-Value Preview Boxes */
    .kv-box {
        background: #f8f9fa; /* Lighter background for kv boxes */
        border: 1px solid #e9ecef;
        border-radius: 8px; /* Slightly less rounded than the main card */
        padding: 0.9rem 1rem;
        height: 100%;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.05);
        transition: background 0.2s, border-color 0.3s, background-color 0.3s; /* Added transitions for dynamic effects */
    }
    .kv-box:hover {
        background: #e9ecef;
    }
    .kv .label {
        font-size: 11px;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: .05em;
        margin-bottom: 3px;
    }
    .kv .value {
        font-weight: 700;
        font-size: 15px;
        color: #343a40;
    }

    /* --- DYNAMIC DATE STYLING --- */
    .bg-danger-subtle { background-color: #fcebeb !important; }
    .text-danger { color: #d9534f !important; }
    .border-danger { border-color: #d9534f !important; }

    .bg-warning-subtle { background-color: #fff8e1 !important; }
    .text-warning { color: #f0ad4e !important; }
    .border-warning { border-color: #f0ad4e !important; }
    /* ---------------------------- */
    
    /* --- COLLECTION DATE INPUT ENHANCEMENT --- */
    .date-input-wrapper {
        border-radius: 12px;
        background-color: #e0f7fa; /* Very light cyan background */
        border: 2px solid #00BCD4; /* Teal border for focus */
        padding: 5px;
        box-shadow: 0 4px 10px rgba(0, 188, 212, 0.2);
        transition: all 0.3s ease;
    }
    .date-input-wrapper:hover {
        box-shadow: 0 6px 15px rgba(0, 188, 212, 0.4);
    }
    .date-input-wrapper .form-control {
        border: none;
        background-color: transparent; /* Make the input itself transparent */
        font-weight: 600;
        color: #00796B;
        padding-left: 10px;
        padding-right: 10px;
        height: 100%; /* Ensure full height within wrapper */
        line-height: 1.5;
    }
    /* Tweak for the calendar icon on supported browsers */
    .date-input-wrapper .form-control::-webkit-calendar-picker-indicator {
        opacity: 0.9;
        cursor: pointer;
        padding: 5px;
        margin-right: -5px;
        color: #00796B;
        font-size: 1.2rem;
    }
    /* ------------------------------------------- */

    /* 6. Button Styling */
    .btn-success {
        background-color: #00796B !important;
        border-color: #00796B !important;
        border-radius: 50px;
        padding: 0.6rem 1.8rem;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(0, 121, 107, 0.4);
        transition: all 0.3s ease;
    }
    .btn-success:hover {
        background-color: #004D40 !important;
        border-color: #004D40 !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 121, 107, 0.6);
    }
    .btn-outline-secondary {
        border-radius: 50px;
        padding: 0.6rem 1.8rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-outline-secondary:hover {
        transform: translateY(-2px);
    }

    /* 7. Animated Fade-in (for professional load) */
    .animated-section {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.7s ease-out, transform 0.7s ease-out;
    }
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 animated-section">
        <h3 class="mb-0 main-title"><i class="fas fa-handshake me-2"></i> New Passport Collection</h3>
        <a href="{{ route('collections.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i> Back
        </a>
    </div>

    <div class="collection-form-card animated-section">
        <form action="{{ route('collections.store') }}" method="POST">
            @csrf

            <div class="row g-4">
                {{-- Form Fields --}}
                <div class="col-lg-6">
                    <label class="form-label fw-semibold">Select Passport</label>
                    <select id="passportSelect" name="passport_id" class="form-select" required>
                        <option value="">-- Choose passport ready to collect --</option>
                        {{-- Data attributes now hold all necessary passport info as JSON --}}
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
                    <label class="form-label fw-semibold">Collected By (Employee)</label>
                    <select name="employee_id" class="form-select" required>
                        <option value="">-- Select employee --</option>
                        @foreach($employees as $e)
                            <option value="{{ $e->id }}">{{ $e->name }}</option>
                        @endforeach
                    </select>
                    @error('employee_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- COLLECTION DATE & TIME FIELD (DYNAMIC DESIGN) --}}
                <div class="col-lg-3">
                    <label class="form-label fw-semibold">Collection Date & Time</label>
                    <div class="date-input-wrapper">
                        <input type="datetime-local" name="collected_at" class="form-control" required value="{{ now()->format('Y-m-d\TH:i') }}">
                    </div>
                    @error('collected_at') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Notes <span class="text-muted">(Optional)</span></label>
                    <textarea name="notes" rows="2" class="form-control" placeholder="Any remarks or special instructions..."></textarea>
                </div>
            </div>

            {{-- Auto-preview panel (mirrors your Processing view style) --}}
            <div id="preview" class="mt-5 animated-section" style="display:none; transition-delay: 0.1s;">
                <h5 class="mb-3 text-muted fw-bold text-uppercase">Passport Preview</h5>

                {{-- Processing Information --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header text-white" style="background-color: #00BCD4 !important;">
                        <i class="fas fa-chart-line me-2"></i> Processing Information
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="kv kv-box">
                                    <div class="label">Status</div>
                                    <div class="value" id="pv-status"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="kv kv-box">
                                    <div class="label">Employee (Picked Up)</div>
                                    <div class="value" id="pv-emp"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="kv kv-box">
                                    <div class="label">Agency</div>
                                    <div class="value" id="pv-agency"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="kv kv-box" id="kv-created">
                                    <div class="label">Created</div>
                                    <div class="value" id="pv-created"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Stakeholders --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header text-white" style="background-color: #4CAF50 !important;">
                        <i class="fas fa-user-tie me-2"></i> Stakeholders
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="kv kv-box">
                                    <div class="label">Agent</div>
                                    <div class="value" id="pv-agent"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="kv kv-box">
                                    <div class="label">Passport Officer</div>
                                    <div class="value" id="pv-officer"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="kv kv-box">
                                    <div class="label">Received By (Original)</div>
                                    <div class="value" id="pv-received"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Passport Details --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-header text-white" style="background-color:#7c3aed;">
                        <i class="fas fa-passport me-2"></i> Applicant Details
                    </div>
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
                                {{-- Expiry Date Box with Dynamic ID --}}
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

            <div class="mt-5 d-flex gap-3 justify-content-center animated-section" style="transition-delay: 0.2s;">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="fas fa-truck-loading me-2"></i> Finalize Collection
                </button>
            </div>
        </form>
    </div>
</div>


@push('scripts')
<script>
    // Utility function to safely set inner HTML for dynamic fields
    // Use innerText for non-date fields for better security against XSS
    const pv = (id, v) => document.getElementById(id).innerText = (v && v !== 'null' && v !== 'N/A' && v !== '') ? v : '— N/A —';
    const pvHtml = (id, html) => {
        const el = document.getElementById(id);
        if (el) el.innerHTML = html;
    };
    
    // Function to format date dynamically and apply conditional styling for expiry
    const formatDynamicDate = (dateString, type = 'default') => {
        if (!dateString) return { html: '— N/A —', boxClass: '' };

        try {
            const date = new Date(dateString);
            if (isNaN(date.getTime())) return { html: dateString, boxClass: '' };

            // Base format: DayOfWeek, Month Day, Year
            const formattedDate = date.toLocaleDateString('en-US', {
                weekday: 'short',
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });

            let output = `<span class="fw-bold">${formattedDate}</span>`;
            let boxClass = '';
            let icon = `<i class="fas fa-calendar-alt me-1 text-primary"></i>`; // Default date icon

            if (type === 'created') {
                // For creation date, add time
                const formattedTime = date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
                output = `<span class="fw-bold">${formattedDate}</span><small class="d-block text-muted mt-1">@ ${formattedTime}</small>`;
                icon = `<i class="fas fa-clock me-1 text-info"></i>`;
            }
            
            if (type === 'dob') {
                icon = `<i class="fas fa-baby me-1 text-success"></i>`;
            }
            
            if (type === 'issue') {
                icon = `<i class="fas fa-file-signature me-1 text-info"></i>`;
            }

            if (type === 'expiry') {
                const today = new Date();
                // Normalize dates to midnight to ensure accurate day calculation
                today.setHours(0, 0, 0, 0);
                date.setHours(0, 0, 0, 0);
                
                const timeDifference = date.getTime() - today.getTime();
                const daysDifference = Math.ceil(timeDifference / (1000 * 60 * 60 * 24));
                const absDays = Math.abs(daysDifference);

                if (daysDifference < 0) {
                    // Expired (Past date)
                    boxClass = 'bg-danger-subtle border-danger';
                    output = `<span class="fw-bold text-danger">${formattedDate}</span><small class="d-block text-danger mt-1">EXPIRED (${absDays} days ago)</small>`;
                    icon = `<i class="fas fa-times-circle me-1 text-danger"></i>`;
                } else if (daysDifference <= 180) {
                    // Expiring soon (6 months or less)
                    boxClass = 'bg-warning-subtle border-warning';
                    output = `<span class="fw-bold text-warning">${formattedDate}</span><small class="d-block text-warning mt-1">Expires in ${daysDifference} days</small>`;
                    icon = `<i class="fas fa-exclamation-triangle me-1 text-warning"></i>`;
                } else {
                    // Good (More than 6 months)
                    icon = `<i class="fas fa-check-circle me-1 text-success"></i>`;
                }
            }

            // Return structured data: combined icon and output HTML, plus conditional box class
            return { html: `<div class="d-flex align-items-center">${icon}<div>${output}</div></div>`, boxClass: boxClass };

        } catch (e) {
            console.error("Date formatting error:", e);
            return { html: dateString, boxClass: '' };
        }
    };


    document.addEventListener('DOMContentLoaded', () => {
        const sel = document.getElementById('passportSelect');
        const preview = document.getElementById('preview');

        // Initialize Select2 on the Passport Select dropdown
        $(sel).select2({
            placeholder: "-- Choose passport ready to collect --",
            allowClear: true,
            width: '100%'
        });

        // Dynamic Fade-in for the entire page content
        document.querySelectorAll('.animated-section').forEach((item, index) => {
            item.style.opacity = '1';
            item.style.transform = 'translateY(0)';
            item.style.transitionDelay = `${index * 0.1}s`;
        });

        // Input Focus Animation (Select2 consistency)
        $(sel).on('select2:open', function() {
            $('.select2-container').find('.select2-selection--single').addClass('input-focused');
        }).on('select2:close', function() {
            $('.select2-container').find('.select2-selection--single').removeClass('input-focused');
        });
        
        // Custom focus handling for native inputs
        document.querySelectorAll('.form-control, .form-select').forEach(input => {
            input.addEventListener('focus', function() {
                this.classList.add('input-focused');
                
                // Add focus style to the custom wrapper for the date input
                if (input.closest('.date-input-wrapper')) {
                    input.closest('.date-input-wrapper').style.boxShadow = '0 0 0 0.25rem rgba(0, 188, 212, 0.4)';
                    input.closest('.date-input-wrapper').style.borderColor = '#00BCD4';
                }
            });
            input.addEventListener('blur', function() {
                this.classList.remove('input-focused');

                // Remove focus style from the custom wrapper
                if (input.closest('.date-input-wrapper')) {
                    input.closest('.date-input-wrapper').style.boxShadow = '0 4px 10px rgba(0, 188, 212, 0.2)';
                    input.closest('.date-input-wrapper').style.borderColor = '#00BCD4';
                }
            });
        });


        // Handle selection change
        $(sel).on('change', function() {
            const selectedOption = $(this).find(':selected');
            
            // Using .attr('data-info') to reliably fetch the raw JSON string
            const dataInfoString = selectedOption.attr('data-info'); 

            if (!dataInfoString) {
                preview.style.display = 'none';
                return;
            }

            // Parse the JSON data from the data-info attribute
            let data;
            try {
                data = JSON.parse(dataInfoString);
            } catch (e) {
                console.error("--- JSON PARSE ERROR ---");
                console.error("Failed to parse data for selected passport. Check server-side escaping.");
                console.error("Raw string:", dataInfoString);
                console.error("Error:", e);
                
                preview.innerHTML = `<div class="alert alert-danger" role="alert">Error loading passport details. Check console for details.</div>`;
                preview.style.display = 'block';
                return;
            }

            // --- 1. SET NON-DATE FIELDS ---
            // Processing Information
            pv('pv-status', data.status);
            pv('pv-emp', data.processing_employee);
            pv('pv-agency', data.processing_agency);

            // Stakeholders
            pv('pv-agent', data.agent_name);
            pv('pv-officer', data.passport_officer);
            pv('pv-received', data.received_by_original);

            // Passport details
            pv('pv-name', data.applicant_name);
            pv('pv-number', data.passport_number);
            pv('pv-nationality', data.nationality);
            pv('pv-phone', data.phone);
            pv('pv-address', data.address);
            pv('pv-nid', data.nid_number);

            // --- 2. SET DYNAMIC DATE FIELDS ---
            const createdInfo = formatDynamicDate(data.created_at, 'created');
            const dobInfo = formatDynamicDate(data.date_of_birth, 'dob');
            const issueInfo = formatDynamicDate(data.issue_date, 'issue');
            const expiryInfo = formatDynamicDate(data.expiry_date, 'expiry');

            // Apply HTML content
            pvHtml('pv-created', createdInfo.html);
            pvHtml('pv-dob', dobInfo.html);
            pvHtml('pv-issue', issueInfo.html);
            pvHtml('pv-expiry', expiryInfo.html);
            
            // --- 3. APPLY CONDITIONAL STYLING TO EXPIRY BOX ---
            const expiryBox = document.getElementById('kv-expiry');
            if (expiryBox) {
                // List of possible dynamic classes to remove before adding new ones
                const dynamicClasses = ['bg-danger-subtle', 'border-danger', 'bg-warning-subtle', 'border-warning'];
                
                // Clear previous dynamic classes
                expiryBox.classList.remove(...dynamicClasses);
                
                // Add new class if provided
                if (expiryInfo.boxClass) {
                    expiryBox.classList.add(...expiryInfo.boxClass.split(' '));
                }
            }


            preview.style.display = 'block';

            // Optional: Scroll to preview section for better UX
            preview.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });
</script>
@endpush
@endsection