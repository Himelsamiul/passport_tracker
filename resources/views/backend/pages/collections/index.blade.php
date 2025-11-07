@extends('backend.master')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.min.css">

@php
  // Safe defaults pulled from the request if controller didn't pass them
  $search   = $search   ?? request('q', '');
  $status   = $status   ?? request('status', '');
  $employeeId = $employeeId ?? request('employee_id', '');
  $agencyId   = $agencyId   ?? request('agency_id', '');
  $dateFrom = $dateFrom ?? request('date_from', '');
  $dateTo   = $dateTo   ?? request('date_to', '');
  $perPage  = $perPage  ?? (int) request('per_page', $collections->perPage() ?? 20);
  $sortBy   = $sortBy   ?? request('sort_by', 'latest');

  // Try to get employees/agencies if not provided by controller
  try {
    $employees = $employees ?? \App\Models\Employee::orderBy('name')->get(['id','name']);
  } catch (\Throwable $e) {
    $employees = collect();
  }
  try {
    // Adjust model name if yours is different (e.g., ProcessingAgency)
    $agencies = $agencies ?? \App\Models\Agency::orderBy('name')->get(['id','name']);
  } catch (\Throwable $e) {
    $agencies = collect();
  }

  // Helper for latest processing on a passport (already eager-loaded)
  $getLatestProcessing = function($passport) {
      // processings relation is loaded by controller ->with('passport.processings.agency')
      if (!$passport || !$passport->relationLoaded('processings')) return null;
      return $passport->processings->sortByDesc('id')->first();
  };

  // Map statuses from both naming schemes
  $procBadge = function($st) {
      $upper = strtoupper((string)$st);
      return match($upper) {
          'PENDING'   => 'bg-secondary',
          'IN_PROGRESS','ONGOING','ON GOING' => 'bg-warning text-dark',
          'DONE','APPROVED' => 'bg-success',
          'REJECTED'  => 'bg-danger',
          default     => 'bg-dark'
      };
  };
@endphp

