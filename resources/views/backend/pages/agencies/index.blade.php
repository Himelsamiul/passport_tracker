@extends('backend.master')

@section('page-title', 'Agencies')
@section('page-subtitle', 'Partner agencies processing abroad')

@section('content')
{{-- ============================================================
     ডিজাইন নোট (এজেন্সি লিস্টে যা কাজ হয়েছে):
     • লাইট টেবিল-স্ক্রল স্টাইল বাদ — নতুন ডার্ক থিমে কার্ডে রেসপনসিভ টেবিল।
     • হেডারে "Add Agency" বাটন; স্ট্যাটাস সফট ব্যাজ; টুলটিপ আগের মতো।
     • "used/locked" চেক (প্রসেসিংয়ে লিংকড কিনা) লজিক অক্ষুণ্ণ।
     ============================================================ --}}
<div class="container-fluid px-0">

  {{-- Page header --}}
  <div class="page-head d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div>
      <h3>Agencies</h3>
      <p class="ph-desc">Partner agencies that process passports abroad.</p>
    </div>
    @if (can_do('agencies.create'))
      <a href="{{ route('agencies.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Add Agency
      </a>
    @endif
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="card p-2 p-md-3">
    <div class="table-responsive">
      <table class="table table-bordered table-striped align-middle text-center">
        <thead>
          <tr>
            <th style="width:56px">#</th>
            <th>Name</th>
            <th>Category</th>
            <th>Contact Person</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Address</th>
            <th>Status</th>
            <th>Notes</th>
            <th>Date &amp; Time</th>
            <th style="width:210px">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($agencies as $key => $agency)
            @php
              // Check if agency is already used in any processing
              $isUsed = \App\Models\PassportProcessing::where('agency_id', $agency->id)->exists();
            @endphp

            <tr class="{{ $isUsed ? 'lock-row' : '' }}">
              <td>{{ $key + 1 }}</td>
              <td class="fw-semibold">{{ $agency->name }}</td>
              <td>{{ $agency->category->name ?? 'N/A' }}</td>
              <td>{{ $agency->contact_person }}</td>
              <td>{{ $agency->phone }}</td>
              <td>{{ $agency->email }}</td>
              <td><div class="addr" style="max-width:200px">{{ $agency->address }}</div></td>
              <td>
                <span class="badge {{ $agency->status === 'ACTIVE' ? 'bg-success' : 'bg-secondary' }}">
                  {{ $agency->status }}
                </span>
              </td>
              <td><div class="addr" style="max-width:180px">{{ $agency->notes }}</div></td>
              <td>{{ $agency->created_at->format('d M Y, h:i A') }}</td>
              <td>
                <div class="d-flex justify-content-center flex-wrap gap-1">
                  @if (can_do('agencies.edit'))
                    <a href="{{ route('agencies.edit', $agency->id) }}" class="btn btn-sm btn-warning">
                      <i class="fas fa-pen"></i> Edit
                    </a>
                  @endif

                  @if($isUsed || !can_do('agencies.delete'))
                    <button type="button"
                            class="btn btn-sm btn-secondary"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="{{ $isUsed ? 'You can’t delete this agency — it’s already used in a processing.' : 'You do not have delete permission.' }}">
                      <i class="fas fa-lock"></i> Locked
                    </button>
                  @else
                    <a href="{{ route('agencies.delete', $agency->id) }}"
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Are you sure you want to delete this agency?')">
                      <i class="fas fa-trash-can"></i> Delete
                    </a>
                  @endif
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="11">
                <div class="empty-state">
                  <div class="es-icon"><i class="fas fa-building"></i></div>
                  <div class="fw-semibold mb-1" style="color:var(--text-soft)">No agencies found</div>
                  <div>Add your first partner agency.</div>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
