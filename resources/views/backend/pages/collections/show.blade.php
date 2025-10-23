@extends('backend.master')

@section('content')
<div class="container-fluid py-4">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Passport Collection Details</h3>
    <a href="{{ route('collections.index') }}" class="btn btn-outline-secondary">Back to List</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="row g-3">

    <!-- 🧾 Stakeholder Info -->
    <div class="col-lg-4">
      <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white fw-semibold">Stakeholder Information</div>
        <div class="card-body">
          <div class="mb-2"><strong>Agent Name:</strong> {{ $collection->passport->agent->name ?? '-' }}</div>
          <div class="mb-2"><strong>Passport Officer:</strong> {{ $collection->passport->passportOfficer->name ?? '-' }}</div>
          
          <hr>
          <div class="mb-2 text-success"><strong>Employee (Received from Agent):</strong> {{ $collection->passport->employee->name ?? '-' }}</div>
          <div class="mb-2 text-warning"><strong>Employee (Gave to Process):</strong> {{ optional($collection->passport->processings->last()->employee)->name ?? '-' }}</div>
          <div class="mb-2 text-danger"><strong>Employee (Collected from Agency):</strong> {{ $collection->employee->name ?? '-' }}</div>
        </div>
      </div>
    </div>

    <!-- 📦 Collection Info -->
    <div class="col-lg-4">
      <div class="card shadow-sm border-0">
        <div class="card-header bg-success text-white fw-semibold">Collection Information</div>
        <div class="card-body">
          <div class="mb-2"><strong>Collected By:</strong> {{ $collection->employee->name ?? '-' }}</div>
          <div class="mb-2"><strong>Collected At:</strong> {{ optional($collection->collected_at)->format('d M Y, h:i A') }}</div>
          <div class="mb-2"><strong>Notes:</strong> {{ $collection->notes ?? '-' }}</div>
        </div>
      </div>
    </div>

    <!-- 🛂 Passport Info -->
    <div class="col-lg-4">
      <div class="card shadow-sm border-0">
        <div class="card-header bg-dark text-white fw-semibold">Passport Information</div>
        <div class="card-body">
          <div class="row g-2">
            <div class="col-12"><strong>Holder Name:</strong> {{ $collection->passport->applicant_name ?? '-' }}</div>
            <div class="col-12"><strong>Passport Number:</strong> {{ $collection->passport->passport_number ?? '-' }}</div>
            <div class="col-12"><strong>Date of Birth:</strong> {{ optional($collection->passport->date_of_birth)->format('d M Y') ?? '-' }}</div>
            <div class="col-12"><strong>Nationality:</strong> {{ $collection->passport->nationality ?? '-' }}</div>
            <div class="col-12"><strong>Phone:</strong> {{ $collection->passport->phone ?? '-' }}</div>
            <div class="col-12"><strong>Address:</strong> {{ $collection->passport->address ?? '-' }}</div>
            <div class="col-12"><strong>Issue Date:</strong> {{ optional($collection->passport->issue_date)->format('d M Y') ?? '-' }}</div>
            <div class="col-12"><strong>Expiry Date:</strong> {{ optional($collection->passport->expiry_date)->format('d M Y') ?? '-' }}</div>
            <div class="col-12"><strong>NID Number:</strong> {{ $collection->passport->nid_number ?? '-' }}</div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>


@push('styles')
<style>
  .card { border-radius: 0.6rem; }
  .card-header { font-size: 15px; text-transform: uppercase; }
  .card-body { font-size: 14px; line-height: 1.6; }
  strong { color: #0d6efd; }
  .text-success strong { color: #198754; }
  .text-warning strong { color: #ffc107; }
  .text-danger strong { color: #dc3545; }
</style>
@endpush
@endsection