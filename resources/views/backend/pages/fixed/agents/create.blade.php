@extends('backend.master')

@section('page-title', 'Add Agent')
@section('page-subtitle', 'Register a new agent for passport handover')

@section('content')
{{-- ============================================================
     ডিজাইন নোট (এজেন্ট তৈরির পেজে যা কাজ হয়েছে):
     • পুরনো সাদা কার্ড + কালো হেডার স্টাইল বাদ — নতুন ডার্ক থিমের ফর্ম কার্ড।
     • উপরে পেজ হেডার + "Back to Agents" বাটন।
     • ফর্ম দুই কলামে সুন্দর করে সাজানো (নাম, ফোন, ইমেইল, ঠিকানা)।
     • Save/Cancel বাটন নতুন স্টাইলে; ভ্যালিডেশন এরর আগের মতোই দেখায়।
     ============================================================ --}}
<div class="container-fluid px-0" style="max-width:860px">

  <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div>
      <h3 class="mb-0">Add New Agent</h3>
      <p class="ph-desc" style="color:var(--muted);font-size:.84rem;margin:2px 0 0">Register an agent who submits passports to the desk.</p>
    </div>
    <a href="{{ route('agents.index') }}" class="btn btn-outline-secondary btn-sm">
      <i class="fas fa-arrow-left me-1"></i> Back to Agents
    </a>
  </div>

  <div class="card">
    <div class="card-body p-3 p-md-4">
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
          <div class="col-md-6">
            <label class="form-label">Agent Name <span class="text-danger">*</span></label>
            <input type="text"
                   name="name"
                   class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name') }}"
                   placeholder="e.g. Md. Rahim Uddin"
                   required>
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- Phone --}}
          <div class="col-md-6">
            <label class="form-label">Phone</label>
            <input type="text"
                   name="phone"
                   class="form-control @error('phone') is-invalid @enderror"
                   value="{{ old('phone') }}"
                   placeholder="e.g. 01700-000000">
            @error('phone')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- Email --}}
          <div class="col-12">
            <label class="form-label">Email</label>
            <input type="email"
                   name="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}"
                   placeholder="agent@example.com">
            @error('email')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- Address --}}
          <div class="col-12">
            <label class="form-label">Address</label>
            <textarea name="address" rows="3"
                      class="form-control @error('address') is-invalid @enderror"
                      placeholder="Street, City, District">{{ old('address') }}</textarea>
            @error('address')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="mt-4 d-flex gap-2">
          <button type="submit" class="btn btn-primary px-4">
            <i class="fas fa-check me-1"></i> Save Agent
          </button>
          <a href="{{ route('agents.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
