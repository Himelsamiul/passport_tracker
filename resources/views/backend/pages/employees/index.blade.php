@extends('backend.master')

@section('page-title', 'Employees')
@section('page-subtitle', 'Internal staff handling passport files')

@section('content')
{{-- ============================================================
     ডিজাইন নোট (কর্মী লিস্টে যা কাজ হয়েছে):
     • নতুন ফিল্টার কার্ড: সার্চ, তারিখ রেঞ্জ+প্রিসেট, সর্ট, পার-পেজ + পেজিনেশন।
     • প্লেইন লাইট টেবিল বাদ — নতুন ডার্ক থিমে পেজ হেডার + কার্ডে টেবিল।
     • হেডারে "Add Employee" বাটন; টেবিলে সফট অ্যাকশন বাটন ও empty-state।
     • "used/locked" চেক লজিক আগের মতোই (পাসপোর্ট/প্রসেসিং/কলেকশনে লিংকড কিনা)।
     ============================================================ --}}
<div class="container-fluid px-0">

  {{-- Page header --}}
  <div class="page-head d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div>
      <h3>Employees</h3>
      <p class="ph-desc">Team members who receive and process passport files.</p>
    </div>
    @if (can_do('employees.create'))
      <a href="{{ route('employees.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Add Employee
      </a>
    @endif
  </div>

  {{-- Flash --}}
  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
  @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div>   @endif

  {{-- FILTERS: search + date range + sort + per page --}}
  <form method="GET" id="filtersForm" class="card p-3 p-md-4 mb-3">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
      <div class="section-title mb-0"><i class="fas fa-sliders"></i> Filter &amp; Search</div>
      <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-rotate-left me-1"></i> Reset
      </a>
    </div>

    <div class="row g-3 align-items-end">
      <div class="col-12 col-lg-4">
        <label class="form-label">Search</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-search"></i></span>
          <input type="text" name="q" value="{{ $search }}" class="form-control" placeholder="Name / Phone / Email / Designation">
        </div>
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

      <div class="col-12 col-lg-4">
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
      <table class="table table-bordered align-middle">
        <thead>
          <tr>
            <th style="width:56px">#</th>
            <th>Name</th>
            <th>Designation</th>
            <th>Phone</th>
            <th>Email</th>
            <th style="width:220px">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($employees as $key => $employee)
            @php
              // Check if employee is used anywhere
              $isUsed =
                \App\Models\Passport::where('employee_id', $employee->id)->exists() ||
                \App\Models\PassportProcessing::where('employee_id', $employee->id)->exists() ||
                \App\Models\PassportCollection::where('employee_id', $employee->id)->exists();
            @endphp

            <tr class="{{ $isUsed ? 'lock-row' : '' }}">
              <td>{{ ($employees->currentPage()-1)*$employees->perPage() + $loop->iteration }}</td>
              <td class="fw-semibold">{{ $employee->name }}</td>
              <td>{{ $employee->designation }}</td>
              <td>{{ $employee->phone }}</td>
              <td>{{ $employee->email }}</td>
              <td>
                <div class="d-flex flex-wrap gap-1">
                  @if (can_do('employees.edit'))
                    <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-sm btn-warning">
                      <i class="fas fa-pen"></i> Edit
                    </a>
                  @endif

                  @if($isUsed || !can_do('employees.delete'))
                    <button type="button"
                            class="btn btn-sm btn-secondary"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="{{ $isUsed ? 'You can’t delete this employee — already linked with other records.' : 'You do not have delete permission.' }}">
                      <i class="fas fa-lock"></i> Locked
                    </button>
                  @else
                    <a href="{{ route('employees.delete', $employee->id) }}"
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Are you sure you want to delete this employee?')">
                      <i class="fas fa-trash-can"></i> Delete
                    </a>
                  @endif
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6">
                <div class="empty-state">
                  <div class="es-icon"><i class="fas fa-users"></i></div>
                  <div class="fw-semibold mb-1" style="color:var(--text-soft)">No employees found</div>
                  <div>Add your first employee to get started.</div>
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
        @if($employees->total()) {{ $employees->firstItem() }}–{{ $employees->lastItem() }} @else 0 @endif
        of {{ $employees->total() }} results
      </div>
      <div>{{ $employees->links() }}</div>
    </div>
  </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Auto-submit selects
    document.querySelectorAll('.auto-submit').forEach(function (el) {
      el.addEventListener('change', function () {
        document.getElementById('filtersForm').submit();
      });
    });

    // Date range + presets
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
