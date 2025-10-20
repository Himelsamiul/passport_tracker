@extends('backend.master')

@section('content')
<div class="container-fluid py-4">
  <h3 class="mb-4">Agents List</h3>

  <a href="{{ route('agents.create') }}" class="btn btn-primary mb-3">Add New Agent</a>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
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
          <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $agent->name }}</td>
            <td>{{ $agent->phone }}</td>
            <td>{{ $agent->email }}</td>
            <td>{{ $agent->address }}</td>
            <td>{{ $agent->created_at->format('d M Y, h:i A') }}</td>
            <td>
              <a href="{{ route('agents.edit', $agent->id) }}" class="btn btn-sm btn-warning">Edit</a>
              <a href="{{ route('agents.delete', $agent->id) }}" class="btn btn-sm btn-danger"
                 onclick="return confirm('Are you sure you want to delete this agent?')">Delete</a>
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
@endsection
