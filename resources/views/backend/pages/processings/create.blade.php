@extends('backend.master')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

<style>
  :root {
    --bg: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --card: linear-gradient(145deg, #9492cdff, #86b2ddff);
    --brand: linear-gradient(135deg, #667eea, #764ba2);
    --brand2: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    --teal: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    --green: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    --orange: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    --danger: linear-gradient(135deg, #ff6b6b, #ee5a24);
    --line: rgba(255,255,255,0.3);
    --muted: #94a3b8;
    --shadow: 0 20px 40px rgba(0,0,0,0.1);
    --shadow-hover: 0 25px 50px rgba(0,0,0,0.15);
  }

  body { 
    background: var(--bg);
    min-height: 100vh;
    font-family: 'Segoe UI', system-ui, sans-serif;
  }

  .container-fluid { 
    max-width: 1200px;
    position: relative;
    z-index: 1;
  }

  /* === Floating Background Elements === */
  body::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: 
      radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
      radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.2) 0%, transparent 50%),
      radial-gradient(circle at 40% 40%, rgba(120, 219, 255, 0.2) 0%, transparent 50%);
    z-index: 0;
  }

  /* === HEADER === */
  .page-head {
    background: linear-gradient(135deg, rgba(255,255,255,0.25), rgba(255,255,255,0.1));
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.17);
    color: white;
    border-radius: 24px;
    padding: 24px 32px;
    display: flex; 
    align-items: center; 
    justify-content: space-between;
    box-shadow: var(--shadow);
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
  }

  .page-head::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
    transition: 0.6s;
  }

  .page-head:hover::before {
    left: 100%;
  }

  .page-head h3 { 
    margin: 0; 
    font-weight: 800; 
    letter-spacing: 1px;
    text-shadow: 0 2px 10px rgba(0,0,0,0.2);
    font-size: 1.8rem;
  }

  .page-head a.btn {
    background: rgba(255,255,255,0.9);
    color: #667eea;
    font-weight: 700; 
    border-radius: 16px;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    padding: 12px 28px;
    border: none;
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    position: relative;
    overflow: hidden;
  }

  .page-head a.btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
    transition: 0.5s;
  }

  .page-head a.btn:hover::before {
    left: 100%;
  }

  .page-head a.btn:hover { 
    transform: translateY(-3px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.2);
    background: white;
  }

  /* === CARDS === */
  .card {
    border: none; 
    border-radius: 24px;
    background: var(--card);
    box-shadow: var(--shadow);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(255,255,255,0.3);
  }

  .card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: var(--brand);
  }

  .card:hover { 
    box-shadow: var(--shadow-hover); 
    transform: translateY(-8px);
  }

  .card-header {
    background: var(--brand);
    color: white; 
    font-weight: 700; 
    font-size: 1.1rem;
    padding: 20px 28px; 
    border-radius: 24px 24px 0 0;
    border: none;
    position: relative;
    overflow: hidden;
  }

  .card-header::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
    transform: translateX(-100%);
  }

  .card:hover .card-header::after {
    animation: shimmer 1.5s infinite;
  }

  @keyframes shimmer {
    100% { transform: translateX(100%); }
  }

  .card-body { 
    padding: 32px; 
    position: relative;
  }

  /* === READONLY AUTO FORM === */
  .readonly {
    background: linear-gradient(135deg, #7ca4cbff, #7ca4cbff);
    padding: 24px;
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,0.5);
    box-shadow: inset 0 2px 10px rgba(0,0,0,0.05);
    position: relative;
  }

  .readonly::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: var(--brand);
    border-radius: 4px 0 0 4px;
  }

  .readonly input, .readonly textarea {
    background: linear-gradient(135deg, #ffffff, #f9fafb) !important;
    border: 1px solid #e2e8f0;
    font-weight: 500;
    color: #1e293b;
    pointer-events: none;
    border-radius: 12px;
    padding: 12px 16px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
  }

  .readonly input:focus, .readonly textarea:focus {
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
  }

  .thumb {
    border-radius: 16px;
    border: 3px solid white;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    transition: all 0.3s ease;
  }

  .thumb:hover {
    transform: scale(1.03);
    box-shadow: 0 15px 35px rgba(0,0,0,0.2);
  }

  /* === INPUTS === */
  .form-label { 
    font-weight: 700; 
    color: #1e293b;
    margin-bottom: 8px;
    display: block;
    position: relative;
  }

  .form-label::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 0;
    height: 2px;
    background: var(--brand);
    transition: width 0.3s ease;
  }

  .form-control:focus + .form-label::after,
  .form-select:focus + .form-label::after {
    width: 100%;
  }

  .form-control, .form-select {
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
    background: white;
    padding: 14px 18px;
    font-size: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.04);
    position: relative;
  }

  .form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2), 0 5px 15px rgba(0,0,0,0.08);
    background: white;
    transform: translateY(-2px);
  }

  /* === CHIPS === */
  .chip-row { 
    display: flex; 
    flex-wrap: wrap; 
    gap: 10px; 
    margin-top: 12px;
  }

  .chip {
    border-radius: 50px; 
    padding: 10px 20px;
    font-size: 13px; 
    font-weight: 700; 
    cursor: pointer;
    border: none;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    position: relative;
    overflow: hidden;
    color: white;
  }

  .chip::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(rgba(255,255,255,0.1), rgba(255,255,255,0));
    opacity: 0;
    transition: opacity 0.3s;
  }

  .chip:hover::before {
    opacity: 1;
  }

  .chip:hover { 
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
  }

  .chip.today { background: var(--brand); }
  .chip.tom { background: var(--teal); }
  .chip.week { background: var(--orange); }
  .chip.clear { background: var(--danger); }

  /* === BUTTONS === */
  .btn-primary {
    background: var(--brand);
    border: none; 
    border-radius: 16px;
    padding: 16px 36px; 
    font-weight: 800;
    font-size: 16px;
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    position: relative;
    overflow: hidden;
    letter-spacing: 0.5px;
  }

  .btn-primary::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: 0.5s;
  }

  .btn-primary:hover::before {
    left: 100%;
  }

  .btn-primary:hover { 
    transform: translateY(-5px) scale(1.02);
    box-shadow: 0 15px 35px rgba(102, 126, 234, 0.5);
  }

  /* === ALERTS === */
  .alert {
    border-radius: 16px;
    border: none;
    box-shadow: var(--shadow);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
  }

  .alert-success {
    background: linear-gradient(135deg, rgba(34, 197, 94, 0.9), rgba(16, 185, 129, 0.9));
    color: white;
  }

  /* === STEP INDICATORS === */
  .step-indicator {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
  }

  .step {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--brand);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-right: 10px;
    box-shadow: 0 4px 10px rgba(102, 126, 234, 0.4);
  }

  .step-line {
    flex-grow: 1;
    height: 3px;
    background: #e2e8f0;
    margin: 0 10px;
    border-radius: 3px;
    position: relative;
    overflow: hidden;
  }

  .step-line::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    width: 0;
    background: var(--brand);
    transition: width 0.5s ease;
  }

  /* === ANIMATIONS === */
  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(30px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .animate-fade-in-up {
    animation: fadeInUp 0.6s ease forwards;
  }

  .card:nth-child(1) { animation-delay: 0.1s; }
  .card:nth-child(2) { animation-delay: 0.2s; }
  .card:nth-child(3) { animation-delay: 0.3s; }

  /* === LOADING EFFECT === */
  .loading-skeleton {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
    border-radius: 8px;
    height: 20px;
    margin-bottom: 10px;
  }

  @keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
  }
