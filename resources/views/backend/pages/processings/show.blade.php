@extends('backend.master')

@section('content')
<style>
  /* ====== ENHANCED THEME ====== */
  :root{
    --bg-soft: #f8fafc;
    --card: #ffffff;
    --border: #e2e8f0;
    --text: #1e293b;
    --muted: #64748b;

    --brand: #3b82f6;
    --brand-600: #2563eb;
    --brand-50: #eff6ff;
    --brand-100: #dbeafe;

    --ok: #10b981;
    --warn: #f59e0b;
    --idle: #94a3b8;
    --fail: #ef4444;

    /* Card Background Colors */
    --card-1-bg: #f0f9ff;
    --card-2-bg: #f0fdf4;
    --card-3-bg: #fef7ff;
    
    /* Card Header Colors */
    --card-header-1: #0ea5e9;
    --card-header-2: #22c55e;
    --card-header-3: #a855f7;

    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
  }

  body { 
    background: var(--bg-soft);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
  }

  /* ====== ENHANCED PAGE HEADER ====== */
  .page-header { 
    display:flex; 
    align-items:center; 
    justify-content:space-between; 
    gap:1rem;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--border);
  }
  
  .page-title {
    margin: 0;
    font-weight: 800;
    color: var(--text);
    letter-spacing: .2px;
    font-size: 1.75rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
  }

  .page-title::before {
    content: '';
    display: block;
    width: 4px;
    height: 24px;
    background: linear-gradient(135deg, var(--brand), var(--brand-600));
    border-radius: 2px;
  }

  /* ====== ENHANCED CARD DESIGN WITH COLORS ====== */
  .card {
    border: 1px solid var(--border);
    border-radius: 16px;
    box-shadow: var(--shadow-md);
    overflow: hidden;
    background: var(--card);
    transition: all 0.3s ease;
    margin-bottom: 1.5rem;
    position: relative;
  }

  .card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
  }

  .card-1 {
    background: var(--card-1-bg);
    border: 1px solid rgba(14, 165, 233, 0.2);
  }

  .card-1::before {
    background: var(--card-header-1);
  }

  .card-2 {
    background: var(--card-2-bg);
    border: 1px solid rgba(34, 197, 94, 0.2);
  }

  .card-2::before {
    background: var(--card-header-2);
  }

  .card-3 {
    background: var(--card-3-bg);
    border: 1px solid rgba(168, 85, 247, 0.2);
  }

  .card-3::before {
    background: var(--card-header-3);
  }

  .card:hover {
    box-shadow: var(--shadow-lg);
    transform: translateY(-2px);
  }

  .card-header {
    border-bottom: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    font-weight: 700;
    padding: 1.25rem 1.5rem;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
  }

  .card-1 .card-header {
    background: var(--card-header-1);
  }

  .card-2 .card-header {
    background: var(--card-header-2);
  }

  .card-3 .card-header {
    background: var(--card-header-3);
  }

  .card-body {
    padding: 1.5rem;
  }

  /* ====== ENHANCED KV GRID ====== */
  .kv-grid {
    display: grid;
    grid-template-columns: repeat(12, 1fr);
    gap: 1rem;
  }

  @media (max-width: 991.98px) { 
    .kv-grid { 
      grid-template-columns: repeat(6, 1fr); 
    } 
  }

  @media (max-width: 575.98px) { 
    .kv-grid { 
      grid-template-columns: repeat(2, 1fr); 
    } 
  }

  .kv-item {
    grid-column: span 3;
    background: rgba(255, 255, 255, 0.8);
    border: 1px solid rgba(255, 255, 255, 0.9);
    border-radius: 12px;
    padding: 1.25rem 1rem;
    min-height: 80px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  }

  .kv-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 3px;
    height: 100%;
    background: var(--brand);
    opacity: 0;
    transition: opacity 0.3s ease;
  }

  .kv-item:hover::before {
    opacity: 1;
  }

  .kv-item:hover { 
    box-shadow: var(--shadow-md); 
    transform: translateY(-2px);
    background: rgba(255, 255, 255, 0.95);
  }

  .span-4 { grid-column: span 4; }
  .span-6 { grid-column: span 6; }
  .span-12{ grid-column: span 12; }

  .kv-label {
    font-size: 0.75rem; 
    color: var(--muted);
    text-transform: uppercase; 
    letter-spacing: .05em;
    margin-bottom: 0.5rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .kv-value {
    font-weight: 700; 
    color: var(--text); 
    font-size: 1rem;
    line-height: 1.4;
  }

  /* ====== ENHANCED BADGES / CHIPS ====== */
  .badge { 
    font-size: 0.75rem; 
    padding: 0.5rem 0.75rem; 
    border-radius: 999px; 
    font-weight: 600;
    letter-spacing: 0.025em;
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
  }

  .b-ok { 
    background: rgba(16, 185, 129, 0.12);  
    color: #047857;  
    border: 1px solid rgba(16, 185, 129, 0.25); 
  }

  .b-warn { 
    background: rgba(245, 158, 11, 0.12); 
    color: #b45309;  
    border: 1px solid rgba(245, 158, 11, 0.28); 
  }

  .b-idle { 
    background: rgba(148, 163, 184, 0.16);
    color: #475569;  
    border: 1px solid rgba(148, 163, 184, 0.28); 
  }

  .b-fail { 
    background: rgba(239, 68, 68, 0.12);  
    color: #b91c1c;  
    border: 1px solid rgba(239, 68, 68, 0.25); 
  }

  .chip {
    display: inline-flex;
    align-items: center;
    border-radius: 999px;
    padding: 0.375rem 0.75rem;
    font-size: 0.75rem;
    border: 1px solid var(--border);
    background: #f8fafc;
    color: #0f172a;
    font-weight: 500;
    gap: 0.375rem;
  }

  /* ====== ENHANCED MEDIA ====== */
  .media-block { 
    margin-top: 1rem;
    text-align: center;
  }

  .media-block img { 
    max-height: 280px; 
    border: 1px solid var(--border); 
    border-radius: 12px;
    box-shadow: var(--shadow-md);
    transition: all 0.3s ease;
    background: white;
    padding: 4px;
  }

  .media-block img:hover {
    transform: scale(1.02);
    box-shadow: var(--shadow-lg);
  }

  /* ====== ENHANCED BUTTONS ====== */
  .btn-brand {
    background: var(--brand);
    border-color: var(--brand);
    color: white;
    font-weight: 600;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
  }

  .btn-brand:hover {
    background: var(--brand-600);
    border-color: var(--brand-600);
    transform: translateY(-1px);
    box-shadow: var(--shadow);
    color: white;
  }

  .btn-secondary {
    background: #f8fafc;
    border-color: #e2e8f0;
    color: #475569;
    font-weight: 600;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
  }

  .btn-secondary:hover {
    background: #f1f5f9;
    border-color: #cbd5e1;
    transform: translateY(-1px);
    box-shadow: var(--shadow);
    color: #374151;
  }

  .btn-warning {
    background: #f59e0b;
    border-color: #f59e0b;
    color: white;
    font-weight: 600;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
  }

  .btn-warning:hover {
    background: #d97706;
    border-color: #d97706;
    transform: translateY(-1px);
    box-shadow: var(--shadow);
    color: white;
  }

  /* ====== ANIMATIONS ====== */
  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .card {
    animation: fadeInUp 0.6s ease-out;
  }

  .card:nth-child(2) {
    animation-delay: 0.1s;
  }

  .card:nth-child(3) {
    animation-delay: 0.2s;
  }

  /* ====== EMPTY STATE ====== */
  .empty-state {
    color: var(--muted);
    font-style: italic;
  }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="container-fluid py-4">
  <div class="page-header">
    <h3 class="page-title">Processing Details</h3>
    <div class="d-flex gap-2">
      <a href="{{ route('processings.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
        Back
      </a>
      <a href="{{ route('processings.edit', $processing->id) }}" class="btn btn-warning">
        <i class="fas fa-edit"></i>
        Edit
      </a>
    </div>
  </div>

  {{-- ========== CARD 1: PROCESSING (Light Blue Background) ========== --}}
  <div class="card card-1">
    <div class="card-header">
      <i class="fas fa-cogs"></i>
      Processing Information
    </div>
    <div class="card-body">
      <div class="kv-grid">
        {{-- Status --}}
        <div class="kv-item">
          <div class="kv-label">
            <i class="fas fa-tag"></i>
            Status
          </div>
          @php
            $statusClass = [
              'DONE'        => 'b-ok',
              'IN_PROGRESS' => 'b-warn',
              'PENDING'     => 'b-idle',
              'REJECTED'    => 'b-fail',
            ][$processing->status] ?? 'b-idle';
          @endphp
          <div class="kv-value">
            <span class="badge {{ $statusClass }}">
              <i class="fas fa-circle"></i>
              {{ $processing->status }}
            </span>
          </div>
        </div>

        {{-- Employee (Picked Up) --}}
        <div class="kv-item">
          <div class="kv-label">
            <i class="fas fa-user"></i>
            Employee (Picked Up)
          </div>
          <div class="kv-value">{{ $processing->employee->name ?? '—' }}</div>
        </div>

        {{-- Agency --}}
        <div class="kv-item">
          <div class="kv-label">
            <i class="fas fa-building"></i>
            Agency
          </div>
          <div class="kv-value">{{ $processing->agency->name ?? 'Unassigned' }}</div>
        </div>

        {{-- Created --}}
        <div class="kv-item">
          <div class="kv-label">
            <i class="fas fa-calendar-plus"></i>
            Created
          </div>
          <div class="kv-value">{{ $processing->created_at->format('d M Y, h:i A') }}</div>
        </div>

        {{-- Notes (full width) --}}
        <div class="kv-item span-12">
          <div class="kv-label">
            <i class="fas fa-sticky-note"></i>
            Notes
          </div>
          <div class="kv-value">{{ $processing->notes ?: '—' }}</div>
        </div>
      </div>
    </div>
  </div>

  {{-- ========== CARD 2: STAKEHOLDERS (Light Green Background) ========== --}}
  <div class="card card-2">
    <div class="card-header">
      <i class="fas fa-users"></i>
      Stakeholders
    </div>
    <div class="card-body">
      <div class="kv-grid">
        {{-- Agent --}}
        <div class="kv-item span-4">
          <div class="kv-label">
            <i class="fas fa-user-tie"></i>
            Agent
          </div>
          <div class="kv-value">{{ optional($processing->passport->agent)->name ?: '—' }}</div>
        </div>

        {{-- Passport Officer --}}
        <div class="kv-item span-4">
          <div class="kv-label">
            <i class="fas fa-passport"></i>
            Passport Officer
          </div>
          <div class="kv-value">{{ optional($processing->passport->passportOfficer)->name ?: '—' }}</div>
        </div>

        {{-- Received By (Original) --}}
        <div class="kv-item span-4">
          <div class="kv-label">
            <i class="fas fa-user-check"></i>
            Received By (Original)
          </div>
          <div class="kv-value">{{ optional($processing->passport->employee)->name ?: '—' }}</div>
        </div>
      </div>
    </div>
  </div>

  {{-- ========== CARD 3: PASSPORT DETAILS (Light Purple Background) ========== --}}
  <div class="card card-3">
    <div class="card-header">
      <i class="fas fa-id-card"></i>
      Passport Details
    </div>
    <div class="card-body">
      <div class="kv-grid">
        {{-- Top row --}}
        <div class="kv-item span-4">
          <div class="kv-label">
            <i class="fas fa-user"></i>
            Applicant Name
          </div>
          <div class="kv-value">{{ $processing->passport->applicant_name }}</div>
        </div>
        <div class="kv-item span-4">
          <div class="kv-label">
            <i class="fas fa-id-card"></i>
            Passport Number
          </div>
          <div class="kv-value">{{ $processing->passport->passport_number }}</div>
        </div>
        <div class="kv-item span-4">
          <div class="kv-label">
            <i class="fas fa-flag"></i>
            Nationality
          </div>
          <div class="kv-value">{{ $processing->passport->nationality ?: '—' }}</div>
        </div>

        {{-- Contact --}}
        <div class="kv-item span-6">
          <div class="kv-label">
            <i class="fas fa-phone"></i>
            Phone
          </div>
          <div class="kv-value">{{ $processing->passport->phone ?: '—' }}</div>
        </div>
        <div class="kv-item span-6">
          <div class="kv-label">
            <i class="fas fa-map-marker-alt"></i>
            Address
          </div>
          <div class="kv-value">{{ $processing->passport->address ?: '—' }}</div>
        </div>

        {{-- Dates --}}
        <div class="kv-item span-4">
          <div class="kv-label">
            <i class="fas fa-birthday-cake"></i>
            Date of Birth
          </div>
          <div class="kv-value">{{ optional($processing->passport->date_of_birth)->format('d M Y') ?: '—' }}</div>
        </div>
        <div class="kv-item span-4">
          <div class="kv-label">
            <i class="fas fa-calendar-day"></i>
            Issue Date
          </div>
          <div class="kv-value">{{ optional($processing->passport->issue_date)->format('d M Y') ?: '—' }}</div>
        </div>
        <div class="kv-item span-4">
          <div class="kv-label">
            <i class="fas fa-calendar-times"></i>
            Expiry Date
          </div>
          <div class="kv-value">{{ optional($processing->passport->expiry_date)->format('d M Y') ?: '—' }}</div>
        </div>

        {{-- NID / Status --}}
        <div class="kv-item span-6">
          <div class="kv-label">
            <i class="fas fa-id-badge"></i>
            NID Number
          </div>
          <div class="kv-value">{{ $processing->passport->nid_number ?: '—' }}</div>
        </div>
        <div class="kv-item span-6">
          <div class="kv-label">
            <i class="fas fa-info-circle"></i>
            Passport Status
          </div>
          <div class="kv-value">
            @if($processing->passport->status)
              <span class="chip">
                <i class="fas fa-circle"></i>
                {{ $processing->passport->status }}
              </span>
            @else
              —
            @endif
          </div>
        </div>

        {{-- Notes --}}
        <div class="kv-item span-12">
          <div class="kv-label">
            <i class="fas fa-sticky-note"></i>
            Passport Notes
          </div>
          <div class="kv-value">{{ $processing->passport->notes ?: '—' }}</div>
        </div>

        {{-- Picture --}}
        @if($processing->passport->passport_picture)
          <div class="span-12 media-block">
            <div class="kv-label mb-2">
              <i class="fas fa-image"></i>
              Passport Picture
            </div>
            <img
              src="{{ asset('storage/'.$processing->passport->passport_picture) }}"
              class="img-fluid"
              alt="Passport Picture">
          </div>
        @endif

        {{-- Timestamps --}}
        <div class="kv-item span-6">
          <div class="kv-label">
            <i class="fas fa-calendar-plus"></i>
            Created At
          </div>
          <div class="kv-value">{{ $processing->passport->created_at->format('d M Y, h:i A') }}</div>
        </div>
        <div class="kv-item span-6">
          <div class="kv-label">
            <i class="fas fa-calendar-check"></i>
            Updated At
          </div>
          <div class="kv-value">{{ $processing->passport->updated_at->format('d M Y, h:i A') }}</div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  // Add subtle animations on page load
  document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
      card.style.animationDelay = `${index * 0.1}s`;
    });
  });
</script>
@endsection