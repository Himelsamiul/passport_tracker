@extends('backend.master')

@section('content')
<div class="container-fluid py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Collection Report</h3>
    <div>
      <span class="badge bg-secondary">Total: {{ $totals['total'] }}</span>
      <span class="badge bg-success">Today: {{ $totals['today'] }}</span>
      <span class="badge bg-info text-dark">This Week: {{ $totals['this_week'] }}</span>
      <span class="badge bg-warning text-dark">This Month: {{ $totals['this_month'] }}</span>
    </div>
  </div>

  <form method="GET" class="card mb-3">
    <div class="card-body">
      <div class="row g-2 align-items-end">
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
        <div class="col-md-3">
          <label class="form-label">Search</label>
          <input type="text" name="q" value="{{ $search ?? '' }}" class="form-control" placeholder="Passport / Applicant">
        </div>
        <div class="col-md-3">
          <label class="form-label">From</label>
          <input type="date" name="date_from" value="{{ $dateFrom ?? '' }}" class="form-control">
        </div>
        <div class="col-md-3">
          <label class="form-label">To</label>
          <input type="date" name="date_to" value="{{ $dateTo ?? '' }}" class="form-control">
        </div>
        <div class="col-12 d-flex gap-2 mt-2">
          <button class="btn btn-primary"><i class="fas fa-filter"></i> Apply</button>
          <a href="{{ route('reports.collection') }}" class="btn btn-outline-secondary">Reset</a>
        </div>
      </div>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table table-bordered align-middle text-center">
      <thead class="table-dark">
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
            <td>{{ $r->passport->passport_number ?? '—' }}</td>
            <td class="text-start fw-semibold">{{ $r->passport->applicant_name ?? '—' }}</td>
            <td>{{ $r->passport->agent->name ?? '—' }}</td>
            <td>{{ $latestAgency ?? '—' }}</td>
            <td>{{ $r->employee->name ?? '—' }}</td>
            <td>{{ $r->collected_at?->format('d M Y, h:i A') }}</td>
          </tr>
        @empty
          <tr><td colspan="7" class="text-muted">No data found.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-2">{{ $rows->links() }}</div>
</div>
@endsection
