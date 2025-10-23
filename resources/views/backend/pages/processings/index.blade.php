@extends('backend.master')

@section('content')
<style>
  .table-scroll { max-height: 70vh; overflow: auto; border: 1px solid #dee2e6; border-radius: .5rem; }
  .table-scroll table { width: max-content; min-width: 1200px; table-layout: auto; margin-bottom: 0; font-size: 13px; }
  th, td { white-space: nowrap; vertical-align: middle; padding: 6px 10px; line-height: 1.3; }
  thead th { position: sticky; top: 0; z-index: 3; background: #212529 !important; color: #fff !important; }
  .badge { font-size: 12px; padding: 4px 8px; }
  .btn-sm { font-size: 12px; padding: 3px 8px; margin: 1px; }
  .filters .form-control, .filters .form-select { height: 36px; padding: 4px 8px; font-size: 13px; }
  .summary .pill { display:inline-block; background:#f8f9fa; border:1px solid #dee2e6; border-radius:20px; padding:4px 10px; margin:2px 6px 2px 0; font-size:12px; }
</style>

<div class="container-fluid py-4">
  <h3 class="mb-2">Processing List</h3>

  @if(session('success'))
    <div class="alert alert-success py-2 px-3 my-2">{{ session('success') }}</div>
  @endif

  {{-- Summary --}}
  <div class="summary mb-3">
    <span class="pill">Total: <strong>{{ $totals['total'] }}</strong></span>
    <span class="pill">Pending: <strong>{{ $totals['pending'] }}</strong></span>
    <span class="pill">In-Progress: <strong>{{ $totals['in_progress'] }}</strong></span>
    <span class="pill">Done: <strong>{{ $totals['done'] }}</strong></span>
    <span class="pill">Rejected: <strong>{{ $totals['rejected'] }}</strong></span>
  </div>

  {{-- Filters --}}
  <form method="GET" class="card filters mb-3">
    <div class="card-body">
      <div class="row g-2 align-items-end">
        <div class="col-md-2">
          <label class="form-label mb-1">Status</label>
          <select name="status" class="form-select">
            <option value="">All</option>
            @foreach(['PENDING','IN_PROGRESS','DONE','REJECTED'] as $opt)
              <option value="{{ $opt }}" @selected($status === $opt)>{{ $opt }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-md-2">
          <label class="form-label mb-1">Employee</label>
          <select name="employee_id" class="form-select">
            <option value="">All</option>
            @foreach($employees as $e)
              <option value="{{ $e->id }}" @selected($employeeId == $e->id)>{{ $e->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-md-2">
          <label class="form-label mb-1">Agency</label>
          <select name="agency_id" class="form-select">
            <option value="">All</option>
            @foreach($agencies as $a)
              <option value="{{ $a->id }}" @selected($agencyId == $a->id)>{{ $a->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-md-2">
          <label class="form-label mb-1">Search</label>
          <input type="text" name="q" value="{{ $search }}" class="form-control" placeholder="Passport No / Applicant">
        </div>

        <div class="col-md-2">
          <label class="form-label mb-1">Date From</label>
          <input type="date" name="date_from" value="{{ $dateFrom }}" class="form-control">
        </div>

        <div class="col-md-2">
          <label class="form-label mb-1">Date To</label>
          <input type="date" name="date_to" value="{{ $dateTo }}" class="form-control">
        </div>

        <div class="col-md-12 mt-2 d-flex gap-2">
          <button class="btn btn-primary btn-sm" type="submit">Apply Filters</button>
          <a href="{{ route('processings.index') }}" class="btn btn-secondary btn-sm">Reset</a>
          <a href="{{ route('processings.create') }}" class="btn btn-success btn-sm ms-auto">+ New Processing</a>
        </div>
      </div>
    </div>
  </form>

  {{-- List Table --}}
  <div class="table-scroll">
    <table class="table table-bordered table-striped align-middle text-center">
      <thead>
        <tr>
          <th style="min-width:60px;">SL</th>
          <th style="min-width:140px;">Passport No</th>
          <th style="min-width:180px;">Applicant</th>
          <th style="min-width:180px;">Employee (Picked Up)</th>
          <th style="min-width:180px;">Agency</th>
          <th style="min-width:130px;">Processing Status</th>
          <th style="min-width:150px;">Collection Status</th>
          <th style="min-width:170px;">Created</th>
          <th style="min-width:220px;">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($processings as $i => $proc)
          @php
            // Has this passport been collected?
            $collected = \App\Models\PassportCollection::where('passport_id', $proc->passport_id)->exists();

            // Processing badge
            $procBadge = match($proc->status) {
              'DONE'        => 'bg-success',
              'IN_PROGRESS' => 'bg-warning text-dark',
              'PENDING'     => 'bg-secondary',
              'REJECTED'    => 'bg-danger',
              default       => 'bg-secondary'
            };

            // Collection badge
            $collectBadge = $collected ? 'bg-success' : 'bg-dark';
            $collectText  = $collected 
                ? 'Collected from '.($proc->agency->name ?? 'Agency') 
                : 'Not Collected';
          @endphp

          <tr @if($collected) style="background-color:#f8f9fa;" @endif>
            <td>{{ ($processings->currentPage()-1)*$processings->perPage() + $i + 1 }}</td>
            <td>{{ $proc->passport->passport_number ?? '—' }}</td>
            <td>{{ $proc->passport->applicant_name ?? '—' }}</td>
            <td>{{ $proc->employee->name ?? '—' }}</td>
            <td>{{ $proc->agency->name ?? 'Unassigned' }}</td>

            {{-- Processing Status --}}
            <td>
              <span class="badge {{ $procBadge }}">{{ $proc->status }}</span>
            </td>

            {{-- Collection Status --}}
            <td>
              <span class="badge {{ $collectBadge }}">{{ $collectText }}</span>
            </td>

            <td>{{ $proc->created_at->format('d M Y, h:i A') }}</td>

            <td>
              <div class="d-flex justify-content-center gap-1 flex-wrap">
                <a href="{{ route('processings.show', $proc->id) }}" class="btn btn-sm btn-info">
                  <i class="fas fa-eye"></i> View
                </a>
                <a href="{{ route('processings.edit', $proc->id) }}" class="btn btn-sm btn-warning">
                  <i class="fas fa-edit"></i> Edit
                </a>

                @if($collected)
                  {{-- 🔒 Locked delete --}}
                  <button type="button" class="btn btn-sm btn-secondary"
                          data-bs-toggle="tooltip"
                          title="You can’t delete this processing — the passport has already been collected.">
                    <i class="fas fa-lock"></i> Locked
                  </button>
                @else
                  <form action="{{ route('processings.destroy', $proc->id) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this record?')">
                      <i class="fas fa-trash-alt"></i> Delete
                    </button>
                  </form>
                @endif
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="9" class="text-muted">No processing records found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Pagination --}}
  <div class="mt-3">
    {{ $processings->links() }}
  </div>

  <p class="mt-2 text-muted" style="font-size:13px;">👉 Scroll left or right to view the full table.</p>
</div>
@endsection

@push('scripts')
<script>
  // Enable Bootstrap tooltips
  document.addEventListener('DOMContentLoaded', function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })
  });
</script>
@endpush
