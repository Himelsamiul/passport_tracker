@extends('backend.master')

@section('content')
<div class="container-fluid py-4">
  <h3 class="mb-4">Edit Category</h3>

  <form action="{{ route('categories.update', $category->id) }}" method="POST">
    @csrf
    <div class="mb-3">
      <label for="name" class="form-label">Category Name</label>
      <input type="text" name="name" id="name" value="{{ $category->name }}" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="status" class="form-label">Status</label>
      <select name="status" id="status" class="form-select" required>
        <option value="active" {{ $category->status == 'active' ? 'selected' : '' }}>Active</option>
        <option value="inactive" {{ $category->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
      </select>
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
    <a href="{{ route('categories.index') }}" class="btn btn-secondary">Back</a>
  </form>
</div>
@endsection
