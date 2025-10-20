@extends('backend.master')

@section('content')
<style>
  /* Enable horizontal scrolling with grab */
  .h-scroll {
    overflow-x: auto;
    overflow-y: hidden;
    cursor: grab;
    padding-bottom: 4px;
  }
  .h-scroll:active { cursor: grabbing; }

  /* Table should be wide enough to show all columns properly */
  .wide-table {
    min-width: 1600px;
    table-layout: auto;
  }

  /* Address column should be wide and readable */
  td.address-col, th.address-col {
    min-width: 250px;
    max-width: 400px;
    white-space: normal;
    word-wrap: break-word;
  }

  .nowrap { white-space: nowrap; }

  .table td, .table th {
    vertical-align: middle;
  }

  /* Sticky first column for index */
  .sticky-col {
    position: sticky;
    left: 0;
    z-index: 2;
    background: #fff;
  }
  thead .sticky-col {
    z-index: 3;
    background: #212529;
    color: #fff;
  }
</style>

<div class="container-fluid py-4">
  <h3 class="mb-2">Passports</h3>

  <div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('passports.create') }}" class="btn btn-primary">Add New Passport</a>
    <small class="text-muted">💡 Tip: drag left↔right to view all columns.</small>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="table-responsive h-scroll" id="passportScroll">
    <table class="table table-bordered align-middle wide-table">
      <thead class="table-dark">
        <tr>
          <th class="sticky-col">#</th>
          <th>Agent</th>
          <th>Applicant Name</th>
          <th class="address-col">Address</th>
          <th class="nowrap">Phone</th>
          <th class="nowrap">Date of Birth</th>
          <th class="nowrap">Passport Number</th>
          <th class="nowrap">Nationality</th>
          <th class="nowrap">Issue Date</th>
          <th class="nowrap">Expiry Date</th>
          <th class="nowrap">NID Number</th>
          <th class="nowrap">Status</th>
          <th class="nowrap">Created (Date &amp; Time)</th>
          <th class="nowrap">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($passports as $idx => $p)
          <tr>
            <td class="sticky-col">{{ $idx + 1 }}</td>
            <td>{{ $p->agent?->name }}</td>
            <td class="nowrap">{{ $p->applicant_name }}</td>
            <td class="address-col">{{ $p->address }}</td>
            <td class="nowrap">{{ $p->phone }}</td>
            <td class="nowrap">{{ optional($p->date_of_birth)->format('d M Y') }}</td>
            <td class="nowrap">{{ $p->passport_number }}</td>
            <td class="nowrap">{{ $p->nationality }}</td>
            <td class="nowrap">{{ optional($p->issue_date)->format('d M Y') }}</td>
            <td class="nowrap">{{ optional($p->expiry_date)->format('d M Y') }}</td>
            <td class="nowrap">{{ $p->nid_number }}</td>
            <td class="nowrap">
              <span class="badge bg-secondary">{{ $p->status }}</span>
            </td>
            <td class="nowrap">
              {{ optional($p->created_at)->timezone(config('app.timezone', 'Asia/Dhaka'))->format('d M Y, h:i A') }}
            </td>
            <td class="nowrap">
              <a href="{{ route('passports.edit', $p->id) }}" class="btn btn-sm btn-warning">Edit</a>
              <a href="{{ route('passports.delete', $p->id) }}" class="btn btn-sm btn-danger"
                 onclick="return confirm('Are you sure you want to delete this passport?')">Delete</a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="14" class="text-center text-muted">No passports found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- Smooth drag-scroll script --}}
<script>
  (function() {
    const el = document.getElementById('passportScroll');
    if (!el) return;

    let isDown = false;
    let startX;
    let scrollLeft;

    const start = (e) => {
      isDown = true;
      el.classList.add('dragging');
      startX = (e.pageX || (e.touches && e.touches[0].pageX)) - el.offsetLeft;
      scrollLeft = el.scrollLeft;
    };
    const end = () => { isDown = false; el.classList.remove('dragging'); };
    const move = (e) => {
      if (!isDown) return;
      e.preventDefault();
      const x = (e.pageX || (e.touches && e.touches[0].pageX)) - el.offsetLeft;
      const walk = (x - startX);
      el.scrollLeft = scrollLeft - walk;
    };

    el.addEventListener('mousedown', start);
    el.addEventListener('mouseleave', end);
    el.addEventListener('mouseup', end);
    el.addEventListener('mousemove', move);
    el.addEventListener('touchstart', start, { passive: true });
    el.addEventListener('touchend', end, { passive: true });
    el.addEventListener('touchmove', move, { passive: false });
  })();
</script>
@endsection
