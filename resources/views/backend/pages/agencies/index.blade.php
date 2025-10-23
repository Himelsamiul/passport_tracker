@extends('backend.master')

@section('content')
<style>
  /* TABLE SCROLL AREA */
  .table-scroll {
    max-height: 600px;
    overflow-y: auto;
    overflow-x: auto;
    border: 1px solid #dee2e6;
    border-radius: .5rem;
  }

  /* TABLE STYLE */
  .table-scroll .table {
    width: max-content;
    min-width: 1100px;
    table-layout: auto;
    margin-bottom: 0;
    font-size: 13px;
  }

  th, td {
    white-space: nowrap;
    vertical-align: middle;
    padding: 6px 10px;
    line-height: 1.3;
  }

  .table-dark th {
    position: sticky;
    top: 0;
    z-index: 3;
    background: #212529;
    color: #fff;
  }

  .badge { font-size: 12px; padding: 4px 8px; }
  .btn-sm { font-size: 12px; padding: 3px 8px; margin: 1px; }

  .col-idx { min-width: 50px; text-align:center; }
  .col-name { min-width: 150px; }
  .col-cat { min-width: 120px; }
  .col-person { min-width: 130px; }
  .col-phone { min-width: 120px; }
  .col-email { min-width: 180px; }
  .col-address { min-width: 200px; }
  .col-status { min-width: 100px; text-align:center; }
  .col-notes { min-width: 180px; }
  .col-datetime { min-width: 150px; }
  .col-actions { min-width: 200px; white-space: nowrap; }

  .scroll-hint {
    font-size: 13px;
    font-style: italic;
    color: #6c757d;
    margin-bottom: 6px;
  }
</style>

<div class="container-fluid py-4">
  <h3 class="mb-3">Agencies</h3>

  <div class="d-flex justify-content-between align-items-center mb-2">
    <a href="{{ route('agencies.create') }}" class="btn btn-primary btn-sm">Add New Agency</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success py-2 px-3">{{ session('success') }}</div>
  @endif

  <p class="scroll-hint">👉 Scroll left or right to view the full table.</p>

  <div class="table-scroll">
    <table class="table table-bordered table-striped align-middle text-center">
      <thead class="table-dark">
        <tr>
          <th class="col-idx">#</th>
          <th class="col-name">Name</th>
          <th class="col-cat">Category</th>
          <th class="col-person">Contact Person</th>
          <th class="col-phone">Phone</th>
          <th class="col-email">Email</th>
          <th class="col-address">Address</th>
          <th class="col-status">Status</th>
          <th class="col-notes">Notes</th>
          <th class="col-datetime">Date &amp; Time</th>
          <th class="col-actions">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($agencies as $key => $agency)
          @php
            // ✅ Check if agency is already used in any processing
            $isUsed = \App\Models\PassportProcessing::where('agency_id', $agency->id)->exists();
          @endphp

          <tr @if($isUsed) style="background-color:#f8f9fa;" @endif>
            <td>{{ $key + 1 }}</td>
            <td>{{ $agency->name }}</td>
            <td>{{ $agency->category->name ?? 'N/A' }}</td>
            <td>{{ $agency->contact_person }}</td>
            <td>{{ $agency->phone }}</td>
            <td>{{ $agency->email }}</td>
            <td>{{ $agency->address }}</td>
            <td>
              <span class="badge {{ $agency->status === 'ACTIVE' ? 'bg-success' : 'bg-secondary' }}">
                {{ $agency->status }}
              </span>
            </td>
            <td>{{ $agency->notes }}</td>
            <td>{{ $agency->created_at->format('d M Y, h:i A') }}</td>
            <td>
              <a href="{{ route('agencies.edit', $agency->id) }}" class="btn btn-sm btn-warning">
                <i class="fas fa-edit"></i> Edit
              </a>

              @if($isUsed)
                {{-- 🔒 Locked (agency in use in processing) --}}
                <button type="button"
                        class="btn btn-sm btn-secondary"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="You can’t delete this agency — it’s already used in a processing.">
                  <i class="fas fa-lock"></i> Locked
                </button>
              @else
                {{-- 🗑️ Delete (safe to delete) --}}
                <a href="{{ route('agencies.delete', $agency->id) }}"
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('Are you sure you want to delete this agency?')">
                  <i class="fas fa-trash-alt"></i> Delete
                </a>
              @endif
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="11" class="text-center text-muted">No agencies found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>


@push('scripts')
<script>
  // ✅ Enable tooltips
  document.addEventListener('DOMContentLoaded', function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })
  });
</script>
@endpush
@endsection