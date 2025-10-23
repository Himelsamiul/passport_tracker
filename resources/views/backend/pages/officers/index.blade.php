@extends('backend.master')

@section('content')
<div class="container-fluid py-4">
  <h3 class="mb-4">Passport Officers</h3>

  <div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('officers.create') }}" class="btn btn-primary">Add Officer</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  <div class="table-responsive">
    <table class="table table-bordered table-striped align-middle text-center">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Phone</th>
          <th>Email</th>
          <th>Address</th>
          <th>Status</th>
          <th>Notes</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($officers as $i => $o)
          @php
            // ✅ Check if this officer is used in any passport
            $isUsed = \App\Models\Passport::where('passport_officer_id', $o->id)->exists();
          @endphp

          <tr @if($isUsed) style="background-color:#f8f9fa;" @endif>
            <td>{{ $i+1 }}</td>
            <td>{{ $o->name }}</td>
            <td>{{ $o->phone }}</td>
            <td>{{ $o->email }}</td>
            <td>{{ $o->address }}</td>
            <td>
              <span class="badge {{ $o->status === 'ACTIVE' ? 'bg-success' : 'bg-secondary' }}">
                {{ $o->status }}
              </span>
            </td>
            <td>{{ $o->notes }}</td>
            <td class="nowrap">
              <a href="{{ route('officers.edit', $o->id) }}" class="btn btn-sm btn-warning">
                <i class="fas fa-edit"></i> Edit
              </a>

              @if($isUsed)
                {{-- 🔒 Locked button if officer is in use --}}
                <button type="button" 
                        class="btn btn-sm btn-secondary"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="You can’t delete this officer — they are already assigned to a passport.">
                  <i class="fas fa-lock"></i> Locked
                </button>
              @else
                {{-- 🗑️ Delete button (safe to delete) --}}
                <a href="{{ route('officers.delete', $o->id) }}" 
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('Are you sure you want to delete this officer?')">
                  <i class="fas fa-trash-alt"></i> Delete
                </a>
              @endif
            </td>
          </tr>
        @empty
          <tr><td colspan="8" class="text-center text-muted">No officers found.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>


@push('scripts')
<script>
  // ✅ Enable Bootstrap tooltip globally
  document.addEventListener('DOMContentLoaded', function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })
  });
</script>
@endpush
@endsection