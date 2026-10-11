@extends('backend.master')

@section('page-title', 'Passport Officers')
@section('page-subtitle', 'Officers responsible for processing')

@section('content')
{{-- ============================================================
     ডিজাইন নোট (অফিসার লিস্টে যা কাজ হয়েছে):
     • নতুন ফিল্টার কার্ড: সার্চ, স্ট্যাটাস, তারিখ রেঞ্জ, সর্ট, পার-পেজ + পেজিনেশন।
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

  {{-- FILTERS: search + status + date range + sort + per page --}}
  <form method="GET" id="filtersForm" class="card p-3 p-md-4 mb-3">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
      <div class="section-title mb-0"><i class="fas fa-sliders"></i> Filter &amp; Search</div>
      <a href="{{ route('officers.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-rotate-left me-1"></i> Reset
      </a>
    </div>

    <div class="row g-3 align-items-end">
      <div class="col-12 col-lg-4">
        <label class="form-label">Search</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-search"></i></span>
          <input type="text" name="q" value="{{ $search }}" class="form-control" placeholder="Name / Phone / Email / Address">
        </div>
      </div>

      <div class="col-6 col-lg-2">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
          <option value="">All</option>
          <option value="ACTIVE"   {{ $status==='ACTIVE'?'selected':'' }}>Active</option>
          <option value="INACTIVE" {{ $status==='INACTIVE'?'selected':'' }}>Inactive</option>
        </select>
      </div>

      <div class="col-6 col-lg-2">
        <label class="form-label">Sort</label>
        <select name="sort_by" class="form-select auto-submit">
          <option value="latest"  {{ $sortBy==='latest'?'selected':'' }}>Latest</option>
          <option value="oldest"  {{ $sortBy==='oldest'?'selected':'' }}>Oldest</option>
          <option value="name_az" {{ $sortBy==='name_az'?'selected':'' }}>Name A→Z</option>
          <option value="name_za" {{ $sortBy==='name_za'?'selected':'' }}>Name Z→A</option>
        </select>
      </div>

      <div class="col-6 col-lg-2">
        <label class="form-label">Per Page</label>
        <select name="per_page" class="form-select auto-submit">
          @foreach([10,25,50,100] as $pp)
            <option value="{{ $pp }}" {{ (int)$perPage===$pp?'selected':'' }}>{{ $pp }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-12 col-lg-2">
        <label class="form-label">Date Range</label>
        <input type="text" id="dateRange" class="form-control" placeholder="Select range"
               value="{{ $dateFrom && $dateTo ? \Carbon\Carbon::parse($dateFrom)->format('d M Y').' to '.\Carbon\Carbon::parse($dateTo)->format('d M Y') : '' }}">
        <input type="hidden" name="date_from" id="date_from" value="{{ $dateFrom }}">
        <input type="hidden" name="date_to" id="date_to" value="{{ $dateTo }}">
      </div>

      <div class="col-12 d-flex flex-wrap gap-2">
        <button type="button" class="preset" data-preset="today">Today</button>
        <button type="button" class="preset" data-preset="7">Last 7 days</button>
        <button type="button" class="preset" data-preset="30">Last 30 days</button>
        <button type="button" class="preset" data-preset="month">This month</button>
        <button type="button" class="preset" id="clearDate">Clear date</button>
      </div>

      <div class="col-12 d-flex gap-2">
        <button type="submit" class="btn btn-apply px-4"><i class="fas fa-filter me-1"></i> Apply Filters</button>
      </div>
    </div>
  </form>

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
              <td>{{ ($officers->currentPage()-1)*$officers->perPage() + $loop->iteration }}</td>
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

    {{-- Pagination --}}
    <div class="mt-3 px-1 d-flex justify-content-between align-items-center flex-wrap gap-2">
      <div class="text-muted small">
        Showing
        @if($officers->total()) {{ $officers->firstItem() }}–{{ $officers->lastItem() }} @else 0 @endif
        of {{ $officers->total() }} results
      </div>
      <div>{{ $officers->links() }}</div>
    </div>
  </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.auto-submit').forEach(function (el) {
      el.addEventListener('change', function () {
        document.getElementById('filtersForm').submit();
      });
    });

    var df = document.getElementById('date_from');
    var dt = document.getElementById('date_to');
    var fp = flatpickr('#dateRange', {
      mode: 'range',
      dateFormat: 'd M Y',
      onChange: function (sel) {
        if (sel.length === 2) {
          var iso = function (d) { return new Date(d.getTime() - d.getTimezoneOffset() * 60000).toISOString().slice(0, 10); };
          df.value = iso(sel[0]);
          dt.value = iso(sel[1]);
        }
      }
    });

    function setRange(start, end) {
      fp.setDate([start, end], true);
      var iso = function (d) { return new Date(d.getTime() - d.getTimezoneOffset() * 60000).toISOString().slice(0, 10); };
      df.value = iso(start);
      dt.value = iso(end);
      document.getElementById('filtersForm').submit();
    }

    var today = new Date(); today.setHours(0, 0, 0, 0);
    document.querySelectorAll('.preset').forEach(function (btn) {
      btn.addEventListener('click', function () {
        document.querySelectorAll('.preset').forEach(function (b) { b.classList.remove('active'); });
        btn.classList.add('active');
        var val = btn.dataset.preset;
        if (val === 'today') {
          setRange(new Date(today), new Date(today));
        } else if (val === 'month') {
          setRange(new Date(today.getFullYear(), today.getMonth(), 1), new Date(today.getFullYear(), today.getMonth() + 1, 0));
        } else {
          var n = parseInt(val, 10);
          var start = new Date(today); start.setDate(today.getDate() - (n - 1));
          setRange(start, new Date(today));
        }
      });
    });

    var clearBtn = document.getElementById('clearDate');
    if (clearBtn) {
      clearBtn.addEventListener('click', function () {
        fp.clear();
        df.value = '';
        dt.value = '';
        document.getElementById('filtersForm').submit();
      });
    }
  });
</script>
@endpush
@endsection
