@extends('backend.master')

@section('page-title', 'Passport Officers')
@section('page-subtitle', 'Officers responsible for processing')

@section('content')
{{-- ============================================================
     ডিজাইন নোট (অফিসার লিস্টে যা কাজ হয়েছে):
     • নতুন ডার্ক থিমে পেজ হেডার + "Add Officer" বাটন; টেবিল কার্ডে।
     • স্ট্যাটাস সফট ব্যাজ; "used/locked" লজিক আগের মতো।
     ============================================================ --}}
<div class="container-fluid px-0">

  {{-- Page header --}}
  <div class="page-head d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div>
      <h3>Passport Officers</h3>
      <p class="ph-desc">Officers responsible for passport processing.</p>
    </div>
    @if (can_do('officers.create'))
      <a href="{{ route('officers.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Add Officer
      </a>
    @endif
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  <div class="card p-2 p-md-3">
    <div class="table-responsive">
      <table class="table table-bordered table-striped align-middle text-center">
        <thead>
          <tr>
            <th style="width:56px">#</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Address</th>
            <th>Status</th>
            <th>Notes</th>
            <th style="width:210px">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($officers as $i => $o)
            @php
              // Check if this officer is used in any passport
              $isUsed = \App\Models\Passport::where('passport_officer_id', $o->id)->exists();
            @endphp

            <tr class="{{ $isUsed ? 'lock-row' : '' }}">
              <td>{{ $i+1 }}</td>
              <td class="fw-semibold">{{ $o->name }}</td>
              <td>{{ $o->phone }}</td>
              <td>{{ $o->email }}</td>
              <td><div class="addr" style="max-width:200px">{{ $o->address }}</div></td>
              <td>
                <span class="badge {{ $o->status === 'ACTIVE' ? 'bg-success' : 'bg-secondary' }}">
                  {{ $o->status }}
                </span>
              </td>
              <td><div class="addr" style="max-width:160px">{{ $o->notes }}</div></td>
              <td>
                <div class="d-flex justify-content-center flex-wrap gap-1">
                  @if (can_do('officers.edit'))
                    <a href="{{ route('officers.edit', $o->id) }}" class="btn btn-sm btn-warning">
                      <i class="fas fa-pen"></i> Edit
                    </a>
                  @endif

                  @if($isUsed || !can_do('officers.delete'))
                    <button type="button"
                            class="btn btn-sm btn-secondary"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="{{ $isUsed ? 'You can’t delete this officer — they are already assigned to a passport.' : 'You do not have delete permission.' }}">
                      <i class="fas fa-lock"></i> Locked
                    </button>
                  @else
                    <a href="{{ route('officers.delete', $o->id) }}"
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Are you sure you want to delete this officer?')">
                      <i class="fas fa-trash-can"></i> Delete
                    </a>
                  @endif
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8">
                <div class="empty-state">
                  <div class="es-icon"><i class="fas fa-user-shield"></i></div>
                  <div class="fw-semibold mb-1" style="color:var(--text-soft)">No officers found</div>
                  <div>Add your first passport officer.</div>
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
