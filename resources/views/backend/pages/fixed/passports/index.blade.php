@extends('backend.master')

@section('page-title', 'Passports')
@section('page-subtitle', 'Registry of every application received')

@section('content')
{{-- ============================================================
     ডিজাইন নোট (পাসপোর্ট লিস্টে যা কাজ হয়েছে):
     • পুরনো রংধনু গ্র্যাডিয়েন্ট হেডার ও লাইট থিমের ইনলাইন CSS পুরো বাদ।
     • নতুন ডার্ক থিমে পেজ হেডার + "Add Passport" বাটন।
     • ফিল্টার কার্ড (সার্চ, স্ট্যাটাস, এজেন্ট, অফিসার, কর্মী, সর্ট, তারিখ) আগের ফিচার অক্ষুণ্ণ।
     • তারিখের প্রিসেট চিপ (Today / 7 days / 30 days / This month) নতুন স্টাইলে।
     • টেবিল ডার্ক কার্ডে, সফট ব্যাজ, রো হোভার; খালি অবস্থায় সুন্দর empty-state।
     • ফ্ল্যাটপিকর ও প্রিসেটের JS আগের মতোই কাজ করে।
     ============================================================ --}}

{{-- Flatpickr for dynamic date range --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<div class="container-fluid px-0">

  {{-- Page header --}}
  <div class="page-head d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div>
      <h3>Passports</h3>
      <p class="ph-desc">Every application from agent handover to delivery.</p>
    </div>
    @if (can_do('passports.create'))
      <a href="{{ route('passports.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Add Passport
      </a>
    @endif
  </div>

  {{-- Flash messages --}}
  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
  @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div>   @endif

  {{-- FILTERS CARD --}}
  <div class="card p-3 p-md-4 mb-3">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
      <div class="section-title mb-0"><i class="fas fa-sliders"></i> Filter &amp; Search</div>
      <a href="{{ route('passports.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-rotate-left me-1"></i> Reset
      </a>
    </div>

    <form id="filtersForm" method="GET" action="{{ route('passports.index') }}" class="row g-3 align-items-end">
      {{-- hidden Y-m-d values that controller reads --}}
      <input type="hidden" name="date_from" id="date_from" value="{{ $dateFrom ?? '' }}">
      <input type="hidden" name="date_to" id="date_to" value="{{ $dateTo ?? '' }}">

      {{-- Search --}}
      <div class="col-12 col-xl-3">
        <label class="form-label">Search</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-search"></i></span>
          <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control fx"
                 placeholder="Passport No / Name / Phone / Address / NID">
        </div>
      </div>

      {{-- Status --}}
      <div class="col-6 col-xl-2">
        <label class="form-label">Status</label>
        <select name="status" class="form-select select-fx fx">
          <option value="" {{ ($status??'')===''?'selected':'' }}>All</option>
          <option value="RECEIVED_FROM_AGENT"   {{ ($status??'')==='RECEIVED_FROM_AGENT'?'selected':'' }}>Received from Agent</option>
          <option value="COLLECTED_FROM_AGENCY" {{ ($status??'')==='COLLECTED_FROM_AGENCY'?'selected':'' }}>Collected from Agency</option>
          <option value="IN_PROCESS"            {{ ($status??'')==='IN_PROCESS'?'selected':'' }}>In Process</option>
        </select>
      </div>

      {{-- Agent --}}
      <div class="col-6 col-xl-2">
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
      <div class="col-6 col-xl-2">
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
      <div class="col-6 col-xl-2">
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
      <div class="col-6 col-xl-2">
        <label class="form-label">Sort</label>
        <select name="sort_by" class="form-select select-fx fx">
          <option value="latest"  {{ ($sortBy??'latest')==='latest'?'selected':'' }}>Latest</option>
          <option value="oldest"  {{ ($sortBy??'latest')==='oldest'?'selected':'' }}>Oldest</option>
          <option value="name_az" {{ ($sortBy??'latest')==='name_az'?'selected':'' }}>Name A→Z</option>
          <option value="name_za" {{ ($sortBy??'latest')==='name_za'?'selected':'' }}>Name Z→A</option>
        </select>
      </div>

      {{-- Per page --}}
      <div class="col-6 col-xl-2">
        <label class="form-label">Per Page</label>
        <select name="per_page" class="form-select select-fx fx">
          @foreach([10,25,50,100] as $pp)
            <option value="{{ $pp }}" {{ (int)($perPage??25)===$pp?'selected':'' }}>{{ $pp }}</option>
          @endforeach
        </select>
      </div>

      {{-- Date Range --}}
      <div class="col-12 col-xl-3">
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
          <button type="button" id="clearDate" class="preset">Clear date</button>
        </div>
      </div>

      {{-- Apply button --}}
      <div class="col-12">
        <button type="submit" class="btn btn-apply px-4">
          <i class="fas fa-filter me-1"></i> Apply Filters
        </button>
      </div>
    </form>
  </div>

  {{-- TABLE CARD --}}
  <div class="card p-2 p-md-3">
    <div class="table-responsive">
      <table class="table table-bordered align-middle text-center mb-0">
        <thead>
          <tr>
            <th style="width:56px">SL</th>
            <th>Passport No</th>
            <th>Name</th>
            <th>Date of Birth</th>
            <th>Agent</th>
            <th>Officer</th>
            <th>Employee</th>
            <th>Status</th>
            <th>Processing</th>
            <th>Date</th>
            <th style="width:240px">Actions</th>
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
                    'IN_PROCESS'             => 'bg-warning',
                    default                  => 'bg-secondary'
                };
              }

              $procStatus = $processing?->status;
              $procBadge  = match($procStatus) {
                'PENDING'      => 'bg-secondary',
                'IN_PROGRESS'  => 'bg-warning',
                'DONE'         => 'bg-primary',
                'REJECTED'     => 'bg-danger',
                default        => 'bg-light'
              };
              $procLabel = $procStatus
                ? (($procStatus === 'IN_PROGRESS') ? 'On Going' : ucwords(strtolower($procStatus)))
                : '—';
            @endphp

            <tr class="{{ $locked ? 'lock-row' : '' }}">
              <td>{{ ($passports->currentPage()-1)*$passports->perPage() + $loop->iteration }}</td>
              <td class="fw-semibold">{{ $p->passport_number }}</td>
              <td class="text-start fw-semibold">{{ $p->applicant_name }}</td>
              <td>{{ optional($p->date_of_birth)->format('d M Y') ?? '—' }}</td>
              <td>{{ $p->agent?->name ?? '—' }}</td>
              <td>{{ $p->passportOfficer?->name ?? '—' }}</td>
              <td>{{ $p->employee?->name ?? '—' }}</td>
              <td><span class="badge {{ $statusClass }}">{{ $statusText }}</span></td>
              <td><span class="badge {{ $procBadge }}">{{ $procLabel }}</span></td>
              <td>{{ optional($p->created_at)->timezone(config('app.timezone','Asia/Dhaka'))->format('d M Y, h:i A') }}</td>
              <td>
                <div class="d-flex justify-content-center gap-1 flex-wrap">
                  <a href="{{ route('passports.show', $p->id) }}" class="btn btn-sm btn-info">
                    <i class="fas fa-eye"></i> View
                  </a>
                  @if (can_do('passports.edit'))
                    <a href="{{ route('passports.edit', $p->id) }}" class="btn btn-sm btn-warning">
                      <i class="fas fa-pen"></i> Edit
                    </a>
                  @endif
                  @if($locked || !can_do('passports.delete'))
                    <button type="button" class="btn btn-sm btn-secondary"
                            data-bs-toggle="tooltip"
                            title="{{ $locked ? 'This passport cannot be deleted — it’s already ' . ($collection ? 'collected' : 'in processing') . '.' : 'You do not have delete permission.' }}">
                      <i class="fas fa-lock"></i> Locked
                    </button>
                  @else
                    <a href="{{ route('passports.delete', $p->id) }}"
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Are you sure you want to delete this passport?')">
                      <i class="fas fa-trash-can"></i> Delete
                    </a>
                  @endif
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="11">
                <div class="empty-state">
                  <div class="es-icon"><i class="fas fa-passport"></i></div>
                  <div class="fw-semibold mb-1" style="color:var(--text-soft)">No passports found</div>
                  <div>Try adjusting the filters, or add a new passport to get started.</div>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-3 px-1 d-flex justify-content-between align-items-center flex-wrap gap-2">
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
@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
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

    // Clear date range
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
@endpush
@endsection
