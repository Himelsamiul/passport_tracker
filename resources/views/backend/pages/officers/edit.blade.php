@extends('backend.master')

@section('content')
<div class="container-fluid py-4">
  <h3 class="mb-4 fw-bold">Edit Passport Officer</h3>

  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
  @endif

  <form action="{{ route('officers.update', $officer->id) }}" method="POST">
    @csrf
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
        <input type="text" name="name" value="{{ old('name', $officer->name) }}" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label class="form-label fw-semibold">Phone</label>
        <input type="text" name="phone" value="{{ old('phone', $officer->phone) }}" class="form-control">
      </div>
      <div class="col-md-6">
        <label class="form-label fw-semibold">Email</label>
        <input type="email" name="email" value="{{ old('email', $officer->email) }}" class="form-control">
      </div>
      <div class="col-md-6">
        <label class="form-label fw-semibold">Status</label>
        <select name="status" class="form-select">
          <option value="ACTIVE" @selected(old('status', $officer->status)=='ACTIVE')>ACTIVE</option>
          <option value="INACTIVE" @selected(old('status', $officer->status)=='INACTIVE')>INACTIVE</option>
        </select>
      </div>
      <div class="col-12">
        <label class="form-label fw-semibold">Address</label>
        <textarea name="address" rows="2" class="form-control">{{ old('address', $officer->address) }}</textarea>
      </div>
      <div class="col-12">
        <label class="form-label fw-semibold">Notes</label>
        <textarea name="notes" rows="2" class="form-control">{{ old('notes', $officer->notes) }}</textarea>
      </div>
    </div>

    <div class="mt-4">
      <button type="submit" class="btn btn-primary">Update Officer</button>
      <a href="{{ route('officers.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
  </form>
</div>
@endsection
