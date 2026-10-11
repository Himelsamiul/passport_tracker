@extends('backend.master')

@section('page-title', 'Admin Users')
@section('page-subtitle', 'Panel logins and their roles')

@section('content')
{{-- ============================================================
     ডিজাইন নোট (অ্যাডমিন ইউজার লিস্টে যা কাজ হয়েছে):
     • সুপার অ্যাডমিন এখানে নতুন প্যানেল লগইন বানায় ও রোল অ্যাসাইন করে।
     • রোল-বিহীন ইউজার = Super Admin ব্যাজ; রোল-যুক্ত হলে রোলের নাম দেখায়।
     • নিজের অ্যাকাউন্ট ডিলিট করা যায় না; শেষ সুপার অ্যাডমিনও ডিলিট করা যায় না।
     ============================================================ --}}
<div class="container-fluid px-0">

  <div class="page-head d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div>
      <h3>Admin Users</h3>
      <p class="ph-desc">Panel logins. Users without a role have unrestricted access.</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
      <i class="fas fa-plus me-1"></i> New Admin User
    </a>
  </div>

  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
  @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div> @endif

  {{-- Quick search + role filter --}}
  <form method="GET" class="card p-2 p-md-3 mb-3">
    <div class="d-flex flex-wrap gap-2 align-items-center">
      <div class="input-group flex-grow-1" style="min-width:220px;max-width:420px">
        <span class="input-group-text"><i class="fas fa-search"></i></span>
        <input type="text" name="q" value="{{ $search }}" class="form-control" placeholder="Search by name or email">
      </div>
      <select name="role_id" class="form-select" style="max-width:220px" onchange="this.form.submit()">
        <option value="">All roles</option>
        <option value="none" {{ $roleFilter==='none'?'selected':'' }}>Super Admin (no role)</option>
        @foreach ($roles as $role)
          <option value="{{ $role->id }}" {{ $roleFilter==$role->id?'selected':'' }}>{{ $role->name }}</option>
        @endforeach
      </select>
      <button type="submit" class="btn btn-apply px-3"><i class="fas fa-filter me-1"></i> Search</button>
      @if ($search !== '' || $roleFilter !== '')
        <a href="{{ route('admin.users.index') }}" class="btn btn-reset px-3"><i class="fas fa-rotate-left me-1"></i> Reset</a>
      @endif
    </div>
  </form>

  <div class="card p-2 p-md-3">
    <div class="table-responsive">
      <table class="table align-middle">
        <thead>
          <tr>
            <th>User</th>
            <th>Role</th>
            <th>Joined</th>
            <th style="width:190px">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($users as $u)
            <tr>
              <td>
                <div class="d-flex align-items-center gap-2">
                  <span class="avatar avatar-sm">{{ mb_strtoupper(mb_substr($u->name, 0, 1)) }}</span>
                  <div>
                    <div class="fw-semibold">{{ $u->name }}</div>
                    <div class="text-muted" style="font-size:.78rem">{{ $u->email }}</div>
                  </div>
                </div>
              </td>
              <td>
                @if ($u->role)
                  <span class="badge bg-primary">{{ $u->role->name }}</span>
                @else
                  <span class="badge bg-success">Super Admin</span>
                @endif
              </td>
              <td>{{ $u->created_at?->format('d M Y') }}</td>
              <td>
                <div class="d-flex gap-1">
                  <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm btn-warning"><i class="fas fa-pen"></i> Edit</a>
                  <a href="{{ route('admin.users.destroy', $u) }}" class="btn btn-sm btn-danger"
                     onclick="return confirm('Delete this admin user?')"><i class="fas fa-trash-can"></i> Delete</a>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4">
                <div class="empty-state">
                  <div class="es-icon"><i class="fas fa-users-gear"></i></div>
                  <div class="fw-semibold mb-1" style="color:var(--text-soft)">No admin users</div>
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
