@extends('backend.master')

@section('content')
<div class="container-fluid py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Summary Report (Lifecycle)</h3>
  </div>

  <form method="GET" class="card mb-3">
    <div class="card-body">
      <div class="row g-2 align-items-end">
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
        <div class="col-md-3">
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
        <div class="col-12 d-flex gap-2 mt-2">
          <button class="btn btn-primary"><i class="fas fa-filter"></i> Apply</button>
          <a href="{{ route('reports.summary') }}" class="btn btn-outline-secondary">Reset</a>
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
            <td>{{ $p->passport_number }}</td>
            <td class="text-start fw-semibold">{{ $p->applicant_name }}</td>
            <td>
              @if($latestProc)
                <span class="badge 
                  {{ $latestProc->status === 'DONE' ? 'bg-success' :
                     ($latestProc->status === 'IN_PROGRESS' ? 'bg-warning text-dark' :
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
              <span class="badge {{ $latestCol ? 'bg-success' : 'bg-dark' }}">
                {{ $latestCol ? 'Collected' : 'Not Collected' }}
              </span>
            </td>
            <td>{{ $latestCol?->employee?->name ?? '—' }}</td>
            <td>{{ $latestCol?->collected_at?->format('d M Y, h:i A') ?? '—' }}</td>
          </tr>
        @empty
          <tr><td colspan="9" class="text-muted">No data found.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-2">{{ $rows->links() }}</div>
</div>
@endsection
