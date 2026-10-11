@extends('backend.master')

@section('page-title', 'Processing')
@section('page-subtitle', 'Track each passport through its stages')

@section('content')
{{-- ============================================================
     ডিজাইন নোট (প্রসেসিং লিস্টে যা কাজ হয়েছে):
     • নীল গ্র্যাডিয়েন্ট হেডার ও লাইট থিম বাদ — নতুন ডার্ক থিম।
     • উপরে সামারি চিপ (Total / Pending / In-Progress / Done / Rejected) নতুন সফট স্টাইলে।
     • ফিল্টার কার্ড: স্ট্যাটাস, কর্মী, এজেন্সি, কলেকশন স্ট্যাটাস, সার্চ, তারিখ রেঞ্জ (Flatpickr)।
     • SweetAlert2-এর ডিলিট/এডিট কনফার্মেশন আগের মতোই — শুধু রং থিমের সাথে মেলানো।
     • কলেক্টেড রেকর্ড লক থাকে — লজিক অপরিবর্তিত।
     ============================================================ --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<div class="container-fluid px-0">

  {{-- Page header --}}
  <div class="page-head d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div>
      <h3>Processing List</h3>
      <p class="ph-desc">Every passport currently moving through its processing stages.</p>
    </div>
    @if (can_do('processings.create'))
      <a href="{{ route('processings.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> New Processing
      </a>
    @endif
  </div>

  {{-- Summary chips --}}
  <div class="d-flex flex-wrap gap-2 mb-3">
    <span class="badge bg-primary" style="font-size:.78rem;padding:.5em .9em">Total: {{ $totals['total'] }}</span>
    <span class="badge bg-warning" style="font-size:.78rem;padding:.5em .9em">Pending: {{ $totals['pending'] }}</span>
    <span class="badge bg-info"    style="font-size:.78rem;padding:.5em .9em">In-Progress: {{ $totals['in_progress'] }}</span>
    <span class="badge bg-success" style="font-size:.78rem;padding:.5em .9em">Done: {{ $totals['done'] }}</span>
    <span class="badge bg-danger"  style="font-size:.78rem;padding:.5em .9em">Rejected: {{ $totals['rejected'] }}</span>
  </div>

  {{-- Filters --}}
  <div class="card p-3 p-md-4 mb-3">
    <div class="section-title mb-3"><i class="fas fa-sliders"></i> Filter</div>
    <form method="GET" id="filterForm" class="row g-3 align-items-end">
      <div class="col-md-2">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
          <option value="">All</option>
          @foreach(['PENDING','IN_PROGRESS','DONE','REJECTED'] as $opt)
            <option value="{{ $opt }}" @selected(($status ?? '') === $opt)>{{ $opt }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label">Employee</label>
        <select name="employee_id" class="form-select">
          <option value="">All</option>
          @foreach($employees as $e)
            <option value="{{ $e->id }}" @selected(($employeeId ?? '') == $e->id)>{{ $e->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label">Agency</label>
        <select name="agency_id" class="form-select">
          <option value="">All</option>
          @foreach($agencies as $a)
            <option value="{{ $a->id }}" @selected(($agencyId ?? '') == $a->id)>{{ $a->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label">Collection Status</label>
        <select name="collected" class="form-select">
          <option value="" {{ ($collected ?? '')==='' ? 'selected' : '' }}>All</option>
          <option value="collected" {{ ($collected ?? '')==='collected' ? 'selected' : '' }}>Collected</option>
          <option value="not_collected" {{ ($collected ?? '')==='not_collected' ? 'selected' : '' }}>Not Collected</option>
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label">Search</label>
        <input type="text" name="q" value="{{ $search ?? '' }}" class="form-control" placeholder="Passport / Name">
      </div>
      <div class="col-md-2">
        <label class="form-label">Date Range</label>
        <input type="text" id="dateRange" class="form-control" placeholder="Select date range">
        <input type="hidden" name="date_from" id="date_from" value="{{ $dateFrom ?? '' }}">
        <input type="hidden" name="date_to" id="date_to" value="{{ $dateTo ?? '' }}">
      </div>
      <div class="col-12 d-flex gap-2 mt-1">
        <button class="btn btn-apply px-4" type="submit"><i class="fas fa-filter me-1"></i> Apply</button>
        <a href="{{ route('processings.index') }}" class="btn btn-reset px-4"><i class="fas fa-rotate-left me-1"></i> Reset</a>
      </div>
    </form>
  </div>

  {{-- Table --}}
  <div class="card p-2 p-md-3">
    <div class="table-responsive">
      <table class="table table-bordered text-center align-middle">
        <thead>
          <tr>
            <th>SL</th>
            <th>Passport No</th>
            <th>Applicant</th>
            <th>Employee</th>
            <th>Agency</th>
            <th>Processing Status</th>
            <th>Collection</th>
            <th>Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($processings as $i => $proc)
            @php
              $collectedRow = \App\Models\PassportCollection::where('passport_id',$proc->passport_id)->exists();
              $procBadge = match($proc->status){
                'DONE'=>'bg-success','IN_PROGRESS'=>'bg-warning',
                'PENDING'=>'bg-secondary','REJECTED'=>'bg-danger',default=>'bg-secondary'};
              $collectBadge = $collectedRow ? 'bg-success' : 'bg-light';
              $collectText  = $collectedRow ? 'Collected' : 'Not Collected';
            @endphp
            <tr>
              <td>{{ ($processings->currentPage()-1)*$processings->perPage() + $i + 1 }}</td>
              <td class="fw-semibold">{{ $proc->passport->passport_number ?? '—' }}</td>
              <td class="fw-semibold text-start">{{ $proc->passport->applicant_name ?? '—' }}</td>
              <td>{{ $proc->employee->name ?? '—' }}</td>
              <td>{{ $proc->agency->name ?? 'Unassigned' }}</td>
              <td><span class="badge {{ $procBadge }}">{{ $proc->status }}</span></td>
              <td><span class="badge {{ $collectBadge }}">{{ $collectText }}</span></td>
              <td>{{ $proc->created_at->format('d M Y, h:i A') }}</td>
              <td>
                {{-- If collected or no permission, disable Edit/Delete --}}
                @if($collectedRow || !can_do('processings.edit'))
                  <button class="btn btn-sm btn-secondary" data-bs-toggle="tooltip" title="{{ $collectedRow ? 'Collected passports are locked.' : 'You do not have edit permission.' }}"><i class="fas fa-lock"></i></button>
                @else
                  <a href="{{ route('processings.edit',$proc->id) }}" class="btn btn-sm btn-warning btn-edit" title="Edit"><i class="fas fa-pen"></i></a>
                  @if (can_do('processings.delete'))
                    <form action="{{ route('processings.destroy',$proc->id) }}" method="POST" class="d-inline delete-form">
                      @csrf @method('DELETE')
                      <button type="button" class="btn btn-sm btn-danger btn-delete" title="Delete"><i class="fas fa-trash-can"></i></button>
                    </form>
                  @endif
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9">
                <div class="empty-state">
                  <div class="es-icon"><i class="fas fa-gears"></i></div>
                  <div class="fw-semibold mb-1" style="color:var(--text-soft)">No records found</div>
                  <div>Try adjusting the filters or create a new processing entry.</div>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-3 px-1 d-flex justify-content-between align-items-center flex-wrap gap-2">
      <div class="text-muted small">
        Showing
        @if($processings->total()) {{ $processings->firstItem() }}–{{ $processings->lastItem() }} @else 0 @endif
        of {{ $processings->total() }} results
      </div>
      <div>{{ $processings->links() }}</div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', ()=>{
  // Date range picker
  const df = document.getElementById('date_from');
  const dt = document.getElementById('date_to');
  flatpickr("#dateRange",{
    mode:"range", dateFormat:"d M Y",
    onChange:(sel)=>{
      if(sel.length===2){
        const iso = d => new Date(d.getTime() - d.getTimezoneOffset()*60000).toISOString().slice(0,10);
        df.value = iso(sel[0]); dt.value = iso(sel[1]);
      }
    }
  });

  // SweetAlert2 delete confirm
  document.querySelectorAll('.btn-delete').forEach(btn=>{
    btn.addEventListener('click', (e)=>{
      e.preventDefault();
      const form = btn.closest('.delete-form');
      Swal.fire({
        title: 'Are you sure?',
        text: 'This record will be permanently deleted.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#4e86e8',
        cancelButtonColor: '#3a465e',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
      }).then((res)=>{ if(res.isConfirmed){ form.submit(); } });
    });
  });

  // SweetAlert2 edit confirm
  document.querySelectorAll('.btn-edit').forEach(btn=>{
    btn.addEventListener('click', (e)=>{
      e.preventDefault();
      const url = btn.getAttribute('href');
      Swal.fire({
        title: 'Are you sure?',
        text: 'Do you really want to edit this record?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#4e86e8',
        cancelButtonColor: '#3a465e',
        confirmButtonText: 'Yes, edit it!',
        cancelButtonText: 'Cancel'
      }).then((res)=>{ if(res.isConfirmed){ window.location.href = url; } });
    });
  });
});
</script>
@endpush
@endsection
