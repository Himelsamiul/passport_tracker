@extends('backend.master')

@section('content')
<div class="container-fluid py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Passport Collections</h3>
    <a href="{{ route('collections.create') }}" class="btn btn-primary">New Collection</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="table-responsive">
    <table class="table table-bordered align-middle">
      <thead class="table-dark">
        <tr>
          <th>SL</th>
          <th>Passport Holder</th>
          <th>Passport Number</th>
          <th>Agent</th>
          <th>Agency</th>
          <th>Status</th> {{-- ✅ Added status column --}}
          <th>Received By (from Agency)</th>
          <th>Collected At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($collections as $i => $c)
        <tr>
          <td>{{ $collections->firstItem() + $i }}</td>
          <td>{{ $c->passport->applicant_name ?? '-' }}</td>
          <td>{{ $c->passport->passport_number ?? '-' }}</td>
          <td>{{ $c->passport->agent->name ?? '-' }}</td>

          {{-- ✅ Agency name from latest processing --}}
          <td>{{ optional($c->passport->processings->last()->agency)->name ?? '-' }}</td>

          {{-- ✅ Status from latest processing --}}
          <td>
            @php
              $latestProcessing = $c->passport->processings->last();
            @endphp
            @if($latestProcessing)
              <span class="badge 
                @if($latestProcessing->status == 'Pending') bg-warning
                @elseif($latestProcessing->status == 'Approved') bg-success
                @elseif($latestProcessing->status == 'Rejected') bg-danger
                @else bg-secondary
                @endif">
                {{ ucfirst($latestProcessing->status) }}
              </span>
            @else
              <span class="badge bg-secondary">-</span>
            @endif
          </td>

          <td>{{ $c->employee->name ?? '-' }}</td>
          <td>{{ optional($c->collected_at)->format('d M Y, h:i A') }}</td>
          <td class="text-center">
            <a href="{{ route('collections.show', $c->id) }}" class="btn btn-sm btn-outline-primary">
              <i class="fas fa-eye"></i> View
            </a>
            <form action="{{ route('collections.destroy', $c->id) }}" method="POST" style="display:inline-block;"
                  onsubmit="return confirm('Are you sure to delete this record?');">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-sm btn-outline-danger">
                <i class="fas fa-trash-alt"></i> Delete
              </button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="9" class="text-center">No collections found.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{ $collections->links() }}
</div>
@endsection