<style>
  :root{
    --bg:#eef3fb;
    --ink:#0f172a;
    --muted:#64748b;
    --line:#dbe2ef;
    --brand:#2563eb;
    --brand2:#06b6d4;
    --green:#22c55e;
    --amber:#f59e0b;
    --red:#ef4444;
  }

  body{ background:var(--bg); }
  .container-fluid{ max-width:1180px; }

  /* Header */
  .page-head{
    background:linear-gradient(110deg,var(--brand),var(--brand2));
    color:#fff;border-radius:18px;padding:16px 22px;display:flex;
    align-items:center;justify-content:space-between;gap:10px;
    box-shadow:0 12px 24px rgba(37,99,235,.25);
  }
  .page-head h3{ margin:0; font-weight:900; }
  .page-head .btn{
    background:#fff;color:var(--brand);border:none;border-radius:12px;padding:8px 18px;font-weight:800;
  }
  .page-head .btn:hover{ background:#eaf2ff }

  /* Filter card */
  .filters{
    background:#fff;border-radius:18px;box-shadow:0 10px 24px rgba(2,8,20,.08);
    padding:18px 18px 14px;margin:18px 0;
  }
  .filters .form-label{ font-weight:700;font-size:13px;color:var(--ink); }
  .filters .form-control,.filters .form-select{
    border:1px solid var(--line);border-radius:12px;background:#fafcff;
    transition:.15s ease;
  }
  .filters .form-control:focus,.filters .form-select:focus{
    border-color:#93c5fd;box-shadow:inset 4px 0 0 0 var(--brand),0 0 0 3px rgba(147,197,253,.25);
  }
  .btn-apply{
    background:linear-gradient(90deg,var(--brand),var(--brand2));border:none;border-radius:12px;
    color:#fff;font-weight:900;
  }
  .btn-reset{
    background:#f1f5f9;border:none;border-radius:12px;color:#111;font-weight:900;
  }

  /* Date range control */
  .daterange .input-group-text{ background:#f8fafc;border:1px solid var(--line);border-right:none;border-radius:12px 0 0 12px; }
  .daterange input{ border-left:none;border-radius:0 12px 12px 0; }

  /* Table */
  .table-wrap{ border-radius:18px;overflow:auto;border:1px solid var(--line); }
  table.table{ background:#e9f3ff; }
  thead th{
    position:sticky;top:0;background:#1f2937!important;color:#fff!important;border:none;z-index:2;
  }
  tbody tr{ background:#fff; transition:.18s ease; }
  tbody tr:nth-child(odd){ background:#f9fbff; }
  tbody tr:hover{ background:#eaf1ff; }
  th,td{ white-space:nowrap;vertical-align:middle;padding:10px 12px; }
  .badge{ font-size:12px;padding:6px 10px;border-radius:10px; }
  .actions .btn{ padding:4px 10px;font-size:12px;border-radius:10px; }

  /* SweetAlert */
  .swal2-popup{ border-radius:16px!important; }
</style>

<div class="container-fluid py-3">

  {{-- Header --}}
  <div class="page-head">
    <h3>Passport Collections</h3>
    <a href="{{ route('collections.create') }}" class="btn"><i class="fas fa-plus"></i> New Collection</a>
  </div>

  {{-- Flash --}}
  @if(session('success'))
    <div class="alert alert-success mt-3">{{ session('success') }}</div>
  @endif

  {{-- Filters --}}
  <form method="GET" action="{{ route('collections.index') }}" class="filters" id="filtersForm">
    <div class="row g-3 align-items-end">
      <div class="col-md-3">
        <label class="form-label">Search</label>
        <input type="text" name="q" value="{{ $search }}" class="form-control" placeholder="Passport No / Applicant">
      </div>

      <div class="col-md-2">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
          <option value="">All</option>
          @foreach(['PENDING','IN_PROGRESS','DONE','REJECTED','APPROVED'] as $opt)
            <option value="{{ $opt }}" {{ strtoupper($status)===$opt ? 'selected' : '' }}>{{ $opt }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-md-2">
        <label class="form-label">Employee</label>
        <select name="employee_id" class="form-select">
          <option value="">All</option>
          @foreach($employees as $e)
            <option value="{{ $e->id }}" {{ (string)$employeeId===(string)$e->id ? 'selected' : '' }}>{{ $e->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-md-2">
        <label class="form-label">Agency</label>
        <select name="agency_id" class="form-select">
          <option value="">All</option>
          @foreach($agencies as $a)
            <option value="{{ $a->id }}" {{ (string)$agencyId===(string)$a->id ? 'selected' : '' }}>{{ $a->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-md-3">
        <label class="form-label">Collected At (Range)</label>
        <div class="input-group daterange">
          <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
          <input type="text" id="dateRange" class="form-control" placeholder="Select date range">
          <input type="hidden" name="date_from" id="date_from" value="{{ $dateFrom }}">
          <input type="hidden" name="date_to" id="date_to" value="{{ $dateTo }}">
        </div>
      </div>

      <div class="col-md-2">
        <label class="form-label">Sort</label>
        <select name="sort_by" class="form-select">
          <option value="latest"  {{ $sortBy==='latest' ? 'selected' : '' }}>Latest</option>
          <option value="oldest"  {{ $sortBy==='oldest' ? 'selected' : '' }}>Oldest</option>
          <option value="name_az" {{ $sortBy==='name_az' ? 'selected' : '' }}>Name A→Z</option>
          <option value="name_za" {{ $sortBy==='name_za' ? 'selected' : '' }}>Name Z→A</option>
        </select>
      </div>

      <div class="col-md-2">
        <label class="form-label">Per Page</label>
        <select name="per_page" class="form-select">
          @foreach([10,20,25,50,100] as $pp)
            <option value="{{ $pp }}" {{ (int)$perPage===$pp ? 'selected' : '' }}>{{ $pp }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-md-8 d-flex gap-2">
        <button class="btn btn-apply" type="submit"><i class="fas fa-filter"></i> Apply</button>
        <a href="{{ route('collections.index') }}" class="btn btn-reset">Reset</a>
      </div>
    </div>
  </form>

  {{-- Table --}}
  <div class="table-wrap">
    <div class="table-responsive">
      <table class="table table-bordered align-middle text-center">
        <thead class="table-dark">
          <tr>
            <th>SL</th>
            <th>Passport Holder</th>
            <th>Passport Number</th>
            <th>Agent</th>
            <th>Agency</th>
            <th>Status</th>
            <th>Received By</th>
            <th>Collected At</th>
            <th style="min-width:180px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($collections as $i => $c)
            @php
              $latestProcessing = $getLatestProcessing($c->passport);
              $agencyName = optional($latestProcessing?->agency)->name ?? '—';
              $statusText = $latestProcessing?->status ?? '—';
              $statusClass = $procBadge($statusText);
            @endphp
            <tr>
              <td>{{ $collections->firstItem() + $i }}</td>
              <td class="text-start fw-semibold">{{ $c->passport->applicant_name ?? '—' }}</td>
              <td>{{ $c->passport->passport_number ?? '—' }}</td>
              <td>{{ $c->passport->agent->name ?? '—' }}</td>
              <td>{{ $agencyName }}</td>
              <td><span class="badge {{ $statusClass }}">{{ strtoupper($statusText) }}</span></td>
              <td>{{ $c->employee->name ?? '—' }}</td>
              <td>
                @if($c->collected_at)
                  <span class="badge bg-primary">
                    <i class="far fa-calendar-alt"></i>
                    {{ \Carbon\Carbon::parse($c->collected_at)->format('d M Y, h:i A') }}
                  </span>
                @else
                  <span class="badge bg-secondary">—</span>
                @endif
              </td>
              <td class="actions">
                <a href="{{ route('collections.show', $c->id) }}" class="btn btn-sm btn-info">
                  <i class="fas fa-eye"></i> View
                </a>
                <button type="button" class="btn btn-sm btn-danger btn-del" data-id="{{ $c->id }}">
                  <i class="fas fa-trash-alt"></i> Delete
                </button>
                <form id="del-{{ $c->id }}" action="{{ route('collections.destroy', $c->id) }}" method="POST" class="d-none">
                  @csrf @method('DELETE')
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="9" class="text-muted">No collections found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-3">
    {{ $collections->appends(request()->query())->links() }}
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  // Date range -> sets hidden fields
  (function(){
    const dr = document.getElementById('dateRange');
    const df = document.getElementById('date_from');
    const dt = document.getElementById('date_to');
    if(dr){
      flatpickr(dr,{
        mode:"range",
        dateFormat:"d M Y",
        defaultDate: (df.value && dt.value) ? [df.value, dt.value] : null,
        onChange:(sel)=>{
          if(sel.length===2){
            const iso = d => new Date(d.getTime()-d.getTimezoneOffset()*60000).toISOString().slice(0,10);
            df.value = iso(sel[0]); dt.value = iso(sel[1]);
          }
        }
      });
    }
  })();

  // SweetAlert delete
  document.querySelectorAll('.btn-del').forEach(btn=>{
    btn.addEventListener('click', ()=>{
      const id = btn.dataset.id;
      Swal.fire({
        title: 'Delete this collection?',
        text: 'This will remove the record and revert the passport status.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, delete it',
        cancelButtonText: 'Cancel'
      }).then(res=>{
        if(res.isConfirmed){
          document.getElementById('del-'+id).submit();
        }
      });
    });
  });
</script>
@endsection
