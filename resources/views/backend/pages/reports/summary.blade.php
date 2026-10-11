@extends('backend.master')

@section('page-title', 'Summary Report')
@section('page-subtitle', 'Lifecycle view — from intake to delivery')

@section('content')
{{-- ============================================================
     ডিজাইন নোট (সামারি রিপোর্টে যা কাজ হয়েছে):
     • নতুন ডার্ক থিমে পেজ হেডার + ফিল্টার কার্ড + টেবিল কার্ড।
     • লাইফসাইকেল ভিউ: সর্বশেষ প্রসেসিং, কর্মী/এজেন্সি, কলেক্ট হয়েছে কিনা।
     • সব ফিল্টার/পেজিনেশন লজিক অপরিবর্তিত।
     ============================================================ --}}
<div class="container-fluid px-0">

  {{-- Header --}}
  <div class="page-head d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div>
      <h3>Summary Report (Lifecycle)</h3>
      <p class="ph-desc">Each passport's journey from intake to delivery at a glance.</p>
    </div>
    {{-- Downloads keep the current filters --}}
    <div class="d-flex gap-2">
      <a href="{{ route('reports.export.excel', 'summary') }}?{{ request()->getQueryString() }}"
         class="btn btn-sm btn-success" data-bs-toggle="tooltip" title="Download Excel with current filters">
        <i class="fas fa-file-excel me-1"></i> Excel
      </a>
      <a href="{{ route('reports.export.pdf', 'summary') }}?{{ request()->getQueryString() }}"
         class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Download PDF with current filters">
        <i class="fas fa-file-pdf me-1"></i> PDF
      </a>
    </div>
  </div>

  {{-- Filters --}}
  <form method="GET" class="card p-3 p-md-4 mb-3">
    <div class="section-title mb-3"><i class="fas fa-sliders"></i> Filter</div>
    <div class="row g-3 align-items-end">
      <div class="col-md-2">
        <label class="form-label">Stage</label>
        <select name="stage" class="form-select">
          <option value="" @selected(($stage ?? '')==='')>All</option>
          <option value="processing" @selected(($stage ?? '')==='processing')>Processing Only</option>
          <option value="collected" @selected(($stage ?? '')==='collected')>Collected</option>
          <option value="pending" @selected(($stage ?? '')==='pending')>No Processing</option>
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
        <a href="{{ route('reports.summary') }}" class="btn btn-reset px-4"><i class="fas fa-rotate-left me-1"></i> Reset</a>
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
            <th>Latest Processing</th>
            <th>Processing Employee</th>
            <th>Processing Agency</th>
            <th>Collected?</th>
            <th>Collected By</th>
            <th>Collection Date</th>
          </tr>
        </thead>
        <tbody>
          @forelse($rows as $i => $p)
            @php
              $latestProc = $p->processings->first();
              $latestCol  = $p->collections->first();
            @endphp
            <tr>
              <td>{{ $rows->firstItem() + $i }}</td>
              <td class="fw-semibold">{{ $p->passport_number }}</td>
              <td class="text-start fw-semibold">{{ $p->applicant_name }}</td>
              <td>
                @if($latestProc)
                  <span class="badge
                    {{ $latestProc->status === 'DONE' ? 'bg-success' :
                       ($latestProc->status === 'IN_PROGRESS' ? 'bg-warning' :
                       ($latestProc->status === 'PENDING' ? 'bg-secondary' : 'bg-danger')) }}">
                    {{ $latestProc->status }}
                  </span>
                  <div class="small text-muted">{{ $latestProc->created_at?->format('d M Y, h:i A') }}</div>
                @else
                  —
                @endif
              </td>
              <td>{{ $latestProc?->employee?->name ?? '—' }}</td>
              <td>{{ $latestProc?->agency?->name ?? '—' }}</td>
              <td>
                <span class="badge {{ $latestCol ? 'bg-success' : 'bg-light' }}">
                  {{ $latestCol ? 'Collected' : 'Not Collected' }}
                </span>
              </td>
              <td>{{ $latestCol?->employee?->name ?? '—' }}</td>
              <td>{{ $latestCol?->collected_at?->format('d M Y, h:i A') ?? '—' }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="9">
                <div class="empty-state">
                  <div class="es-icon"><i class="fas fa-chart-pie"></i></div>
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
