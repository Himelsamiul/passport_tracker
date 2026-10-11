@extends('backend.master')

@section('page-title', 'Registrations')
@section('page-subtitle', 'Public registration submissions')

@section('content')
{{-- ============================================================
     ডিজাইন নোট (রেজিস্ট্রেশন লিস্টে যা কাজ হয়েছে):
     • নীল গ্র্যাডিয়েন্ট হেডার বাদ — নতুন ডার্ক থিমে পেজ হেডার + "+ New" বাটন।
     • ফিল্টার (সার্চ/স্ট্যাটাস), অ্যাক্টিভ-টগল ও SweetAlert ডিলিট — সব লজিক আগের মতো।
     ============================================================ --}}
<div class="container-fluid px-0">

  <div class="page-head d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div>
      <h3>Registered Users</h3>
      <p class="ph-desc">People who registered through the public form.</p>
    </div>
    @if (can_do('registrations.create'))
      <a href="{{ route('register.form') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus me-1"></i> New
      </a>
    @endif
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  {{-- Filters --}}
  <form method="GET" class="card p-3 p-md-4 mb-3">
    <div class="row g-3 align-items-end">
      <div class="col-md-4">
        <label class="form-label">Search</label>
        <input type="text" class="form-control" name="q" value="{{ $q ?? '' }}" placeholder="Name or Email">
      </div>
      <div class="col-md-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
          <option value="" {{ ($status??'')===''?'selected':'' }}>All</option>
          <option value="active" {{ ($status??'')==='active'?'selected':'' }}>Active</option>
          <option value="inactive" {{ ($status??'')==='inactive'?'selected':'' }}>Inactive</option>
        </select>
      </div>
      <div class="col-md-4">
        <button class="btn btn-apply px-4" type="submit"><i class="fas fa-filter me-1"></i> Apply</button>
        <a class="btn btn-reset px-4" href="{{ route('register.list') }}"><i class="fas fa-rotate-left me-1"></i> Reset</a>
      </div>
    </div>
  </form>

  <div class="card p-2 p-md-3">
    <div class="table-responsive">
      <table class="table table-bordered align-middle">
        <thead>
          <tr>
            <th style="width:56px">#</th>
            <th>Name</th>
            <th>Email</th>
            <th style="width:130px">Status</th>
            <th style="width:190px">Created</th>
            <th style="width:240px" class="text-center">Actions</th>
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
                <div class="d-flex justify-content-center gap-1">
                  @if (can_do('registrations.edit'))
                    {{-- Toggle status --}}
                    <form action="{{ route('register.status', $u->id) }}" method="POST">
                      @csrf @method('PATCH')
                      <button type="submit" class="btn btn-sm {{ $u->status==='active'?'btn-warning':'btn-success' }}">
                        {{ $u->status==='active' ? 'Deactivate' : 'Activate' }}
                      </button>
                    </form>
                  @endif

                  @if (can_do('registrations.delete'))
                    {{-- Delete with confirm --}}
                    <form action="{{ route('register.destroy', $u->id) }}" method="POST" class="delete-form">
                      @csrf @method('DELETE')
                      <button type="button" class="btn btn-sm btn-danger btn-delete"><i class="fas fa-trash-can"></i> Delete</button>
                    </form>
                  @endif
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6">
                <div class="empty-state">
                  <div class="es-icon"><i class="fas fa-user-plus"></i></div>
                  <div class="fw-semibold mb-1" style="color:var(--text-soft)">No registered users found</div>
                  <div>Registrations from the public form will appear here.</div>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="p-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
      <div class="text-muted small">
        Showing
        @if($registrations->total()) {{ $registrations->firstItem() }}–{{ $registrations->lastItem() }} @else 0 @endif
        of {{ $registrations->total() }} results
      </div>
      {{ $registrations->links() }}
    </div>
  </div>
</div>

@push('scripts')
<script>
document.querySelectorAll('.btn-delete').forEach(btn=>{
  btn.addEventListener('click', (e)=>{
    const form = btn.closest('.delete-form');
    Swal.fire({
      title: 'Delete this user?',
      text: 'This action cannot be undone.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#4e86e8',
      cancelButtonColor: '#3a465e',
      confirmButtonText: 'Yes, delete',
      cancelButtonText: 'Cancel'
    }).then(res=>{
      if(res.isConfirmed) form.submit();
    });
  });
});
</script>
@endpush
@endsection
