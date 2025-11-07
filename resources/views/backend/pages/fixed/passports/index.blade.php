@extends('backend.master')

@section('content')
{{-- Flatpickr for dynamic date range --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
  :root{
    --bg:#f4f7fb;
    --card:#ffffff;
    --ink:#0f172a;
    --muted:#6b7280;
    --line:#e6eaf0;

    /* Brand palette (tweak if you like) */
    --brand:#0ea5e9;           /* cyan */
    --brand-2:#10b981;         /* green */
    --brand-3:#8b5cf6;         /* violet */
    --accent:#f59e0b;          /* amber */
    --danger:#ef4444;          /* red */
  }

  /* Light page background */
  body{ background: var(--bg); }

  /* Page header */
  .page-head{
    border-radius:18px;
    padding:22px 24px;
    color:#fff;
    background: radial-gradient(120% 180% at 0% 0%, rgba(255,255,255,.15) 0%, transparent 40%),
                linear-gradient(110deg, var(--brand) 0%, var(--brand-2) 55%, var(--brand-3) 100%);
    box-shadow: 0 14px 35px rgba(2,8,20,.12);
  }
  .page-head h3{ margin:0; font-weight:900; letter-spacing:.3px }

  /* “Glass” card container */
  .glass{
    background: var(--card);
    border:1px solid var(--line);
    border-radius:16px;
    box-shadow: 0 10px 24px rgba(0,0,0,.06);
  }

  /* Filter header */
  .filter-title{
    font-weight:800; letter-spacing:.2px; color:var(--ink);
  }

  /* Fancy inputs */
  .field{
    position:relative;
  }
  .field .form-label{
    font-weight:700; color:var(--ink); font-size:.92rem; margin-bottom:6px;
  }
  .fx,
  .input-group .form-control{
    border-radius:12px !important;
    border:1px solid var(--line);
    background:#fbfdff;
    transition: box-shadow .2s ease, transform .12s ease, border-color .2s ease, background-color .2s ease;
  }
  .fx:focus,
  .input-group .form-control:focus,
  .select-fx:focus{
    background:#fff;
    box-shadow: 0 0 0 .21rem rgba(14,165,233,.18);
    border-color:#b9e6ff;
    transform: translateY(-1px);
  }
  .input-group-text{
    border-radius:12px 0 0 12px !important;
    border:1px solid var(--line);
    background: linear-gradient(180deg,#f0f7ff 0%,#e9f5ff 100%);
    color:#0b6aa0;
    font-weight:700;
  }
  .select-fx{
    appearance: none;
    background: linear-gradient(180deg,#fbfdff 0%,#f6fbff 100%);
  }

  /* Preset pill buttons */
  .preset{
    border:1px dashed #dbe6f4; background:#fff;
    color:#374151; padding:8px 12px; border-radius:999px; font-size:.85rem;
    transition: all .2s ease; font-weight:600;
  }
  .preset:hover{ background:#f0f7ff }
  .preset.active{ background:#e6fbf3; border-color:#c6f0dc; color:#0b683f }

  /* Apply button */
  .btn-apply{
    background: linear-gradient(135deg, #0ea5e9, #8b5cf6);
    color:#fff; border:none; font-weight:800; letter-spacing:.2px;
    border-radius:12px;
    box-shadow:0 12px 28px rgba(139,92,246,.25);
  }
  .btn-apply:hover{ filter:brightness(.97) }

  /* Table style */
  .table thead th{
    position:sticky; top:0; z-index:5;
    color:#fff; background:#0b1220; border-color:#0b1220;
  }
  .table tbody tr{
    transition: transform .16s ease, background-color .25s ease, box-shadow .16s ease;
    animation: fadeRow .55s ease both;
  }
  .table tbody tr:hover{
    background:#f7fbff; transform: translateY(-1px);
    box-shadow:0 8px 18px rgba(0,0,0,.04);
  }
  @keyframes fadeRow{ from{opacity:0; transform:translateY(6px)} to{opacity:1; transform:translateY(0)} }
  .addr{ max-width:280px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
  .lock-row{ background:#fafafa; }

  /* Badges more vibrant */
  .badge.bg-info{ background:#38bdf8 !important }
  .badge.bg-success{ background:#22c55e !important }
  .badge.bg-warning{ background:#fbbf24 !important }
  .badge.bg-primary{ background:#6366f1 !important }
  .badge.bg-danger{ background:#ef4444 !important }

  /* Responsive */
  @media (max-width: 991px){
    .addr{ max-width:180px }
  }
</style>

<div class="container-fluid py-4">

  {{-- Page header --}}
  <div class="page-head d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <h3>Passports</h3>
    <a href="{{ route('passports.create') }}" class="btn btn-light fw-bold rounded-3">
      <i class="fas fa-plus"></i> Add Passport
    </a>
  </div>

  {{-- Flash messages --}}
  @if(session('success')) <div class="alert alert-success glass">{{ session('success') }}</div> @endif
  @if(session('error'))   <div class="alert alert-danger glass">{{ session('error') }}</div>   @endif

  {{-- FILTERS CARD --}}
  <div class="glass p-3 p-md-4 mb-4">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
      <div class="filter-title">Filter &amp; Search</div>
      <a href="{{ route('passports.index') }}" class="btn btn-outline-secondary btn-sm rounded-3">
        <i class="fas fa-undo"></i> Reset
      </a>
    </div>

    <form id="filtersForm" method="GET" action="{{ route('passports.index') }}" class="row g-3 align-items-end">
      {{-- hidden Y-m-d values that controller reads --}}
      <input type="hidden" name="date_from" id="date_from" value="{{ $dateFrom ?? '' }}">
      <input type="hidden" name="date_to" id="date_to" value="{{ $dateTo ?? '' }}">

      {{-- Search --}}
      <div class="col-12 col-xl-3 field">
        <label class="form-label">Search</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-search"></i></span>
          <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control fx"
                 placeholder="Passport No / Name / Phone / Address / NID">
        </div>
      </div>

      {{-- Status --}}
      <div class="col-6 col-xl-2 field">
        <label class="form-label">Status</label>
        <select name="status" class="form-select select-fx fx">
          <option value="" {{ ($status??'')===''?'selected':'' }}>All</option>
          <option value="RECEIVED_FROM_AGENT"   {{ ($status??'')==='RECEIVED_FROM_AGENT'?'selected':'' }}>Received from Agent</option>
          <option value="COLLECTED_FROM_AGENCY" {{ ($status??'')==='COLLECTED_FROM_AGENCY'?'selected':'' }}>Collected from Agency</option>
          <option value="IN_PROCESS"            {{ ($status??'')==='IN_PROCESS'?'selected':'' }}>In Process</option>
        </select>
      </div>

      {{-- Agent --}}
      <div class="col-6 col-xl-2 field">
        <label class="form-label">Agent</label>
        <select name="agent_id" class="form-select select-fx fx">
          <option value="" {{ ($agentId??'')===''?'selected':'' }}>All</option>
          @isset($agents)
            @foreach($agents as $a)
              <option value="{{ $a->id }}" {{ ($agentId??'')==$a->id?'selected':'' }}>{{ $a->name }}</option>
            @endforeach
          @endisset
        </select>
      </div>

      {{-- Officer --}}
      <div class="col-6 col-xl-2 field">
        <label class="form-label">Officer</label>
        <select name="officer_id" class="form-select select-fx fx">
          <option value="" {{ ($offId??'')===''?'selected':'' }}>All</option>
          @isset($officers)
            @foreach($officers as $o)
              <option value="{{ $o->id }}" {{ ($offId??'')==$o->id?'selected':'' }}>{{ $o->name }}</option>
            @endforeach
          @endisset
        </select>
      </div>

      {{-- Employee --}}
      <div class="col-6 col-xl-2 field">
        <label class="form-label">Employee</label>
        <select name="employee_id" class="form-select select-fx fx">
          <option value="" {{ ($empId??'')===''?'selected':'' }}>All</option>
          @isset($employees)
            @foreach($employees as $e)
              <option value="{{ $e->id }}" {{ ($empId??'')==$e->id?'selected':'' }}>{{ $e->name }}</option>
            @endforeach
          @endisset
        </select>
      </div>

      {{-- Sort --}}
      <div class="col-6 col-xl-2 field">
        <label class="form-label">Sort</label>
        <select name="sort_by" class="form-select select-fx fx">
          <option value="latest"  {{ ($sortBy??'latest')==='latest'?'selected':'' }}>Latest</option>
          <option value="oldest"  {{ ($sortBy??'latest')==='oldest'?'selected':'' }}>Oldest</option>
          <option value="name_az" {{ ($sortBy??'latest')==='name_az'?'selected':'' }}>Name A→Z</option>
          <option value="name_za" {{ ($sortBy??'latest')==='name_za'?'selected':'' }}>Name Z→A</option>
        </select>
      </div>

      {{-- Per page --}}
      <div class="col-6 col-xl-2 field">
        <label class="form-label">Per Page</label>
        <select name="per_page" class="form-select select-fx fx">
          @foreach([10,25,50,100] as $pp)
            <option value="{{ $pp }}" {{ (int)($perPage??25)===$pp?'selected':'' }}>{{ $pp }}</option>
          @endforeach
        </select>
      </div>

      {{-- Date Range --}}
      <div class="col-12 col-xl-3 field">
        <label class="form-label">Date Range</label>
        <input type="text" id="dateRange" class="form-control fx"
               placeholder="Select range"
               value="{{ ($dateFrom ?? null) && ($dateTo ?? null) ? \Carbon\Carbon::parse($dateFrom)->format('d M Y').' to '.\Carbon\Carbon::parse($dateTo)->format('d M Y') : '' }}">
      </div>

      {{-- Preset quick range --}}
      <div class="col-12">
<div class="d-flex flex-wrap gap-2">
  <button type="button" class="preset" data-preset="today">Today</button>
  <button type="button" class="preset" data-preset="7">Last 7 days</button>
  <button type="button" class="preset" data-preset="30">Last 30 days</button>
  <button type="button" class="preset" data-preset="month">This month</button>
  <button type="button" id="clearDate" class="preset" style="border-style:solid">Clear date</button>
</div>

      </div>

      {{-- Apply button --}}

      <div class="col-12">
        <button type="submit" class="btn btn-apply px-4 py-2">
          <i class="fas fa-filter"></i> Apply
        </button>
      </div>
    </form>
  </div>

  {{-- TABLE CARD --}}
  <div class="glass p-2 p-md-3">
    <div class="table-responsive">
      <table class="table table-bordered align-middle text-center mb-0">
        <thead class="table-dark">
          <tr>
            <th style="width:60px">SL</th>
            <th>Passport No</th>
            <th>Name</th>
            <th>Date of Birth</th>
            <th>Agent</th>
            <th>Officer</th>
            <th>Employee</th>
            <th>Status</th>
            <th>Processing Status</th>
            <th>Date</th>
            <th style="width:250px">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($passports as $idx => $p)
            @php
              $processing = \App\Models\PassportProcessing::with('agency')
                  ->where('passport_id', $p->id)->latest('id')->first();
              $collection = \App\Models\PassportCollection::where('passport_id', $p->id)
                  ->latest('id')->first();
              $locked = (bool)($processing || $collection);

              if ($collection) {
                $statusText  = 'Collected from ' . ($processing?->agency?->name ?? 'Agency');
                $statusClass = 'bg-success';
              } else {
                $statusText  = $p->status ?? 'RECEIVED_FROM_AGENT';
                $statusClass = match($p->status) {
                    'RECEIVED_FROM_AGENT'    => 'bg-info',
                    'COLLECTED_FROM_AGENCY'  => 'bg-success',
                    'IN_PROCESS'             => 'bg-warning text-dark',
                    default                  => 'bg-secondary'
                };
              }

              $procStatus = $processing?->status;
              $procBadge  = match($procStatus) {
                'PENDING'      => 'bg-secondary',
                'IN_PROGRESS'  => 'bg-warning text-dark',
                'DONE'         => 'bg-primary',
                'REJECTED'     => 'bg-danger',
                default        => 'bg-light text-muted'
              };
              $procLabel = $procStatus
                ? (($procStatus === 'IN_PROGRESS') ? 'On Going' : ucwords(strtolower($procStatus)))
                : '—';
            @endphp

            <tr class="{{ $locked ? 'lock-row' : '' }}">
              <td>{{ ($passports->currentPage()-1)*$passports->perPage() + $loop->iteration }}</td>
              <td class="fw-semibold">{{ $p->passport_number }}</td>
              <td class="text-start"><strong>{{ $p->applicant_name }}</strong></td>
              <td>{{ optional($p->date_of_birth)->format('d M Y') ?? '—' }}</td>
              <td>{{ $p->agent?->name ?? '—' }}</td>
              <td>{{ $p->passportOfficer?->name ?? '—' }}</td>
              <td>{{ $p->employee?->name ?? '—' }}</td>
              <td><span class="badge {{ $statusClass }}">{{ $statusText }}</span></td>
              <td><span class="badge {{ $procBadge }}">{{ $procLabel }}</span></td>
              <td>{{ optional($p->created_at)->timezone(config('app.timezone','Asia/Dhaka'))->format('d M Y, h:i A') }}</td>
              <td>
                <div class="d-flex justify-content-center gap-1 flex-wrap">
                  <a href="{{ route('passports.show', $p->id) }}" class="btn btn-sm btn-info rounded-3">
                    <i class="fas fa-eye"></i> View
                  </a>
                  <a href="{{ route('passports.edit', $p->id) }}" class="btn btn-sm btn-warning rounded-3">
                    <i class="fas fa-edit"></i> Edit
                  </a>
                  @if($locked)
                    <button type="button" class="btn btn-sm btn-secondary rounded-3"
                            data-bs-toggle="tooltip"
                            title="This passport cannot be deleted — it’s already {{ $collection ? 'collected' : 'in processing' }}.">
                      <i class="fas fa-lock"></i> Locked
                    </button>
                  @else
                    <a href="{{ route('passports.delete', $p->id) }}"
                       class="btn btn-sm btn-danger rounded-3"
                       onclick="return confirm('Are you sure you want to delete this passport?')">
                      <i class="fas fa-trash-alt"></i> Delete
                    </a>
                  @endif
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="11" class="text-center text-muted py-4">
                <i class="far fa-folder-open"></i> No passports found.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
      <div class="text-muted small">
        Showing
        @if($passports->total()) {{ $passports->firstItem() }}–{{ $passports->lastItem() }} @else 0 @endif
        of {{ $passports->total() }} results
      </div>
      <div>{{ $passports->onEachSide(1)->links() }}</div>
    </div>
  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Enable tooltips
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

    // Date range picker setup
    const df = document.getElementById('date_from');
    const dt = document.getElementById('date_to');
    const fp = flatpickr("#dateRange", {
      mode: "range",
      dateFormat: "d M Y",
      onChange(selected){
        if (selected.length === 2){
          const iso = d => new Date(d.getTime() - d.getTimezoneOffset()*60000).toISOString().slice(0,10);
          df.value = iso(selected[0]);
          dt.value = iso(selected[1]);
        }
      }
    });

    // Preset buttons
    function setRange(start, end){
      fp.setDate([start, end], true);
      const iso = d => new Date(d.getTime() - d.getTimezoneOffset()*60000).toISOString().slice(0,10);
      df.value = iso(start); dt.value = iso(end);
      document.getElementById('filtersForm').submit();
    }

    const today = new Date(); today.setHours(0,0,0,0);
    document.querySelectorAll('.preset').forEach(btn=>{
      btn.addEventListener('click', ()=>{
        document.querySelectorAll('.preset').forEach(b=>b.classList.remove('active'));
        btn.classList.add('active');
        const val = btn.dataset.preset;
        if (val === 'today'){
          setRange(new Date(today), new Date(today));
        } else if (val === 'month'){
          setRange(new Date(today.getFullYear(), today.getMonth(), 1),
                   new Date(today.getFullYear(), today.getMonth()+1, 0));
        } else {
          const n = parseInt(val,10);
          const start = new Date(today); start.setDate(today.getDate() - (n-1));
          setRange(start, new Date(today));
        }
      });
    });

    // ✅ Clear date range safely (ADD THIS HERE)
    const clearBtn = document.getElementById('clearDate');
    if (clearBtn) {
      clearBtn.addEventListener('click', ()=>{
        const dr = document.getElementById('dateRange');
        if (dr && dr._flatpickr) dr._flatpickr.clear();
        if (df) df.value = '';
        if (dt) dt.value = '';
        document.getElementById('filtersForm').submit();
      });
    }
  });
</script>



@endsection
