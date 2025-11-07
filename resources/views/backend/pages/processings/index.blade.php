@extends('backend.master')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.min.css">

<style>
  :root{
    --bg:#f3f7ff;
    --card:#ffffff;
    --ink:#0f172a;
    --muted:#64748b;
    --line:#e2e8f0;
    --blue:#2563eb;
    --blue2:#60a5fa;
    --teal:#0ea5e9;
    --green:#22c55e;
    --danger:#ef4444;
  }

  body{ background:var(--bg); }

  .page-head{
    background:linear-gradient(120deg,var(--blue),var(--teal));
    color:#fff; border-radius:16px; padding:18px 24px;
    box-shadow:0 12px 28px rgba(37,99,235,.25);
    display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;
  }
  .page-head h3{ font-weight:800; margin:0; }
  .page-head a.btn{ background:#fff; color:var(--blue); font-weight:700; border-radius:12px; }

  .summary .pill{
    display:inline-flex; align-items:center; gap:6px;
    padding:8px 16px; border-radius:25px;
    font-size:13px; font-weight:700; color:#fff;
    box-shadow:0 4px 10px rgba(0,0,0,.1);
    transition:transform .2s, box-shadow .2s;
  }
  .summary .pill:hover{ transform:translateY(-2px); box-shadow:0 6px 14px rgba(0,0,0,.15); }

  .summary .total{ background:#6366f1; }
  .summary .pending{ background:#facc15; color:#111; }
  .summary .inprogress{ background:#0ea5e9; }
  .summary .done{ background:#16a34a; }
  .summary .rejected{ background:#ef4444; }

  .filter-card{
    background:var(--card); border:1px solid var(--line);
    border-radius:16px; padding:20px; margin-top:16px;
    box-shadow:0 10px 24px rgba(0,0,0,.06); animation:fadeIn .6s ease;
  }
  @keyframes fadeIn{ from{opacity:0; transform:translateY(6px)} to{opacity:1; transform:translateY(0)} }
  .form-label{ font-weight:700; color:var(--ink); margin-bottom:6px; }
  .form-control, .form-select{
    border:1px solid var(--line); border-radius:10px; background:#f9fbff; transition:all .25s;
  }
  .form-control:focus, .form-select:focus{
    border-color:var(--blue2); box-shadow:0 0 0 3px rgba(96,165,250,.22); background:#fff;
  }
  .btn-apply{
    background:linear-gradient(135deg,var(--blue),var(--blue2)); color:#fff; font-weight:800; border:none;
    border-radius:10px; box-shadow:0 6px 18px rgba(37,99,235,.25);
  }
  .btn-reset{
    background:linear-gradient(135deg,#e2e8f0,#f8fafc); color:#0f172a; font-weight:800; border:none;
    border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,.1);
  }

  .table-container{ margin-top:20px; border-radius:16px; overflow:hidden; border:1px solid var(--line); }
  .table{ font-size:13px; background:#e9f3ff; }
  .table thead th{
    background:linear-gradient(135deg,var(--blue),var(--blue2));
    color:#fff; border:none; position:sticky; top:0; z-index:3;
  }
  .table td, .table th{ white-space:nowrap; vertical-align:middle; padding:8px 12px; }
  .table tbody tr:hover{ background:#e7f1ff; transform:translateY(-1px); box-shadow:0 10px 18px rgba(2,8,20,.06); }

  .badge{ font-size:12px; padding:5px 10px; border-radius:10px; }
  .badge.bg-success{ background:var(--green) !important; }
  .badge.bg-warning{ background:#facc15 !important; color:#111 !important; }
  .badge.bg-danger{ background:var(--danger) !important; }
  .badge.bg-secondary{ background:#94a3b8 !important; }
  .badge.bg-dark{ background:#111827 !important; }

  @media (max-width:768px){
    .filter-card .col-md-2, .filter-card .col-md-3 { flex:0 0 48%; max-width:48%; }
  }
</style>

<div class="container-fluid py-3 py-md-4">
  <div class="page-head">
    <h3>Processing List</h3>
    <a href="{{ route('processings.create') }}" class="btn"><i class="fas fa-plus"></i> New Processing</a>
  </div>

  <div class="summary mt-3 mb-2 d-flex flex-wrap gap-2">
    <span class="pill total">Total: <strong>{{ $totals['total'] }}</strong></span>
    <span class="pill pending">Pending: <strong>{{ $totals['pending'] }}</strong></span>
    <span class="pill inprogress">In-Progress: <strong>{{ $totals['in_progress'] }}</strong></span>
    <span class="pill done">Done: <strong>{{ $totals['done'] }}</strong></span>
    <span class="pill rejected">Rejected: <strong>{{ $totals['rejected'] }}</strong></span>
  </div>

  {{-- Filters --}}
  <div class="filter-card">
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
        <button class="btn btn-apply px-4" type="submit"><i class="fas fa-filter"></i> Apply</button>
        <a href="{{ route('processings.index') }}" class="btn btn-reset px-4"><i class="fas fa-undo"></i> Reset</a>
      </div>
    </form>
  </div>

  {{-- Table --}}
  <div class="table-container mt-3">
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
            <th>Collection Status</th>
            <th>Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($processings as $i => $proc)
            @php
              $collectedRow = \App\Models\PassportCollection::where('passport_id',$proc->passport_id)->exists();
              $procBadge = match($proc->status){
                'DONE'=>'bg-success','IN_PROGRESS'=>'bg-warning text-dark',
                'PENDING'=>'bg-secondary','REJECTED'=>'bg-danger',default=>'bg-secondary'};
              $collectBadge = $collectedRow ? 'bg-success' : 'bg-dark';
              $collectText  = $collectedRow ? 'Collected' : 'Not Collected';
            @endphp
            <tr>
              <td>{{ ($processings->currentPage()-1)*$processings->perPage() + $i + 1 }}</td>
              <td>{{ $proc->passport->passport_number ?? '—' }}</td>
              <td class="fw-semibold text-start">{{ $proc->passport->applicant_name ?? '—' }}</td>
              <td>{{ $proc->employee->name ?? '—' }}</td>
              <td>{{ $proc->agency->name ?? 'Unassigned' }}</td>
              <td><span class="badge {{ $procBadge }}">{{ $proc->status }}</span></td>
              <td><span class="badge {{ $collectBadge }}">{{ $collectText }}</span></td>
              <td>{{ $proc->created_at->format('d M Y, h:i A') }}</td>
              <td>
                {{-- 🔒 If collected, disable Edit/Delete --}}
                @if($collectedRow)
                  <button class="btn btn-sm btn-secondary" disabled><i class="fas fa-lock"></i></button>
                @else
                  <a href="{{ route('processings.edit',$proc->id) }}" class="btn btn-sm btn-warning btn-edit"><i class="fas fa-edit"></i></a>
                  <form action="{{ route('processings.destroy',$proc->id) }}" method="POST" class="d-inline delete-form">
                    @csrf @method('DELETE')
                    <button type="button" class="btn btn-sm btn-danger btn-delete"><i class="fas fa-trash"></i></button>
                  </form>
                @endif
              </td>
            </tr>
          @empty
            <tr><td colspan="9" class="text-muted">No records found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-3">{{ $processings->links() }}</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#6b7280',
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
        confirmButtonColor: '#0ea5e9',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, edit it!',
        cancelButtonText: 'Cancel'
      }).then((res)=>{ if(res.isConfirmed){ window.location.href = url; } });
    });
  });
});
</script>
@endsection
