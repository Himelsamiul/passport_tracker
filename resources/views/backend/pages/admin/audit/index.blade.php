@extends('backend.master')

@section('page-title', 'Track Record')
@section('page-subtitle', 'Audit trail — which admin did what, and when')

@section('content')
{{-- ============================================================
     ডিজাইন নোট (ট্র্যাক রেকর্ড / অডিট ট্রেইল পেজে যা কাজ হয়েছে):
     • প্রতিটি অ্যাডমিন অ্যাক্টিভিটির লগ: কে, কখন, কী করলো
       (Created / Updated / Deleted / Login / Logout / Failed login)।
     • ফিল্টার: ইউজার, মডিউল, অ্যাকশন, সার্চ, তারিখ রেঞ্জ + প্রিসেট।
     • Update হলে ফিল্ডের old → new বিস্তারিত "Details" বাটনে (tooltip/modal) দেখা যায়।
     • সব লেবেল ইংরেজি (এক্সপোর্ট নীতির সাথে মিল রেখে UI-ও ইংরেজি)।
     ============================================================ --}}
<div class="container-fluid px-0">

  {{-- Page header --}}
  <div class="page-head d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div>
      <h3>Track Record</h3>
      <p class="ph-desc">Every admin action on the platform — creates, edits, deletes and sign-ins.</p>
    </div>
    <span class="badge bg-secondary">{{ $logs->total() }} entries</span>
  </div>

  {{-- FILTERS --}}
  <form method="GET" id="filtersForm" class="card p-3 p-md-4 mb-3">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
      <div class="section-title mb-0"><i class="fas fa-sliders"></i> Filter</div>
      @if ($search !== '' || $userId !== '' || $module !== '' || $action !== '' || $dateFrom !== '' || $dateTo !== '')
        <a href="{{ route('admin.audit.index') }}" class="btn btn-outline-secondary btn-sm">
          <i class="fas fa-rotate-left me-1"></i> Reset
        </a>
      @endif
    </div>

    <div class="row g-3 align-items-end">
      <div class="col-12 col-lg-3">
        <label class="form-label">Search</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-search"></i></span>
          <input type="text" name="q" value="{{ $search }}" class="form-control" placeholder="Description or admin name">
        </div>
      </div>

      <div class="col-6 col-lg-2">
        <label class="form-label">Admin</label>
        <select name="user_id" class="form-select">
          <option value="">All admins</option>
          @foreach ($users as $u)
            <option value="{{ $u->id }}" {{ $userId==$u->id?'selected':'' }}>{{ $u->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-6 col-lg-2">
        <label class="form-label">Module</label>
        <select name="module" class="form-select">
          <option value="">All modules</option>
          @foreach ($modules as $m)
            <option value="{{ $m }}" {{ $module===$m?'selected':'' }}>{{ ucfirst($m) }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-6 col-lg-2">
        <label class="form-label">Action</label>
        <select name="action" class="form-select">
          <option value="">All actions</option>
          @foreach ($actions as $a)
            <option value="{{ $a }}" {{ $action===$a?'selected':'' }}>{{ $a === 'login_failed' ? 'Failed login' : ucfirst($a) }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-12 col-lg-3">
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
        <button type="button" class="preset" id="clearDate">Clear date</button>
        <button type="submit" class="btn btn-apply px-4 ms-auto"><i class="fas fa-filter me-1"></i> Apply Filters</button>
      </div>
    </div>
  </form>

  {{-- LOG TABLE --}}
  <div class="card p-2 p-md-3">
    <div class="table-responsive">
      <table class="table align-middle">
        <thead>
          <tr>
            <th style="width:150px">Time</th>
            <th style="width:190px">Admin</th>
            <th style="width:120px">Action</th>
            <th style="width:110px">Module</th>
            <th>Description</th>
            <th style="width:110px">IP</th>
            <th style="width:90px" class="text-center">Details</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($logs as $log)
            <tr>
              <td class="text-muted" style="font-size:.8rem;white-space:nowrap">{{ $log->created_at->format('d M Y, h:i A') }}</td>
              <td>
                <div class="d-flex align-items-center gap-2">
                  <span class="avatar avatar-sm">{{ mb_strtoupper(mb_substr($log->user_name ?? 'S', 0, 1)) }}</span>
                  <div>
                    <div class="fw-semibold" style="font-size:.84rem">{{ $log->user_name ?? 'System' }}</div>
                    @if ($log->role_name)
                      <div class="text-muted" style="font-size:.7rem">{{ $log->role_name }}</div>
                    @endif
                  </div>
                </div>
              </td>
              <td>
                @php $actionClass = match ($log->action) {
                  'created' => 'bg-success',
                  'updated' => 'bg-warning',
                  'deleted' => 'bg-danger',
                  'login' => 'bg-primary',
                  'logout' => 'bg-secondary',
                  'login_failed' => 'bg-danger',
                  default => 'bg-light',
                }; @endphp
                <span class="badge {{ $actionClass }}">{{ $log->action === 'login_failed' ? 'failed login' : $log->action }}</span>
              </td>
              <td><span class="badge bg-light">{{ ucfirst($log->module) }}</span></td>
              <td style="font-size:.84rem">{{ $log->description }}</td>
              <td class="text-muted" style="font-size:.74rem">{{ $log->ip_address }}</td>
              <td class="text-center">
                @if ($log->changes)
                  <button type="button" class="btn btn-sm btn-outline-secondary"
                          data-bs-toggle="modal" data-bs-target="#changes-{{ $log->id }}">
                    <i class="fas fa-code-branch"></i>
                  </button>
                @else
                  <span class="text-muted">—</span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7">
                <div class="empty-state">
                  <div class="es-icon"><i class="fas fa-clipboard-list"></i></div>
                  <div class="fw-semibold mb-1" style="color:var(--text-soft)">No activity found</div>
                  <div>Admin actions will appear here as they happen.</div>
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
        @if($logs->total()) {{ $logs->firstItem() }}–{{ $logs->lastItem() }} @else 0 @endif
        of {{ $logs->total() }} entries
      </div>
      <div>{{ $logs->links() }}</div>
    </div>
  </div>
</div>

{{-- Change-detail modals --}}
@foreach ($logs->whereNotNull('changes') as $log)
  <div class="modal fade" id="changes-{{ $log->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title">Change details — {{ $log->user_name }}</h6>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p class="text-muted" style="font-size:.8rem">{{ $log->description }} · {{ $log->created_at->format('d M Y, h:i A') }}</p>
          <table class="table table-sm align-middle">
            <thead>
              <tr><th>Field</th><th>Old</th><th>New</th></tr>
            </thead>
            <tbody>
              @foreach ($log->changes as $field => $change)
                <tr>
                  <td class="fw-semibold">{{ $field }}</td>
                  <td class="text-muted" style="max-width:220px;word-break:break-word">{{ \Illuminate\Support\Str::limit((string) ($change['old'] ?? '—'), 120) }}</td>
                  <td style="max-width:220px;word-break:break-word">{{ \Illuminate\Support\Str::limit((string) ($change['new'] ?? '—'), 120) }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endforeach

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
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
