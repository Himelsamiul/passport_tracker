@extends('backend.master')

@section('page-title', 'Roles & Permissions')
@section('page-subtitle', 'Who can see and do what')

@section('content')
{{-- ============================================================
     ডিজাইন নোট (রোল লিস্টে যা কাজ হয়েছে):
     • নতুন "Roles & Permissions" পেজ — সুপার অ্যাডমিন এখানে রোল বানায় ও
       প্রতিটি মডিউলের View / Create / Edit / Delete পারমিশন ঠিক করে।
     • প্রতি রোলে পারমিশন কাউন্ট + কয়টি ইউজার ব্যবহার করছে তা দেখায়।
     • ইউজার-যুক্ত রোল ডিলিট করা যায় না (নিরাপত্তা)।
     ============================================================ --}}
<div class="container-fluid px-0">

  <div class="page-head d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div>
      <h3>Roles</h3>
      <p class="ph-desc">Each role maps to a set of module permissions. Users without a role are super admins.</p>
    </div>
    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
      <i class="fas fa-plus me-1"></i> New Role
    </a>
  </div>

  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
  @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div> @endif

  <div class="card p-2 p-md-3">
    <div class="table-responsive">
      <table class="table align-middle">
        <thead>
          <tr>
            <th>Role</th>
            <th>Description</th>
            <th>Permissions</th>
            <th style="width:90px">Users</th>
            <th style="width:190px">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($roles as $role)
            <tr>
              <td class="fw-semibold">{{ $role->name }}</td>
              <td class="text-muted" style="font-size:.83rem">{{ $role->description ?? '—' }}</td>
              <td>
                @php $permCount = count($role->permissions ?? []); @endphp
                <span class="badge bg-primary">{{ $permCount }} abilities</span>
                @if ($permCount > 0)
                  <span class="badge bg-light">{{ implode(', ', array_slice($role->permissions, 0, 3)) }}</span>
                  @if ($permCount > 3)
                    <span class="badge bg-light">+{{ $permCount - 3 }} more</span>
                  @endif
                @endif
              </td>
              <td><span class="badge bg-secondary">{{ $role->users_count }}</span></td>
              <td>
                <div class="d-flex gap-1">
                  <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-warning"><i class="fas fa-pen"></i> Edit</a>
                  <a href="{{ route('admin.roles.destroy', $role) }}" class="btn btn-sm btn-danger"
                     onclick="return confirm('Delete this role?')"><i class="fas fa-trash-can"></i> Delete</a>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5">
                <div class="empty-state">
                  <div class="es-icon"><i class="fas fa-user-lock"></i></div>
                  <div class="fw-semibold mb-1" style="color:var(--text-soft)">No roles yet</div>
                  <div>Create the first role to start assigning limited access.</div>
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