</style>

<div class="container-fluid py-4">
  <!-- Header Section -->
  <div class="page-head mb-4 animate__animated animate__fadeInDown">
    <h3 style="color: #1e293b;">✨ Create Processing</h3>
    <a href="{{ route('processings.index') }}" class="btn">
      <i class="fas fa-arrow-left me-2"></i> Back to List
    </a>
  </div>

  {{-- Flash Message --}}
  @if(session('success'))
    <div class="alert alert-success animate__animated animate__bounceIn shadow-lg">{{ session('success') }}</div>
  @endif

  {{-- STEP 1: Select Passport --}}
  <div class="card mb-4 animate-fade-in-up">
    <div class="card-header d-flex align-items-center">
      <div class="step">1</div>
      <span>🔍 Select Passport</span>
    </div>
    <div class="card-body">
      <div class="step-indicator">
        <div class="step">1</div>
        <div class="step-line"></div>
        <div class="step" style="background: #cbd5e1;">2</div>
      </div>
      
      <div class="row g-4">
        <div class="col-md-8">
          <div class="form-group">
            <label class="form-label">Passport Selection</label>
            <select id="passport_id_select" class="form-select" required>
              <option value="">-- Select a Passport --</option>
              @foreach($passports as $p)
                <option value="{{ $p->id }}">{{ $p->passport_number }} — {{ $p->applicant_name }}</option>
              @endforeach
            </select>
            <small class="text-muted mt-2 d-block">Select a passport to automatically load all details below</small>
          </div>
        </div>
        <div class="col-md-4 d-flex align-items-end">
          <div class="w-100">
            <div class="loading-skeleton" style="display: none; height: 40px;" id="loadingPassport"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- STEP 1.5: Auto-loaded Passport Details --}}
  <div id="passportAutoBlock" class="card mb-4" style="display:none;">
    <div class="card-header d-flex align-items-center">
      <div class="step">2</div>
      <span>📄 Passport Details</span>
    </div>
    <div class="card-body readonly animate__animated animate__fadeIn">
      <div class="step-indicator">
        <div class="step" style="background: #22c55e;">✓</div>
        <div class="step-line" style="background: #22c55e;"></div>
        <div class="step">2</div>
      </div>
      
      <div class="row g-4">
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
        <div class="col-md-6">
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
        <div class="col-md-4">
          <label class="form-label">Passport Number</label>
          <input id="f_passport_no" class="form-control" readonly>
        </div>
        
        <div class="col-md-4">
          <label class="form-label">Issue Date</label>
          <input id="f_issue" class="form-control" readonly>
        </div>
        <div class="col-md-4">
          <label class="form-label">Expiry Date</label>
          <input id="f_expiry" class="form-control" readonly>
        </div>
        <div class="col-md-6">
          <label class="form-label">NID Number</label>
          <input id="f_nid" class="form-control" readonly>
        </div>
        
        <div class="col-md-6">
          <label class="form-label">Notes</label>
          <textarea id="f_notes" class="form-control" rows="2" readonly></textarea>
        </div>
        
        <div class="col-12" id="picWrap" style="display:none;">
          <label class="form-label">Passport Picture</label><br>
          <img id="f_picture" class="thumb" alt="Passport Picture" style="max-width: 200px; height: auto;">
        </div>
      </div>
    </div>
  </div>

  {{-- STEP 2: Processing Details --}}
  <form method="POST" action="{{ route('processings.store') }}" id="processingForm">
    @csrf
    <input type="hidden" name="passport_id" id="passport_id_hidden">

    <div class="card mb-4 animate-fade-in-up">
      <div class="card-header d-flex align-items-center">
        <div class="step">3</div>
        <span>⚙️ Processing Details</span>
      </div>
      <div class="card-body">
        <div class="step-indicator">
          <div class="step" style="background: #22c55e;">✓</div>
          <div class="step-line" style="background: #22c55e;"></div>
          <div class="step" style="background: #22c55e;">✓</div>
          <div class="step-line"></div>
          <div class="step">3</div>
        </div>
        
        <div class="row g-4">
          <div class="col-md-4">
            <label class="form-label">Employee</label>
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
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
              <option value="PENDING">PENDING</option>
              <option value="IN_PROGRESS">IN_PROGRESS</option>
              <option value="DONE">DONE</option>
              <option value="REJECTED">REJECTED</option>
            </select>
          </div>
          
          <div class="col-md-6">
            <label class="form-label">Processing Date & Time</label>
            <input type="text" id="processed_at_view" class="form-control" placeholder="Select date & time" required>
            <input type="hidden" name="processed_at" id="processed_at">
            
            <div class="chip-row">
              <button type="button" class="chip today" data-add="0">
                <i class="fas fa-calendar-day me-1"></i> Today
              </button>
              <button type="button" class="chip tom" data-add="1">
                <i class="fas fa-sun me-1"></i> Tomorrow
              </button>
              <button type="button" class="chip week" data-add="7">
                <i class="fas fa-calendar-week me-1"></i> +7 days
              </button>
              <button type="button" class="chip clear" id="clearProcDate">
                <i class="fas fa-times me-1"></i> Clear
              </button>
            </div>
          </div>
          
          <div class="col-12">
            <label class="form-label">Processing Notes</label>
            <textarea name="notes" class="form-control" rows="3" placeholder="Any additional notes about this processing..."></textarea>
          </div>
        </div>
      </div>
    </div>

    <div class="text-center py-4">
      <button type="submit" class="btn btn-primary btn-lg">
        <i class="fas fa-save me-2"></i> Save Processing
      </button>
    </div>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
  // === AJAX AUTO-LOAD ===
  const sel = document.getElementById('passport_id_select');
  const block = document.getElementById('passportAutoBlock');
  const hid = document.getElementById('passport_id_hidden');
  const loadingPassport = document.getElementById('loadingPassport');
  
  sel.addEventListener('change', async function() {
    const id = this.value; 
    hid.value = id || ''; 
    
    if (!id) {
      block.style.display = 'none';
      return;
    }
    
    try {
      // Show loading
      loadingPassport.style.display = 'block';
      block.style.display = 'none';
      
      const url = "{{ route('processings.passport.details', ':id') }}".replace(':id', id);
      const res = await fetch(url);
      
      if (!res.ok) throw new Error('Failed to load passport details');
      const d = await res.json();
      
      // Hide loading and show block with animation
      loadingPassport.style.display = 'none';
      
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
      
      // Show with animation
      block.style.display = 'block';
      block.classList.remove('animate__fadeIn');
      void block.offsetWidth; // Trigger reflow
      block.classList.add('animate__fadeIn');
      
    } catch(e) {
      loadingPassport.style.display = 'none';
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

  document.querySelectorAll('.chip[data-add]').forEach(ch => {
    ch.addEventListener('click', () => setDatePlus(parseInt(ch.dataset.add, 10)));
  });

  document.getElementById('clearProcDate').addEventListener('click', () => {
    fp.clear(); 
    ph.value = ''; 
    pv.value = '';
  });

  // Form submission animation
  document.getElementById('processingForm').addEventListener('submit', function(e) {
    const btn = this.querySelector('button[type="submit"]');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Saving...';
    btn.disabled = true;
  });
</script>
@endsection