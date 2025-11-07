@extends('backend.master')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.min.css">

<style>
  .page-head{background:linear-gradient(120deg,#2563eb,#0ea5e9);color:#fff;border-radius:12px;padding:16px 20px;box-shadow:0 14px 28px rgba(2,8,20,.12)}
  .table thead th{background:#0f172a;color:#fff;position:sticky;top:0;z-index:3}
  .badge{padding:.45rem .65rem;border-radius:.6rem;font-weight:700}
</style>

<div class="container-fluid py-4">
  <div class="page-head d-flex align-items-center justify-content-between">
    <h5 class="m-0 fw-bold">Registered Users</h5>
    <a href="{{ route('register.form') }}" class="btn btn-light btn-sm">+ New</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success mt-3">{{ session('success') }}</div>
  @endif

  {{-- Filters --}}
  <form method="GET" class="card mt-3">
    <div class="card-body row g-2 align-items-end">
      <div class="col-md-3">
        <label class="form-label fw-semibold">Search</label>
        <input type="text" class="form-control" name="q" value="{{ $q ?? '' }}" placeholder="Name or Email">
      </div>
      <div class="col-md-3">
        <label class="form-label fw-semibold">Status</label>
        <select name="status" class="form-select">
          <option value="" {{ ($status??'')===''?'selected':'' }}>All</option>
          <option value="active" {{ ($status??'')==='active'?'selected':'' }}>Active</option>
          <option value="inactive" {{ ($status??'')==='inactive'?'selected':'' }}>Inactive</option>
        </select>
      </div>
      <div class="col-md-3">
        <button class="btn btn-primary" type="submit">Apply</button>
        <a class="btn btn-secondary" href="{{ route('register.list') }}">Reset</a>
      </div>
    </div>
  </form>

  <div class="card mt-3">
    <div class="table-responsive">
      <table class="table table-bordered align-middle">
        <thead>
          <tr>
            <th style="width:60px">#</th>
            <th>Name</th>
            <th>Email</th>
            <th style="width:140px">Status</th>
            <th style="width:200px">Created</th>
            <th style="width:220px" class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($registrations as $i => $u)
            <tr>
              <td>{{ $registrations->firstItem() + $i }}</td>
              <td class="fw-semibold">{{ $u->name }}</td>
              <td>{{ $u->email }}</td>
              <td>
                <span class="badge {{ $u->status==='active' ? 'bg-success' : 'bg-danger' }}">
                  {{ ucfirst($u->status) }}
                </span>
              </td>
              <td>{{ $u->created_at?->format('d M Y, h:i A') }}</td>
              <td class="text-center">
                {{-- Toggle status --}}
                <form action="{{ route('register.status', $u->id) }}" method="POST" class="d-inline-block">
                  @csrf @method('PATCH')
                  <button type="submit" class="btn btn-sm {{ $u->status==='active'?'btn-warning':'btn-success' }}">
                    {{ $u->status==='active' ? 'Deactivate' : 'Activate' }}
                  </button>
                </form>

                {{-- Delete with confirm --}}
                <form action="{{ route('register.destroy', $u->id) }}" method="POST" class="d-inline-block delete-form">
                  @csrf @method('DELETE')
                  <button type="button" class="btn btn-sm btn-danger btn-delete">Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="6" class="text-muted text-center">No registered users found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="p-3">
      {{ $registrations->links() }}
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('.btn-delete').forEach(btn=>{
  btn.addEventListener('click', (e)=>{
    const form = btn.closest('.delete-form');
    Swal.fire({
      title: 'Delete this user?',
      text: 'This action cannot be undone.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete',
      cancelButtonText: 'Cancel'
    }).then(res=>{
      if(res.isConfirmed) form.submit();
    });
  });
});
</script>
@endsection
