@extends('backend.master')

@section('content')
<div class="container-fluid py-4">
  <h3 class="mb-4 fw-bold">Add New Agency</h3>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('agencies.store') }}" method="POST">
    @csrf
    <div class="row g-3">

      {{-- Category --}}
      <div class="col-md-6">
        <label class="form-label fw-semibold">Category</label>
        <div class="d-flex gap-2">
          <select name="category_id" class="form-select">
            <option value="">-- Select Category --</option>
            @foreach ($categories as $cat)
              <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>
                {{ $cat->name }}
              </option>
            @endforeach
          </select>
          <a href="{{ route('categories.create') }}" class="btn btn-outline-light border">
            + New
          </a>
        </div>
        @error('category_id') <small class="text-danger">{{ $message }}</small> @enderror
      </div>

      <div class="col-md-6">
        <label class="form-label fw-semibold">Agency Name <span class="text-danger">*</span></label>
        <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
      </div>

      <div class="col-md-6">
        <label class="form-label fw-semibold">Contact Person</label>
        <input type="text" name="contact_person" value="{{ old('contact_person') }}" class="form-control">
      </div>

      <div class="col-md-6">
        <label class="form-label fw-semibold">Phone</label>
        <input type="text" name="phone" value="{{ old('phone') }}" class="form-control">
      </div>

      <div class="col-md-6">
        <label class="form-label fw-semibold">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" class="form-control">
      </div>

      <div class="col-md-12">
        <label class="form-label fw-semibold">Address</label>
        <textarea name="address" rows="2" class="form-control">{{ old('address') }}</textarea>
      </div>

      <div class="col-md-6">
        <label class="form-label fw-semibold">Status</label>
        <select name="status" class="form-select">
          <option value="ACTIVE" @selected(old('status') == 'ACTIVE')>ACTIVE</option>
          <option value="INACTIVE" @selected(old('status') == 'INACTIVE')>INACTIVE</option>
        </select>
      </div>

      <div class="col-md-6">
        <label class="form-label fw-semibold">Notes</label>
        <textarea name="notes" rows="2" class="form-control">{{ old('notes') }}</textarea>
      </div>
    </div>

    <div class="mt-4">
      <button type="submit" class="btn btn-success">Save Agency</button>
      <a href="{{ route('agencies.index') }}" class="btn btn-secondary">Back</a>
    </div>
  </form>
</div>
@endsection
