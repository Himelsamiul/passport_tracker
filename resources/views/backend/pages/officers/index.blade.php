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

  <div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
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
          <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $o->name }}</td>
            <td>{{ $o->phone }}</td>
            <td>{{ $o->email }}</td>
            <td>{{ $o->address }}</td>
            <td><span class="badge {{ $o->status === 'ACTIVE' ? 'bg-success' : 'bg-secondary' }}">{{ $o->status }}</span></td>
            <td>{{ $o->notes }}</td>
            <td class="nowrap">
              <a href="{{ route('officers.edit', $o->id) }}" class="btn btn-sm btn-warning">Edit</a>
              <a href="{{ route('officers.delete', $o->id) }}" class="btn btn-sm btn-danger"
                 onclick="return confirm('Delete this officer?')">Delete</a>
            </td>
          </tr>
        @empty
          <tr><td colspan="8" class="text-center text-muted">No officers found.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
