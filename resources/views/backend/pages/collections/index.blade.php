@extends('backend.master')

@section('page-title', 'Passport Collections')
@section('page-subtitle', 'Handover records for delivered passports')

@section('content')
{{-- ============================================================
     ডিজাইন নোট (কলেকশন লিস্টে যা কাজ হয়েছে):
     • নীল গ্র্যাডিয়েন্ট হেডার/লাইট থিম বাদ — নতুন ডার্ক থিম।
     • ফিল্টার কার্ড: সার্চ, স্ট্যাটাস, কর্মী, এজেন্সি, তারিখ রেঞ্জ, সর্ট, পার-পেজ — সব আগের মতো।
     • SweetAlert ডিলিট কনফার্মেশন ও Flatpickr আগের লজিকেই চলে।
     ============================================================ --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

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
    $agencies = $agencies ?? \App\Models\Agency::orderBy('name')->get(['id','name']);
  } catch (\Throwable $e) {
    $agencies = collect();
  }

  // Helper for latest processing on a passport (already eager-loaded)
  $getLatestProcessing = function($passport) {
      if (!$passport || !$passport->relationLoaded('processings')) return null;
      return $passport->processings->sortByDesc('id')->first();
  };

  // Map statuses from both naming schemes
  $procBadge = function($st) {
      $upper = strtoupper((string)$st);
      return match($upper) {
          'PENDING'   => 'bg-secondary',
          'IN_PROGRESS','ONGOING','ON GOING' => 'bg-warning',
          'DONE','APPROVED' => 'bg-success',
          'REJECTED'  => 'bg-danger',
          default     => 'bg-light'
      };
  };
@endphp

<div class="container-fluid px-0">

  {{-- Header --}}
  <div class="page-head d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div>
      <h3>Passport Collections</h3>
      <p class="ph-desc">Records of passports handed over to their owners.</p>
    </div>
    @if (can_do('collections.create'))
      <a href="{{ route('collections.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> New Collection
      </a>
    @endif
  </div>

  {{-- Flash --}}
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  {{-- Filters --}}
  <form method="GET" action="{{ route('collections.index') }}" class="card p-3 p-md-4 mb-3" id="filtersForm">
    <div class="section-title mb-3"><i class="fas fa-sliders"></i> Filter</div>
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
        <div class="input-group">
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
        <button class="btn btn-apply px-4" type="submit"><i class="fas fa-filter me-1"></i> Apply</button>
        <a href="{{ route('collections.index') }}" class="btn btn-reset px-4"><i class="fas fa-rotate-left me-1"></i> Reset</a>
      </div>
    </div>
  </form>

  {{-- Table --}}
  <div class="card p-2 p-md-3">
    <div class="table-responsive">
      <table class="table table-bordered align-middle text-center">
        <thead>
          <tr>
            <th>SL</th>
            <th>Passport Holder</th>
            <th>Passport Number</th>
            <th>Agent</th>
            <th>Agency</th>
            <th>Status</th>
            <th>Received By</th>
            <th>Collected At</th>
            <th style="min-width:190px;">Actions</th>
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
              <td>
                <div class="d-flex justify-content-center gap-1">
                  <a href="{{ route('collections.show', $c->id) }}" class="btn btn-sm btn-info">
                    <i class="fas fa-eye"></i> View
                  </a>
                  @if (can_do('collections.delete'))
                    <button type="button" class="btn btn-sm btn-danger btn-del" data-id="{{ $c->id }}">
                      <i class="fas fa-trash-can"></i> Delete
                    </button>
                    <form id="del-{{ $c->id }}" action="{{ route('collections.destroy', $c->id) }}" method="POST" class="d-none">
                      @csrf @method('DELETE')
                    </form>
                  @endif
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9">
                <div class="empty-state">
                  <div class="es-icon"><i class="fas fa-box-open"></i></div>
                  <div class="fw-semibold mb-1" style="color:var(--text-soft)">No collections found</div>
                  <div>Try adjusting the filters or record a new collection.</div>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-3 px-1 d-flex justify-content-between align-items-center flex-wrap gap-2">
      <div class="text-muted small">
        Showing
        @if($collections->total()) {{ $collections->firstItem() }}–{{ $collections->lastItem() }} @else 0 @endif
        of {{ $collections->total() }} results
      </div>
      <div>{{ $collections->appends(request()->query())->links() }}</div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@push('scripts')
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
        confirmButtonColor: '#4e86e8',
        cancelButtonColor: '#3a465e',
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
@endpush
@endsection
