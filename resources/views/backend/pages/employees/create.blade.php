@extends('backend.master')

@section('content')
<div class="container py-4">
  <h3 class="mb-4">Add New Employee</h3>

  <form action="{{ route('employees.store') }}" method="POST">
    @csrf

    <div class="mb-3">
      <label>Name</label>
      <input type="text" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Designation</label>
      <input type="text" name="designation" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Phone</label>
      <input type="text" name="phone" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Email</label>
      <input type="email" name="email" class="form-control">
    </div>

    <button type="submit" class="btn btn-success">Save</button>
    <a href="{{ route('employees.index') }}" class="btn btn-secondary">Cancel</a>
  </form>
</div>
@endsection
