@extends('backend.master')

@section('content')
<div class="container-fluid py-4">
  <div class="card shadow-sm">
    <div class="card-header bg-dark text-white">
      <h5 class="mb-0">Add New Agent</h5>
    </div>

    <div class="card-body">
      {{-- Top-level validation summary --}}
      @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('agents.store') }}" method="POST">
        @csrf

        <div class="row g-3">
          {{-- Agent Name --}}
          <div class="col-md-4">
            <label class="form-label">Agent Name <span class="text-danger">*</span></label>
            <input type="text"
                   name="name"
                   class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name') }}"
                   required>
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- Phone --}}
          <div class="col-md-4">
            <label class="form-label">Phone</label>
            <input type="text"
                   name="phone"
                   class="form-control @error('phone') is-invalid @enderror"
                   value="{{ old('phone') }}">
            @error('phone')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- Email --}}
          <div class="col-md-4">
            <label class="form-label">Email</label>
            <input type="email"
                   name="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}">
            @error('email')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- Address --}}
          <div class="col-12">
            <label class="form-label">Address</label>
            <textarea name="address"
                      rows="3"
                      class="form-control @error('address') is-invalid @enderror">{{ old('address') }}</textarea>
            @error('address')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="mt-4 d-flex gap-2">
          <button type="submit" class="btn btn-success">Save Agent</button>
          <a href="{{ route('agents.index') }}" class="btn btn-secondary">Back</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
