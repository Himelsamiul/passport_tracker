@extends('backend.master')

@section('content')
<div class="container-fluid py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Passport Details</h3>
    <div class="d-flex gap-2">
      <a href="{{ route('passports.edit', $passport->id) }}" class="btn btn-warning">Edit</a>
      <a href="{{ route('passports.index') }}" class="btn btn-secondary">Back to List</a>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="row g-3">
    <!-- Left: main info -->
    <div class="col-lg-8">
      <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
          <strong>Applicant & Document</strong>
        </div>
        <div class="card-body">
          <div class="row mb-2">
            <div class="col-md-6">
              <div class="mb-2"><strong>Applicant Name:</strong> {{ $passport->applicant_name }}</div>
              <div class="mb-2"><strong>Passport Number:</strong> {{ $passport->passport_number }}</div>
              <div class="mb-2"><strong>Phone:</strong> {{ $passport->phone ?? '—' }}</div>
              <div class="mb-2"><strong>NID Number:</strong> {{ $passport->nid_number ?? '—' }}</div>
              <div class="mb-2"><strong>Nationality:</strong> {{ $passport->nationality ?? '—' }}</div>
            </div>
            <div class="col-md-6">
              <div class="mb-2"><strong>Date of Birth:</strong> {{ optional($passport->date_of_birth)->format('d M Y') ?? '—' }}</div>
              <div class="mb-2"><strong>Issue Date:</strong> {{ optional($passport->issue_date)->format('d M Y') ?? '—' }}</div>
              <div class="mb-2"><strong>Expiry Date:</strong> {{ optional($passport->expiry_date)->format('d M Y') ?? '—' }}</div>
              <div class="mb-2"><strong>Status:</strong> <span class="badge bg-secondary">{{ $passport->status }}</span></div>
              <div class="mb-2"><strong>Created:</strong> {{ optional($passport->created_at)->timezone(config('app.timezone','Asia/Dhaka'))->format('d M Y, h:i A') }}</div>
            </div>
          </div>

          <div class="mb-2"><strong>Address:</strong> {{ $passport->address ?? '—' }}</div>

          @if($passport->notes)
            <div class="alert alert-info mt-3 mb-0">
              <strong>Notes:</strong> {{ $passport->notes }}
            </div>
          @endif
        </div>
      </div>
    </div>

    <!-- Right: relationships & picture -->
    <div class="col-lg-4">
      <div class="card shadow-sm mb-3">
        <div class="card-header bg-dark text-white">
          <strong>Workflow</strong>
        </div>
        <div class="card-body">
          <div class="mb-2"><strong>Agent:</strong> {{ $passport->agent?->name ?? '—' }}</div>
          <div class="mb-2"><strong>Officer:</strong> {{ $passport->passportOfficer?->name ?? '—' }}</div>
          <div class="mb-2"><strong>Employee (Received By):</strong> {{ $passport->employee?->name ?? '—' }}</div>
        </div>
      </div>

      <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
          <strong>Passport Picture</strong>
        </div>
        <div class="card-body text-center">
          @if($passport->passport_picture)
            <img src="{{ asset('storage/'.$passport->passport_picture) }}" alt="Passport Picture" class="img-fluid rounded">
            <div class="mt-2">
              <a href="{{ asset('storage/'.$passport->passport_picture) }}" target="_blank" class="btn btn-sm btn-outline-secondary">Open Image</a>
            </div>
          @else
            <span class="text-muted">No picture uploaded.</span>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
