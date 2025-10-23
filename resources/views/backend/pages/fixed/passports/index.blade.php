@extends('backend.master')

@section('content')
<div class="table-responsive">
  <table class="table table-bordered align-middle text-center">
    <thead class="table-dark">
      <tr>
        <th style="width:60px">SL</th>
        <th>Passport No</th>
        <th>Name</th>
        <th>Date of Birth</th>
        <th>Agent</th>
        <th>Officer</th>
        <th>Employee</th>
        <th>Status</th>
        <th>Processing Status</th>
        <th>Date</th>
        <th style="width:250px">Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($passports as $idx => $p)
        @php
          // Latest processing for this passport
          $processing = \App\Models\PassportProcessing::with('agency')
              ->where('passport_id', $p->id)
              ->latest('id')
              ->first();

          // Latest collection (if any)
          $collection = \App\Models\PassportCollection::where('passport_id', $p->id)
              ->latest('id')
              ->first();

          // Lock delete if either in processing or collected
          $locked = (bool)($processing || $collection);

          // Status label (Collected / Received etc.)
          if ($collection) {
              $statusText  = 'Collected from ' . ($processing?->agency?->name ?? 'Agency');
              $statusClass = 'bg-success';
          } else {
              $statusText  = $p->status ?? 'RECEIVED_FROM_AGENT';
              $statusClass = match($p->status) {
                  'RECEIVED_FROM_AGENT'    => 'bg-info',
                  'COLLECTED_FROM_AGENCY'  => 'bg-success',
                  'IN_PROCESS'             => 'bg-warning text-dark',
                  default                  => 'bg-secondary'
              };
          }

          // Processing status (Pending / On Going / Done / Rejected)
          $procStatus = $processing?->status;
          $procBadge  = match($procStatus) {
              'PENDING'      => 'bg-secondary',
              'IN_PROGRESS'  => 'bg-warning text-dark',
              'DONE'         => 'bg-primary',
              'REJECTED'     => 'bg-danger',
              default        => 'bg-light text-muted'
          };
          $procLabel = $procStatus
              ? (($procStatus === 'IN_PROGRESS') ? 'On Going' : ucwords(strtolower($procStatus)))
              : '—';
        @endphp

        <tr @if($locked) style="background-color:#f8f9fa;" @endif>
          <td>{{ $idx + 1 }}</td>
          <td class="fw-semibold">{{ $p->passport_number }}</td>
          <td>{{ $p->applicant_name }}</td>
          <td>{{ optional($p->date_of_birth)->format('d M Y') ?? '—' }}</td>
          <td>{{ $p->agent?->name ?? '—' }}</td>
          <td>{{ $p->passportOfficer?->name ?? '—' }}</td>
          <td>{{ $p->employee?->name ?? '—' }}</td>

          {{-- Main Status (Collected/Received/etc.) --}}
          <td>
            <span class="badge {{ $statusClass }}">
              {{ $statusText }}
            </span>
          </td>

          {{-- Processing Status (Pending/On Going/etc.) --}}
          <td>
            <span class="badge {{ $procBadge }}">
              {{ $procLabel }}
            </span>
          </td>

          <td>{{ optional($p->created_at)->timezone(config('app.timezone','Asia/Dhaka'))->format('d M Y, h:i A') }}</td>

          <td>
            <div class="d-flex justify-content-center gap-1 flex-wrap">
              <a href="{{ route('passports.show', $p->id) }}" class="btn btn-sm btn-info">
                <i class="fas fa-eye"></i> View
              </a>

              <a href="{{ route('passports.edit', $p->id) }}" class="btn btn-sm btn-warning">
                <i class="fas fa-edit"></i> Edit
              </a>

              @if($locked)
                <button type="button" class="btn btn-sm btn-secondary"
                        data-bs-toggle="tooltip"
                        title="This passport cannot be deleted — it’s already {{ $collection ? 'collected' : 'in processing' }}.">
                  <i class="fas fa-lock"></i> Locked
                </button>
              @else
                <a href="{{ route('passports.delete', $p->id) }}" 
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('Are you sure you want to delete this passport?')">
                  <i class="fas fa-trash-alt"></i> Delete
                </a>
              @endif
            </div>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="11" class="text-center text-muted">No passports found.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })
  });
</script>
@endpush
@endsection
