@extends('backend.master')

@section('page-title', 'Processing Report')
@section('page-subtitle', 'Processing activity across all passports')

@section('content')
{{-- ============================================================
     ডিজাইন নোট (প্রসেসিং রিপোর্টে যা কাজ হয়েছে):
     • নতুন ডার্ক থিমে পেজ হেডার + সামারি চিপ (Total/Pending/In-Progress/Done/Rejected)।
     • ফিল্টার কার্ড ও টেবিল কার্ড; সব ফিল্টার/পেজিনেশন লজিক অপরিবর্তিত।
     ============================================================ --}}
<div class="container-fluid px-0">

  {{-- Header + summary chips --}}
  <div class="page-head d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div>
      <h3>Processing Report</h3>
      <p class="ph-desc">Every processing entry with its current outcome.</p>
    </div>
    <div class="d-flex flex-wrap gap-2 align-items-center">
      <span class="badge bg-secondary">Total: {{ $totals['total'] }}</span>
      <span class="badge bg-warning">Pending: {{ $totals['pending'] }}</span>
      <span class="badge bg-info">In Progress: {{ $totals['in_progress'] }}</span>
      <span class="badge bg-success">Done: {{ $totals['done'] }}</span>
      <span class="badge bg-danger">Rejected: {{ $totals['rejected'] }}</span>

      {{-- Downloads keep the current filters --}}
      <div class="d-flex gap-2 ms-md-2">
        <a href="{{ route('reports.export.excel', 'processing') }}?{{ request()->getQueryString() }}"
           class="btn btn-sm btn-success" data-bs-toggle="tooltip" title="Download Excel with current filters">
          <i class="fas fa-file-excel me-1"></i> Excel
        </a>
        <a href="{{ route('reports.export.pdf', 'processing') }}?{{ request()->getQueryString() }}"
           class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Download PDF with current filters">
          <i class="fas fa-file-pdf me-1"></i> PDF
        </a>
      </div>
    </div>
  </div>

  {{-- Filters --}}
  <form method="GET" class="card p-3 p-md-4 mb-3">
    <div class="section-title mb-3"><i class="fas fa-sliders"></i> Filter</div>
    <div class="row g-3 align-items-end">
      <div class="col-md-2">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
          <option value="">All</option>
          @foreach(['PENDING','IN_PROGRESS','DONE','REJECTED'] as $s)
            <option value="{{ $s }}" @selected(($status ?? '')===$s)>{{ $s }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label">Employee</label>
        <select name="employee_id" class="form-select">
          <option value="">All</option>
          @foreach($employees as $e)
            <option value="{{ $e->id }}" @selected(($employeeId ?? '')==$e->id)>{{ $e->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label">Agency</label>
        <select name="agency_id" class="form-select">
          <option value="">All</option>
          @foreach($agencies as $a)
            <option value="{{ $a->id }}" @selected(($agencyId ?? '')==$a->id)>{{ $a->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label">Collection Status</label>
        <select name="collected" class="form-select">
          <option value="" @selected(($collected ?? '')==='')>All</option>
          <option value="collected" @selected(($collected ?? '')==='collected')>Collected</option>
          <option value="not_collected" @selected(($collected ?? '')==='not_collected')>Not Collected</option>
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label">Search</label>
        <input type="text" name="q" value="{{ $search ?? '' }}" class="form-control" placeholder="Passport / Applicant">
      </div>
      <div class="col-md-1">
        <label class="form-label">From</label>
        <input type="date" name="date_from" value="{{ $dateFrom ?? '' }}" class="form-control">
      </div>
      <div class="col-md-1">
        <label class="form-label">To</label>
        <input type="date" name="date_to" value="{{ $dateTo ?? '' }}" class="form-control">
      </div>
      <div class="col-12 d-flex gap-2 mt-1">
        <button class="btn btn-apply px-4"><i class="fas fa-filter me-1"></i> Apply</button>
        <a href="{{ route('reports.processing') }}" class="btn btn-reset px-4"><i class="fas fa-rotate-left me-1"></i> Reset</a>
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
            <th>Passport No</th>
            <th>Applicant</th>
            <th>Employee</th>
            <th>Agency</th>
            <th>Status</th>
            <th>Collected?</th>
            <th>Created</th>
          </tr>
        </thead>
        <tbody>
          @forelse($rows as $i => $r)
            @php
              $collected = $r->passport?->collections?->isNotEmpty();
              $procBadge = match($r->status){
                'DONE' => 'bg-success', 'IN_PROGRESS'=>'bg-warning',
                'PENDING'=>'bg-secondary','REJECTED'=>'bg-danger', default=>'bg-secondary'
              };
            @endphp
            <tr>
              <td>{{ $rows->firstItem() + $i }}</td>
              <td class="fw-semibold">{{ $r->passport->passport_number ?? '—' }}</td>
              <td class="text-start fw-semibold">{{ $r->passport->applicant_name ?? '—' }}</td>
              <td>{{ $r->employee->name ?? '—' }}</td>
              <td>{{ $r->agency->name ?? '—' }}</td>
              <td><span class="badge {{ $procBadge }}">{{ $r->status }}</span></td>
              <td>
                <span class="badge {{ $collected ? 'bg-success' : 'bg-light' }}">
                  {{ $collected ? 'Collected' : 'Not Collected' }}
                </span>
              </td>
              <td>{{ $r->created_at?->format('d M Y, h:i A') }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="8">
                <div class="empty-state">
                  <div class="es-icon"><i class="fas fa-chart-bar"></i></div>
                  <div class="fw-semibold mb-1" style="color:var(--text-soft)">No data found</div>
                  <div>Try adjusting the filters.</div>
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
        @if($rows->total()) {{ $rows->firstItem() }}–{{ $rows->lastItem() }} @else 0 @endif
        of {{ $rows->total() }} results
      </div>
      <div>{{ $rows->links() }}</div>
    </div>
  </div>
</div>
@endsection
