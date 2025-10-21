@extends('backend.master')

@section('content')
<div class="container-fluid py-4">
  <h3 class="mb-3">Edit Processing</h3>

  <div class="mb-3">
    <a href="{{ route('processings.index') }}" class="btn btn-secondary btn-sm">← Back</a>
    <a href="{{ route('processings.show', $processing->id) }}" class="btn btn-info btn-sm">View</a>
  </div>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="card mb-3">
    <div class="card-header fw-bold">
      Processing for: {{ $processing->passport->passport_number ?? '—' }} — {{ $processing->passport->applicant_name ?? '—' }}
    </div>
    <div class="card-body">
      <form method="POST" action="{{ route('processings.update', $processing->id) }}">
        @csrf @method('PUT')
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Employee (Picked Up By)</label>
            <select name="employee_id" class="form-select" required>
              @foreach($employees as $e)
                <option value="{{ $e->id }}" @selected(old('employee_id', $processing->employee_id) == $e->id)>{{ $e->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-4">
            <label class="form-label">Agency</label>
            <select name="agency_id" class="form-select">
              <option value="">Unassigned</option>
              @foreach($agencies as $a)
                <option value="{{ $a->id }}" @selected(old('agency_id', $processing->agency_id) == $a->id)>{{ $a->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-4">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
              @foreach(['PENDING','IN_PROGRESS','DONE','REJECTED'] as $opt)
                <option value="{{ $opt }}" @selected(old('status', $processing->status) === $opt)>{{ $opt }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-12">
            <label class="form-label">Notes</label>
            <textarea name="notes" class="form-control" rows="3">{{ old('notes', $processing->notes) }}</textarea>
          </div>
        </div>

        <div class="text-end mt-3">
          <button class="btn btn-primary" type="submit">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
