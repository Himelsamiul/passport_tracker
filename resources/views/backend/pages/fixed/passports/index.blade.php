@extends('backend.master')

@section('content')
<div class="table-responsive">
  <table class="table table-bordered align-middle">
    <thead class="table-dark">
      <tr>
        <th style="width:60px">SL</th>
        <th>Passport No</th>
        <th>Name</th>
        <th>Date of Birth</th>
        <th>Agent</th>
        <th>Officer</th>
        <th>Employee</th>
        <th>Status</th>
        <th>Date</th>
        <th style="width:220px">Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($passports as $idx => $p)
        <tr>
          <td>{{ $idx + 1 }}</td>
          <td class="fw-semibold">{{ $p->passport_number }}</td>
          <td>{{ $p->applicant_name }}</td>
          <td>{{ optional($p->date_of_birth)->format('d M Y') ?? '—' }}</td>
          <td>{{ $p->agent?->name ?? '—' }}</td>
          <td>{{ $p->passportOfficer?->name ?? '—' }}</td>
          <td>{{ $p->employee?->name ?? '—' }}</td>
          <td>
            <span class="badge bg-secondary">{{ $p->status }}</span>
          </td>
          <td>{{ optional($p->created_at)->timezone(config('app.timezone','Asia/Dhaka'))->format('d M Y, h:i A') }}</td>
          <td class="nowrap">
            <a href="{{ route('passports.show', $p->id) }}" class="btn btn-sm btn-info">View</a>
            <a href="{{ route('passports.edit', $p->id) }}" class="btn btn-sm btn-warning">Edit</a>
            <a href="{{ route('passports.delete', $p->id) }}" class="btn btn-sm btn-danger"
               onclick="return confirm('Are you sure you want to delete this passport?')">Delete</a>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="9" class="text-center text-muted">No passports found.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection