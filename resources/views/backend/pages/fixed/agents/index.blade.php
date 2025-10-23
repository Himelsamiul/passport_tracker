@extends('backend.master')

@section('content')
<div class="container-fluid py-4">
  <h3 class="mb-4">Agents List</h3>

  <a href="{{ route('agents.create') }}" class="btn btn-primary mb-3">Add New Agent</a>

  {{-- ✅ Flash messages --}}
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  <div class="table-responsive">
    <table class="table table-bordered align-middle">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Phone</th>
          <th>Email</th>
          <th>Address</th>
          <th>Created At</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($agents as $index => $agent)
          @php
            // ✅ Check if agent is already used anywhere
            $isUsed =
              \App\Models\Passport::where('agent_id', $agent->id)->exists() ||
              \App\Models\PassportProcessing::whereHas('agency', function($q) use ($agent) {
                $q->where('id', $agent->id);
              })->exists() ||
              \App\Models\PassportCollection::whereHas('passport.agent', function($q) use ($agent) {
                $q->where('id', $agent->id);
              })->exists();
          @endphp

          <tr @if($isUsed) style="background-color:#f8f9fa;" @endif>
            <td>{{ $index + 1 }}</td>
            <td>{{ $agent->name }}</td>
            <td>{{ $agent->phone }}</td>
            <td>{{ $agent->email }}</td>
            <td>{{ $agent->address }}</td>
            <td>{{ $agent->created_at->format('d M Y, h:i A') }}</td>
            <td>
              <a href="{{ route('agents.edit', $agent->id) }}" class="btn btn-sm btn-warning">
                <i class="fas fa-edit"></i> Edit
              </a>

              @if($isUsed)
                {{-- 🔒 Locked (agent in use) --}}
                <button type="button"
                        class="btn btn-sm btn-secondary"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="You can’t delete this agent — already linked with other records.">
                  <i class="fas fa-lock"></i> Locked
                </button>
              @else
                {{-- 🗑️ Delete (safe to delete) --}}
                <a href="{{ route('agents.delete', $agent->id) }}"
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('Are you sure you want to delete this agent?')">
                  <i class="fas fa-trash-alt"></i> Delete
                </a>
              @endif
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center text-muted">No agents found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@push('scripts')
<script>
  // ✅ Enable Bootstrap tooltips
  document.addEventListener('DOMContentLoaded', function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })
  });
</script>
@endpush
@endsection
