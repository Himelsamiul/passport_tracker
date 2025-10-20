@extends('backend.master')

@section('content')
<div class="container-fluid py-4">
  <h3 class="mb-4">Add Passport</h3>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('passports.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label">Agent <span class="text-danger">*</span></label>
        <select name="agent_id" class="form-select" required>
          <option value="">— Select Agent —</option>
          @foreach($agents as $a)
            <option value="{{ $a->id }}" @selected(old('agent_id')==$a->id)>{{ $a->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-md-4">
        <label class="form-label">Applicant Name <span class="text-danger">*</span></label>
        <input type="text" name="applicant_name" value="{{ old('applicant_name') }}" class="form-control" required>
      </div>

      <div class="col-md-4">
        <label class="form-label">Phone</label>
        <input type="text" name="phone" value="{{ old('phone') }}" class="form-control">
      </div>

      <div class="col-md-6">
        <label class="form-label">Address</label>
        <input type="text" name="address" value="{{ old('address') }}" class="form-control">
      </div>

      <div class="col-md-3">
        <label class="form-label">Date of Birth</label>
        <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" class="form-control">
      </div>

      <div class="col-md-3">
        <label class="form-label">Nationality</label>
        <input type="text" name="nationality" value="{{ old('nationality') }}" class="form-control">
      </div>

      <div class="col-md-4">
        <label class="form-label">Passport Number <span class="text-danger">*</span></label>
        <input type="text" name="passport_number" value="{{ old('passport_number') }}" class="form-control" required>
      </div>

      <div class="col-md-4">
        <label class="form-label">Issue Date</label>
        <input type="date" name="issue_date" value="{{ old('issue_date') }}" class="form-control">
      </div>

      <div class="col-md-4">
        <label class="form-label">Expiry Date</label>
        <input type="date" name="expiry_date" value="{{ old('expiry_date') }}" class="form-control">
      </div>

      <div class="col-md-6">
        <label class="form-label">NID Number</label>
        <input type="text" name="nid_number" value="{{ old('nid_number') }}" class="form-control">
      </div>

      <div class="col-md-6">
        <label class="form-label">Passport Picture</label>
        <input type="file" name="passport_picture" class="form-control" accept="image/*">
        <small class="text-muted">Max 2MB. JPG/PNG/WebP.</small>
      </div>
    </div>

    <div class="mt-4">
      <button type="submit" class="btn btn-success">Save Passport</button>
      <a href="{{ route('passports.index') }}" class="btn btn-secondary">Back</a>
    </div>
  </form>
</div>
@endsection
