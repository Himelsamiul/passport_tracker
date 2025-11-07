@extends('backend.master')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
  :root{
    --ink:#0f172a; --muted:#6b7280; --line:#e5e7eb; --shade:#f8fafc;
    --brand:#12824a; --brand2:#0ea5e9;
  }

  /* Header */
  .page-head{
    background: linear-gradient(135deg, var(--brand), var(--brand2));
    color:#fff; border-radius:16px; padding:18px 22px;
    box-shadow: 0 14px 30px rgba(0,0,0,.08);
  }
  .page-head h3{ margin:0; font-weight:900; letter-spacing:.2px; }
  .kpi-badge{ background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.35); }

  /* Cards / fields */
  .glass{
    background: rgba(255,255,255,.85);
    backdrop-filter: blur(12px);
    border:1px solid rgba(17,24,39,.06);
    border-radius:14px;
    box-shadow:0 12px 28px rgba(2,8,20,.06);
  }
  .filter-title{ font-weight:800; color:var(--ink); }
  .fx{
    transition: box-shadow .2s ease, transform .12s ease, border-color .2s ease;
    border-color: #e7eaf0 !important;
  }
  .fx:focus{
    box-shadow: 0 0 0 .2rem rgba(18,130,74,.15);
    transform: translateY(-1px);
    border-color:#bfe3cf !important;
  }
  .input-group-text{ background:#fff }
  .select-fx{ background:#fff; }

  /* Quick presets */
  .preset{
    border:1px dashed #dfe4ea; background:#fff; color:#374151;
    padding:6px 10px; border-radius:10px; font-size:12px;
    transition: all .2s ease;
  }
  .preset:hover{ background:#f1f5f9; }
  .preset.active{ background:#e8f5ee; border-color:#c7e6d2; color:#115e43; }

  /* Table */
  .table thead th{
    position:sticky; top:0; z-index:2; background:#0b1220; color:#fff; border-color:#0b1220;
  }
  .table tbody tr{
    transition: transform .16s ease, background-color .25s ease, box-shadow .16s ease;
    animation: fadeRow .55s ease both;
  }
  .table tbody tr:hover{ background:#f9fafb; transform: translateY(-1px); box-shadow:0 8px 20px rgba(0,0,0,.05); }
  @keyframes fadeRow{ from{opacity:0; transform:translateY(6px)} to{opacity:1; transform:translateY(0)} }

  .addr{ max-width:340px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
  .lock-row{ background:#fafafa; }

  @media (max-width: 768px){
    .addr{ max-width:180px }
  }
</style>

<div class="container-fluid py-4">
  <!-- Header -->
  <div class="page-head d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <h3>Agents</h3>
    <div class="d-flex align-items-center gap-2 flex-wrap">
      <span class="badge kpi-badge text-white">All: {{ $totalAll }}</span>
      <span class="badge kpi-badge text-white">Used: {{ $totalUsed }}</span>
      <span class="badge kpi-badge text-white">Unused: {{ $totalUnused }}</span>
      <a href="{{ route('agents.create') }}" class="btn btn-light fw-bold">
        <i class="fas fa-plus"></i> Add Agent
      </a>
    </div>
  </div>

  {{-- Flash --}}
  @if(session('success')) <div class="alert alert-success glass">{{ session('success') }}</div> @endif
  @if(session('error'))   <div class="alert alert-danger glass">{{ session('error') }}</div>   @endif

  <!-- FILTERS -->
  <div class="glass p-3 p-md-4 mb-3">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
      <div class="filter-title">Filter &amp; Search</div>
      <a href="{{ route('agents.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-undo"></i> Reset
      </a>
    </div>

    <form id="filtersForm" method="GET" action="{{ route('agents.index') }}" class="row g-3 align-items-end">
      {{-- hidden fields the controller uses --}}
      <input type="hidden" name="date_from" id="date_from" value="{{ $dateFrom }}">
      <input type="hidden" name="date_to" id="date_to" value="{{ $dateTo }}">

      <!-- Search -->
      <div class="col-12 col-lg-4">
        <label class="form-label mb-1">Search</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-search"></i></span>
          <input type="text" name="search" value="{{ $search }}" id="searchInput"
                 class="form-control fx" placeholder="Name / Phone / Email / Address" list="searchHints">
        </div>
        <datalist id="searchHints">
          @foreach($namesForDatalist as $n) <option value="{{ $n }}"></option> @endforeach
          @foreach($phonesForDatalist as $p) <option value="{{ $p }}"></option> @endforeach
          @foreach($emailsForDatalist as $e) <option value="{{ $e }}"></option> @endforeach
        </datalist>
      </div>

      <!-- Status -->
      <div class="col-6 col-lg-2">
        <label class="form-label mb-1">Status</label>
        <select name="used" class="form-select select-fx fx auto-submit">
          <option value="" {{ $used===''?'selected':'' }}>All</option>
          <option value="used" {{ $used==='used'?'selected':'' }}>Used (Locked)</option>
          <option value="unused" {{ $used==='unused'?'selected':'' }}>Unused (Deletable)</option>
        </select>
      </div>

      <!-- Sort -->
      <div class="col-6 col-lg-2">
        <label class="form-label mb-1">Sort</label>
        <select name="sort_by" class="form-select select-fx fx auto-submit">
          <option value="latest"  {{ $sortBy==='latest'?'selected':'' }}>Latest</option>
          <option value="oldest"  {{ $sortBy==='oldest'?'selected':'' }}>Oldest</option>
          <option value="name_az" {{ $sortBy==='name_az'?'selected':'' }}>Name A→Z</option>
          <option value="name_za" {{ $sortBy==='name_za'?'selected':'' }}>Name Z→A</option>
        </select>
      </div>

      <!-- Per page -->
      <div class="col-6 col-lg-2">
        <label class="form-label mb-1">Per Page</label>
        <select name="per_page" class="form-select select-fx fx auto-submit">
          @foreach([10,25,50,100] as $pp)
            <option value="{{ $pp }}" {{ (int)$perPage===$pp?'selected':'' }}>{{ $pp }}</option>
          @endforeach
        </select>
      </div>

      <!-- Date Range (dynamic) -->
      <div class="col-6 col-lg-2">
        <label class="form-label mb-1">Date Range</label>
        <input type="text" id="dateRange" class="form-control fx" placeholder="Select range"
               value="{{ $dateFrom && $dateTo ? \Carbon\Carbon::parse($dateFrom)->format('d M Y').' to '.\Carbon\Carbon::parse($dateTo)->format('d M Y') : '' }}">
      </div>

      <!-- Quick presets (one-click set range) -->
      <div class="col-12">
        <div class="d-flex flex-wrap gap-2">
          <button type="button" class="preset" data-preset="today">Today</button>
          <button type="button" class="preset" data-preset="7">Last 7 days</button>
          <button type="button" class="preset" data-preset="30">Last 30 days</button>
          <button type="button" class="preset" data-preset="month">This month</button>
        </div>
      </div>

      <!-- Actions (ONLY one Apply + Reset link above) -->
      <div class="col-12">
        <button type="submit" class="btn btn-dark">
          <i class="fas fa-filter"></i> Apply
        </button>
      </div>
    </form>
  </div>

  <!-- TABLE -->
  <div class="glass p-2 p-md-3">
    <div class="table-responsive">
      <table class="table table-bordered align-middle mb-0">
        <thead>
          <tr>
            <th style="width:60px">#</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Address</th>
            <th>Created At</th>
            <th style="width:230px">Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($agents as $agent)
            @php $isUsed = $usedAgentIds->contains($agent->id); @endphp
            <tr class="{{ $isUsed ? 'lock-row' : '' }}">
              <td>{{ ($agents->currentPage()-1)*$agents->perPage() + $loop->iteration }}</td>
              <td class="fw-semibold">
                {{ $agent->name }}
                @if($isUsed)
                  <span class="badge text-bg-light ms-1"><i class="fas fa-link"></i> linked</span>
                @endif
              </td>
              <td><span class="badge text-bg-light"><i class="fas fa-phone"></i> {{ $agent->phone }}</span></td>
              <td>
                @if($agent->email)
                  <span class="badge text-bg-light"><i class="fas fa-envelope"></i> {{ $agent->email }}</span>
                @else
                  <span class="text-muted">—</span>
                @endif
              </td>
              <td><div class="addr">{{ $agent->address ?: '—' }}</div></td>
              <td>{{ $agent->created_at?->format('d M Y, h:i A') }}</td>
              <td class="d-flex flex-wrap gap-2">
                <a href="{{ route('agents.edit', $agent->id) }}" class="btn btn-sm btn-warning">
                  <i class="fas fa-edit"></i> Edit
                </a>

                @if($isUsed)
                  <button type="button" class="btn btn-sm btn-secondary"
                          data-bs-toggle="tooltip" data-bs-placement="top"
                          title="You can’t delete this agent — already linked with other records.">
                    <i class="fas fa-lock"></i> Locked
                  </button>
                @else
                  <a href="{{ route('agents.delete', $agent->id) }}"
                     class="btn btn-sm btn-danger"
                     onclick="return confirm('Are you sure you want to delete this agent?')">
                    <i class="fas fa-trash-alt"></i> Delete
                  </a>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center text-muted py-4">
                <i class="far fa-folder-open"></i> No agents found.
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
        @if($agents->total()) {{ $agents->firstItem() }}–{{ $agents->lastItem() }} @else 0 @endif
        of {{ $agents->total() }} results
      </div>
      <div>{{ $agents->onEachSide(1)->links() }}</div>
    </div>
  </div>
</div>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Tooltips
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

    // Auto-submit on selects
    document.querySelectorAll('.auto-submit').forEach(el => {
      el.addEventListener('change', () => document.getElementById('filtersForm').submit());
    });

    // Flatpickr (range) -> fills hidden Y-m-d inputs
    const df = document.getElementById('date_from');
    const dt = document.getElementById('date_to');
    const fp = flatpickr("#dateRange", {
      mode: "range",
      dateFormat: "d M Y",
      altInput: false,
      onChange(selected){
        if (selected.length === 2){
          const asISO = d => new Date(d.getTime() - d.getTimezoneOffset()*60000).toISOString().slice(0,10);
          df.value = asISO(selected[0]);
          dt.value = asISO(selected[1]);
        }
      }
    });

    // Quick preset buttons
    function setRange(start, end){
      fp.setDate([start, end], true); // update input visually
      const toISO = d => new Date(d.getTime() - d.getTimezoneOffset()*60000).toISOString().slice(0,10);
      df.value = toISO(start);
      dt.value = toISO(end);
      // Submit immediately (no AJAX)
      document.getElementById('filtersForm').submit();
    }

    const today = new Date(); today.setHours(0,0,0,0);
    const presets = document.querySelectorAll('.preset');
    presets.forEach(btn=>{
      btn.addEventListener('click', ()=>{
        presets.forEach(b=>b.classList.remove('active'));
        btn.classList.add('active');
        const val = btn.dataset.preset;
        if (val === 'today'){
          setRange(new Date(today), new Date(today));
        } else if (val === 'month'){
          const start = new Date(today.getFullYear(), today.getMonth(), 1);
          const end   = new Date(today.getFullYear(), today.getMonth()+1, 0);
          setRange(start, end);
        } else {
          // last N days
          const n = parseInt(val, 10);
          const start = new Date(today); start.setDate(today.getDate() - (n-1));
          const end   = new Date(today);
          setRange(start, end);
        }
      });
    });
  });
</script>
@endsection
