@extends('backend.master')

@section('content')
<div class="container-fluid py-4">
  <h3 class="mb-4">Edit Passport</h3>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('passports.update', $passport->id) }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label">Agent <span class="text-danger">*</span></label>
        <select name="agent_id" class="form-select" required>
          @foreach($agents as $a)
            <option value="{{ $a->id }}" @selected(old('agent_id', $passport->agent_id)==$a->id)>{{ $a->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-md-4">
        <label class="form-label">Applicant Name <span class="text-danger">*</span></label>
        <input type="text" name="applicant_name" value="{{ old('applicant_name', $passport->applicant_name) }}" class="form-control" required>
      </div>

      <div class="col-md-4">
        <label class="form-label">Phone</label>
        <input type="text" name="phone" value="{{ old('phone', $passport->phone) }}" class="form-control">
      </div>

      <div class="col-md-6">
        <label class="form-label">Address</label>
        <input type="text" name="address" value="{{ old('address', $passport->address) }}" class="form-control">
      </div>

      <div class="col-md-3">
        <label class="form-label">Date of Birth</label>
        <input type="date" name="date_of_birth" value="{{ old('date_of_birth', optional($passport->date_of_birth)->format('Y-m-d')) }}" class="form-control">
      </div>

      <div class="col-md-3">
        <label class="form-label">Nationality</label>
        <input type="text" name="nationality" value="{{ old('nationality', $passport->nationality) }}" class="form-control">
      </div>

      <div class="col-md-4">
        <label class="form-label">Passport Number <span class="text-danger">*</span></label>
        <input type="text" name="passport_number" value="{{ old('passport_number', $passport->passport_number) }}" class="form-control" required>
      </div>

      <div class="col-md-4">
        <label class="form-label">Issue Date</label>
        <input type="date" name="issue_date" value="{{ old('issue_date', optional($passport->issue_date)->format('Y-m-d')) }}" class="form-control">
      </div>

      <div class="col-md-4">
        <label class="form-label">Expiry Date</label>
        <input type="date" name="expiry_date" value="{{ old('expiry_date', optional($passport->expiry_date)->format('Y-m-d')) }}" class="form-control">
      </div>

      <div class="col-md-6">
        <label class="form-label">NID Number</label>
        <input type="text" name="nid_number" value="{{ old('nid_number', $passport->nid_number) }}" class="form-control">
      </div>

      {{-- 
        <div class="col-md-6">
          <label class="form-label">Passport Picture</label>
          <input type="file" name="passport_picture" class="form-control" accept="image/*">
          <small class="text-muted">Leave empty to keep existing.</small>
          @if($passport->passport_picture)
            <div class="mt-2">
          <img src="{{ asset('storage/'.$passport->passport_picture) }}" alt="Current Picture" width="100" style="border-radius:6px;">
            </div>
          @endif
        </div>
      --}}
    </div>

    <div class="mt-4">
      <button type="submit" class="btn btn-primary">Update Passport</button>
      <a href="{{ route('passports.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
  </form>
</div>
@endsection
