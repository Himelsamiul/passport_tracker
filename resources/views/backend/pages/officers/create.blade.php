@extends('backend.master')

@section('content')
<div class="container-fluid py-4">
  <h3 class="mb-4 fw-bold">Add Passport Officer</h3>

  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
  @endif

  <form action="{{ route('officers.store') }}" method="POST">
    @csrf
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
        <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label class="form-label fw-semibold">Phone</label>
        <input type="text" name="phone" value="{{ old('phone') }}" class="form-control">
      </div>
      <div class="col-md-6">
        <label class="form-label fw-semibold">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" class="form-control">
      </div>
      <div class="col-md-6">
        <label class="form-label fw-semibold">Status</label>
        <select name="status" class="form-select">
          <option value="ACTIVE" @selected(old('status')=='ACTIVE')>ACTIVE</option>
          <option value="INACTIVE" @selected(old('status')=='INACTIVE')>INACTIVE</option>
        </select>
      </div>
      <div class="col-12">
        <label class="form-label fw-semibold">Address</label>
        <textarea name="address" rows="2" class="form-control">{{ old('address') }}</textarea>
      </div>
      <div class="col-12">
        <label class="form-label fw-semibold">Notes</label>
        <textarea name="notes" rows="2" class="form-control">{{ old('notes') }}</textarea>
      </div>
    </div>

    <div class="mt-4">
      <button type="submit" class="btn btn-success">Save Officer</button>
      <a href="{{ route('officers.index') }}" class="btn btn-secondary">Back</a>
    </div>
  </form>
</div>
@endsection
