@extends('backend.master')

@section('content')
<div class="container-fluid py-4">
  <h3 class="mb-4">Edit Agent</h3>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('agents.update', $agent->id) }}" method="POST">
    @csrf

    <div class="mb-3">
      <label class="form-label">Agent Name</label>
      <input type="text" name="name" class="form-control" required value="{{ old('name', $agent->name) }}">
    </div>

    <div class="mb-3">
      <label class="form-label">Phone</label>
      <input type="text" name="phone" class="form-control" value="{{ old('phone', $agent->phone) }}">
    </div>

    <div class="mb-3">
      <label class="form-label">Email</label>
      <input type="email" name="email" class="form-control" value="{{ old('email', $agent->email) }}">
    </div>

    <div class="mb-3">
      <label class="form-label">Address</label>
      <textarea name="address" class="form-control" rows="3">{{ old('address', $agent->address) }}</textarea>
    </div>

    <button type="submit" class="btn btn-primary">Update Agent</button>
    <a href="{{ route('agents.index') }}" class="btn btn-secondary">Cancel</a>
  </form>
</div>
@endsection
