@extends('backend.master')

@section('page-title', 'Collection Report')
@section('page-subtitle', 'Delivery and handover activity')

@section('content')
{{-- ============================================================
     ডিজাইন নোট (কলেকশন রিপোর্টে যা কাজ হয়েছে):
     • নতুন ডার্ক থিমে পেজ হেডার + সামারি চিপ (Total/Today/This Week/This Month)।
     • ফিল্টার কার্ড ও টেবিল কার্ড; সব ফিল্টার/পেজিনেশন লজিক অপরিবর্তিত।
     ============================================================ --}}
<div class="container-fluid px-0">

  {{-- Header + summary chips --}}
  <div class="page-head d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div>
      <h3>Collection Report</h3>
      <p class="ph-desc">Every passport handover recorded on the platform.</p>
    </div>
    <div class="d-flex flex-wrap gap-2 align-items-center">
      <span class="badge bg-secondary">Total: {{ $totals['total'] }}</span>
      <span class="badge bg-success">Today: {{ $totals['today'] }}</span>
      <span class="badge bg-info">This Week: {{ $totals['this_week'] }}</span>
      <span class="badge bg-warning">This Month: {{ $totals['this_month'] }}</span>

      {{-- Downloads keep the current filters --}}
      <div class="d-flex gap-2 ms-md-2">
        <a href="{{ route('reports.export.excel', 'collection') }}?{{ request()->getQueryString() }}"
           class="btn btn-sm btn-success" data-bs-toggle="tooltip" title="Download Excel with current filters">
          <i class="fas fa-file-excel me-1"></i> Excel
        </a>
        <a href="{{ route('reports.export.pdf', 'collection') }}?{{ request()->getQueryString() }}"
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
      <div class="col-md-3">
        <label class="form-label">Collected By (Employee)</label>
        <select name="employee_id" class="form-select">
          <option value="">All</option>
          @foreach($employees as $e)
            <option value="{{ $e->id }}" @selected(($employeeId ?? '')==$e->id)>{{ $e->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label">Agency</label>
        <select name="agency_id" class="form-select">
          <option value="">All</option>
          @foreach($agencies as $a)
            <option value="{{ $a->id }}" @selected(($agencyId ?? '')==$a->id)>{{ $a->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label">Search</label>
        <input type="text" name="q" value="{{ $search ?? '' }}" class="form-control" placeholder="Passport / Applicant">
      </div>
      <div class="col-md-2">
        <label class="form-label">From</label>
        <input type="date" name="date_from" value="{{ $dateFrom ?? '' }}" class="form-control">
      </div>
      <div class="col-md-2">
        <label class="form-label">To</label>
        <input type="date" name="date_to" value="{{ $dateTo ?? '' }}" class="form-control">
      </div>
      <div class="col-12 d-flex gap-2 mt-1">
        <button class="btn btn-apply px-4"><i class="fas fa-filter me-1"></i> Apply</button>
        <a href="{{ route('reports.collection') }}" class="btn btn-reset px-4"><i class="fas fa-rotate-left me-1"></i> Reset</a>
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
            <th>Agent</th>
            <th>Agency</th>
            <th>Collected By</th>
            <th>Collected At</th>
          </tr>
        </thead>
        <tbody>
          @forelse($rows as $i => $r)
            @php
              $latestAgency = optional($r->passport->processings->last()?->agency)->name;
            @endphp
            <tr>
              <td>{{ $rows->firstItem() + $i }}</td>
              <td class="fw-semibold">{{ $r->passport->passport_number ?? '—' }}</td>
              <td class="text-start fw-semibold">{{ $r->passport->applicant_name ?? '—' }}</td>
              <td>{{ $r->passport->agent->name ?? '—' }}</td>
              <td>{{ $latestAgency ?? '—' }}</td>
              <td>{{ $r->employee->name ?? '—' }}</td>
              <td>{{ $r->collected_at?->format('d M Y, h:i A') }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="7">
                <div class="empty-state">
                  <div class="es-icon"><i class="fas fa-box-open"></i></div>
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
