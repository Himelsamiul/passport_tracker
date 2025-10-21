@extends('backend.master')

@section('content')
<div class="container-fluid py-4">
  <h3 class="mb-4 fw-bold">Edit Agency</h3>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('agencies.update', $agency->id) }}" method="POST">
    @csrf
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label fw-semibold">Agency Name <span class="text-danger">*</span></label>
        <input type="text" name="name" value="{{ old('name', $agency->name) }}" class="form-control" required>
      </div>

      <div class="col-md-6">
        <label class="form-label fw-semibold">Contact Person</label>
        <input type="text" name="contact_person" value="{{ old('contact_person', $agency->contact_person) }}" class="form-control">
      </div>

      <div class="col-md-6">
        <label class="form-label fw-semibold">Phone</label>
        <input type="text" name="phone" value="{{ old('phone', $agency->phone) }}" class="form-control">
      </div>

      <div class="col-md-6">
        <label class="form-label fw-semibold">Email</label>
        <input type="email" name="email" value="{{ old('email', $agency->email) }}" class="form-control">
      </div>

      <div class="col-md-12">
        <label class="form-label fw-semibold">Address</label>
        <textarea name="address" rows="2" class="form-control">{{ old('address', $agency->address) }}</textarea>
      </div>

      <div class="col-md-6">
        <label class="form-label fw-semibold">Status</label>
        <select name="status" class="form-select">
          <option value="ACTIVE" @selected(old('status', $agency->status) == 'ACTIVE')>ACTIVE</option>
          <option value="INACTIVE" @selected(old('status', $agency->status) == 'INACTIVE')>INACTIVE</option>
        </select>
      </div>

      <div class="col-md-6">
        <label class="form-label fw-semibold">Notes</label>
        <textarea name="notes" rows="2" class="form-control">{{ old('notes', $agency->notes) }}</textarea>
      </div>
    </div>

    <div class="mt-4">
      <button type="submit" class="btn btn-primary">Update Agency</button>
      <a href="{{ route('agencies.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
  </form>
</div>
@endsection
