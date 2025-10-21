@extends('backend.master')

@section('content')
<div class="container-fluid py-4">
  <h3 class="mb-4">Agencies</h3>

  <div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('agencies.create') }}" class="btn btn-primary">Add New Agency</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Contact Person</th>
          <th>Phone</th>
          <th>Email</th>
          <th>Address</th>
          <th>Status</th>
          <th>Notes</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($agencies as $key => $agency)
          <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $agency->name }}</td>
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
            <td>
              <a href="{{ route('agencies.edit', $agency->id) }}" class="btn btn-sm btn-warning">Edit</a>
              <a href="{{ route('agencies.delete', $agency->id) }}" class="btn btn-sm btn-danger"
                 onclick="return confirm('Are you sure you want to delete this agency?')">Delete</a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="9" class="text-center text-muted">No agencies found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
