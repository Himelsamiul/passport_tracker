@extends('backend.master')

@section('page-title', 'Agents')
@section('page-subtitle', 'People who submit passports to the desk')

@section('content')
{{-- ============================================================
     ডিজাইন নোট (এজেন্ট লিস্টে যা কাজ হয়েছে):
     • সবুজ গ্র্যাডিয়েন্ট হেডার ও লাইট গ্লাস থিম বাদ — নতুন ডার্ক থিম।
     • হেডারে All / Used / Unused কাউন্ট ব্যাজ + "Add Agent" বাটন।
     • ফিল্টার কার্ড: সার্চ (ডেটালিস্ট সাজেশনসহ), স্ট্যাটাস, সর্ট, পার পেজ, তারিখ রেঞ্জ + প্রিসেট।
     • অটো-সাবমিট সিলেক্ট ও ফ্ল্যাটপিকরের আগের লজিক অক্ষুণ্ণ।
     • টেবিলে সফট চিপ ব্যাজ, "linked" ট্যাগ, লকড টুলটিপ ও empty-state।
     ============================================================ --}}
<div class="container-fluid px-0">

  {{-- Page header --}}
  <div class="page-head d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div>
      <h3>Agents</h3>
      <p class="ph-desc">Every agent who hands over passport applications.</p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
      <span class="badge kpi-badge">All: {{ $totalAll }}</span>
      <span class="badge kpi-badge">Used: {{ $totalUsed }}</span>
      <span class="badge kpi-badge">Unused: {{ $totalUnused }}</span>
      @if (can_do('agents.create'))
        <a href="{{ route('agents.create') }}" class="btn btn-primary">
          <i class="fas fa-plus me-1"></i> Add Agent
        </a>
      @endif
    </div>
  </div>

  {{-- Flash --}}
  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
  @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div>   @endif

  {{-- FILTERS --}}
  <div class="card p-3 p-md-4 mb-3">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
      <div class="section-title mb-0"><i class="fas fa-sliders"></i> Filter &amp; Search</div>
      <a href="{{ route('agents.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-rotate-left me-1"></i> Reset
      </a>
    </div>

    <form id="filtersForm" method="GET" action="{{ route('agents.index') }}" class="row g-3 align-items-end">
      {{-- hidden fields the controller uses --}}
      <input type="hidden" name="date_from" id="date_from" value="{{ $dateFrom }}">
      <input type="hidden" name="date_to" id="date_to" value="{{ $dateTo }}">

      <!-- Search -->
      <div class="col-12 col-lg-4">
        <label class="form-label">Search</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-search"></i></span>
          <input type="text" name="search" value="{{ $search }}" id="searchInput"
                 class="form-control fx" placeholder="Name / Phone / Email / Address" list="searchHints">
        </div>
        <datalist id="searchHints">
          @foreach($namesForDatalist as $n) <option value="{{ $n }}"></option> @endforeach
          @foreach($phonesForDatalist as $p) <option value="{{ $p }}"></option> @endforeach
          @foreach($emailsForDatalist as $e) <option value="{{ $e }}"></option> @endforeach
        </datalist>
      </div>

      <!-- Status -->
      <div class="col-6 col-lg-2">
        <label class="form-label">Status</label>
        <select name="used" class="form-select select-fx fx auto-submit">
          <option value="" {{ $used===''?'selected':'' }}>All</option>
          <option value="used" {{ $used==='used'?'selected':'' }}>Used (Locked)</option>
          <option value="unused" {{ $used==='unused'?'selected':'' }}>Unused (Deletable)</option>
        </select>
      </div>

      <!-- Sort -->
      <div class="col-6 col-lg-2">
        <label class="form-label">Sort</label>
        <select name="sort_by" class="form-select select-fx fx auto-submit">
          <option value="latest"  {{ $sortBy==='latest'?'selected':'' }}>Latest</option>
          <option value="oldest"  {{ $sortBy==='oldest'?'selected':'' }}>Oldest</option>
          <option value="name_az" {{ $sortBy==='name_az'?'selected':'' }}>Name A→Z</option>
          <option value="name_za" {{ $sortBy==='name_za'?'selected':'' }}>Name Z→A</option>
        </select>
      </div>

      <!-- Per page -->
      <div class="col-6 col-lg-2">
        <label class="form-label">Per Page</label>
        <select name="per_page" class="form-select select-fx fx auto-submit">
          @foreach([10,25,50,100] as $pp)
            <option value="{{ $pp }}" {{ (int)$perPage===$pp?'selected':'' }}>{{ $pp }}</option>
          @endforeach
        </select>
      </div>

      <!-- Date Range -->
      <div class="col-6 col-lg-2">
        <label class="form-label">Date Range</label>
        <input type="text" id="dateRange" class="form-control fx" placeholder="Select range"
               value="{{ $dateFrom && $dateTo ? \Carbon\Carbon::parse($dateFrom)->format('d M Y').' to '.\Carbon\Carbon::parse($dateTo)->format('d M Y') : '' }}">
      </div>

      <!-- Quick presets -->
      <div class="col-12">
        <div class="d-flex flex-wrap gap-2">
          <button type="button" class="preset" data-preset="today">Today</button>
          <button type="button" class="preset" data-preset="7">Last 7 days</button>
          <button type="button" class="preset" data-preset="30">Last 30 days</button>
          <button type="button" class="preset" data-preset="month">This month</button>
        </div>
      </div>

      <!-- Apply -->
      <div class="col-12">
        <button type="submit" class="btn btn-apply px-4">
          <i class="fas fa-filter me-1"></i> Apply Filters
        </button>
      </div>
    </form>
  </div>

  <!-- TABLE -->
  <div class="card p-2 p-md-3">
    <div class="table-responsive">
      <table class="table table-bordered align-middle mb-0">
        <thead>
          <tr>
            <th style="width:56px">#</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Address</th>
            <th>Created At</th>
            <th style="width:220px">Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($agents as $agent)
            @php $isUsed = $usedAgentIds->contains($agent->id); @endphp
            <tr class="{{ $isUsed ? 'lock-row' : '' }}">
              <td>{{ ($agents->currentPage()-1)*$agents->perPage() + $loop->iteration }}</td>
              <td class="fw-semibold">
                {{ $agent->name }}
                @if($isUsed)
                  <span class="badge bg-light ms-1"><i class="fas fa-link"></i> linked</span>
                @endif
              </td>
              <td><span class="badge bg-light"><i class="fas fa-phone"></i> {{ $agent->phone }}</span></td>
              <td>
                @if($agent->email)
                  <span class="badge bg-light"><i class="fas fa-envelope"></i> {{ $agent->email }}</span>
                @else
                  <span class="text-muted">—</span>
                @endif
              </td>
              <td><div class="addr">{{ $agent->address ?: '—' }}</div></td>
              <td>{{ $agent->created_at?->format('d M Y, h:i A') }}</td>
              <td>
                <div class="d-flex flex-wrap gap-1">
                  @if (can_do('agents.edit'))
                    <a href="{{ route('agents.edit', $agent->id) }}" class="btn btn-sm btn-warning">
                      <i class="fas fa-pen"></i> Edit
                    </a>
                  @endif

                  @if($isUsed || !can_do('agents.delete'))
                    <button type="button" class="btn btn-sm btn-secondary"
                            data-bs-toggle="tooltip" data-bs-placement="top"
                            title="{{ $isUsed ? 'You can’t delete this agent — already linked with other records.' : 'You do not have delete permission.' }}">
                      <i class="fas fa-lock"></i> Locked
                    </button>
                  @else
                    <a href="{{ route('agents.delete', $agent->id) }}"
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Are you sure you want to delete this agent?')">
                      <i class="fas fa-trash-can"></i> Delete
                    </a>
                  @endif
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7">
                <div class="empty-state">
                  <div class="es-icon"><i class="fas fa-user-tie"></i></div>
                  <div class="fw-semibold mb-1" style="color:var(--text-soft)">No agents found</div>
                  <div>Try adjusting the filters, or add your first agent.</div>
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
        @if($agents->total()) {{ $agents->firstItem() }}–{{ $agents->lastItem() }} @else 0 @endif
        of {{ $agents->total() }} results
      </div>
      <div>{{ $agents->onEachSide(1)->links() }}</div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Auto-submit on selects
    document.querySelectorAll('.auto-submit').forEach(el => {
      el.addEventListener('change', () => document.getElementById('filtersForm').submit());
    });

    // Flatpickr (range) -> fills hidden Y-m-d inputs
    const df = document.getElementById('date_from');
    const dt = document.getElementById('date_to');
    const fp = flatpickr("#dateRange", {
      mode: "range",
      dateFormat: "d M Y",
      altInput: false,
      onChange(selected){
        if (selected.length === 2){
          const asISO = d => new Date(d.getTime() - d.getTimezoneOffset()*60000).toISOString().slice(0,10);
          df.value = asISO(selected[0]);
          dt.value = asISO(selected[1]);
        }
      }
    });

    // Quick preset buttons
    function setRange(start, end){
      fp.setDate([start, end], true);
      const toISO = d => new Date(d.getTime() - d.getTimezoneOffset()*60000).toISOString().slice(0,10);
      df.value = toISO(start);
      dt.value = toISO(end);
      document.getElementById('filtersForm').submit();
    }

    const today = new Date(); today.setHours(0,0,0,0);
    const presets = document.querySelectorAll('.preset');
    presets.forEach(btn=>{
      btn.addEventListener('click', ()=>{
        presets.forEach(b=>b.classList.remove('active'));
        btn.classList.add('active');
        const val = btn.dataset.preset;
        if (val === 'today'){
          setRange(new Date(today), new Date(today));
        } else if (val === 'month'){
          const start = new Date(today.getFullYear(), today.getMonth(), 1);
          const end   = new Date(today.getFullYear(), today.getMonth()+1, 0);
          setRange(start, end);
        } else {
          const n = parseInt(val, 10);
          const start = new Date(today); start.setDate(today.getDate() - (n-1));
          const end   = new Date(today);
          setRange(start, end);
        }
      });
    });
  });
</script>
@endpush
@endsection
