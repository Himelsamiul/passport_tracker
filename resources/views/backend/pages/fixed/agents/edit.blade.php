@extends('backend.master')

@section('page-title', 'Edit Agent')
@section('page-subtitle', 'Update agent information')

@section('content')
{{-- ============================================================
     ডিজাইন নোট (এজেন্ট এডিট পেজে যা কাজ হয়েছে):
     • নতুন ডার্ক থিমে ফর্ম কার্ড — তৈরির পেজের সাথে একই ডিজাইন।
     • উপরে পেজ হেডার + "Back to Agents" বাটন।
     • আগের ভ্যালু ও ভ্যালিডেশন লজিক অপরিবর্তিত।
     ============================================================ --}}
<div class="container-fluid px-0" style="max-width:860px">

  <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div>
      <h3 class="mb-0">Edit Agent</h3>
      <p class="ph-desc" style="color:var(--muted);font-size:.84rem;margin:2px 0 0">Update information for {{ $agent->name }}.</p>
    </div>
    <a href="{{ route('agents.index') }}" class="btn btn-outline-secondary btn-sm">
      <i class="fas fa-arrow-left me-1"></i> Back to Agents
    </a>
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

  <div class="card">
    <div class="card-body p-3 p-md-4">
      <form action="{{ route('agents.update', $agent->id) }}" method="POST">
        @csrf

        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Agent Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" required value="{{ old('name', $agent->name) }}">
          </div>

          <div class="col-md-6">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $agent->phone) }}">
          </div>

          <div class="col-12">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $agent->email) }}">
          </div>

          <div class="col-12">
            <label class="form-label">Address</label>
            <textarea name="address" class="form-control" rows="3">{{ old('address', $agent->address) }}</textarea>
          </div>
        </div>

        <div class="mt-4 d-flex gap-2">
          <button type="submit" class="btn btn-primary px-4">
            <i class="fas fa-arrows-rotate me-1"></i> Update Agent
          </button>
          <a href="{{ route('agents.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
