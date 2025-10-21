@extends('backend.master')

@section('content')
<div class="container-fluid py-4">
  <h3 class="mb-4">Category List</h3>
  <a href="{{ route('categories.create') }}" class="btn btn-primary mb-3">Add New Category</a>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <table class="table table-bordered">
    <thead class="table-dark">
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($categories as $key => $category)
      <tr>
        <td>{{ $key + 1 }}</td>
        <td>{{ $category->name }}</td>
        <td>
          <span class="badge bg-{{ $category->status == 'active' ? 'success' : 'secondary' }}">
            {{ ucfirst($category->status) }}
          </span>
        </td>
        <td>
          <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-warning btn-sm">Edit</a>
          <a href="{{ route('categories.delete', $category->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection
