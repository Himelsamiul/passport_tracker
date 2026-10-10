@extends('backend.master')

@section('content')
<div class="container-fluid py-3">

  {{-- Company Title (golden) --}}
  <div class="text-center mb-3">
    <h1 style="
      font-size:clamp(28px,3.2vw,46px);
      font-weight:900;
      letter-spacing:.6px;
      background:linear-gradient(180deg,#FFD700 0%,#E6BE8A 55%,#B8860B 100%);
      -webkit-background-clip:text;background-clip:text;color:transparent;
      text-shadow:0 1px 0 rgba(0,0,0,.05);
    ">Shahriar Worldwide Venture</h1>
  </div>

  {{-- ================== KPI CARDS (Row 1) ================== --}}
  @php
    // First row KPI cards
    $cards = [
      ['label'=>'Total Passports','value'=>$stats['passports'] ?? 0,'icon'=>'fa-passport','bg'=>'#4361ee'],
      ['label'=>'Agents','value'=>$stats['agents'] ?? 0,'icon'=>'fa-user-tie','bg'=>'#0ea5e9'],
      ['label'=>'Officers','value'=>$stats['officers'] ?? 0,'icon'=>'fa-user-shield','bg'=>'#6d28d9'],
      ['label'=>'Employees','value'=>$stats['employees'] ?? 0,'icon'=>'fa-users','bg'=>'#14b8a6'],
      ['label'=>'Agencies','value'=>$stats['agencies'] ?? 0,'icon'=>'fa-building','bg'=>'#a855f7'],
      ['label'=>'Categories','value'=>$stats['categories'] ?? 0,'icon'=>'fa-layer-group','bg'=>'#ec4899'],
      // Total Processing = sum of latest status buckets (pending+ongoing+done+rejected)
      ['label'=>'Total Processing','value'=>$processingLatestTotal ?? 0,'icon'=>'fa-cogs','bg'=>'#f97316'],
      ['label'=>'Active Processing','value'=>$stats['processings_active'] ?? 0,'icon'=>'fa-tasks','bg'=>'#fbbf24','color'=>'#111'],
      ['label'=>'Total Collecting','value'=>$stats['collections'] ?? 0,'icon'=>'fa-box-open','bg'=>'#22c55e'],
      ['label'=>'Pending','value'=>$statusCounts['pending'] ?? 0,'icon'=>'fa-hourglass-start','bg'=>'#fbbf24','color'=>'#111','query'=>'pending'],
    ];
    $max = max(array_map(fn($x)=>$x['value'],$cards)) ?: 1;
  @endphp

  <div class="row g-3">
    @foreach($cards as $c)
      <div class="col-6 col-md-4 col-lg-1-5" style="flex:0 0 20%;max-width:20%;">
        <div class="kpi-card" style="--accent:{{ $c['bg'] }};">
          <div class="kpi-icon" style="background:{{ $c['bg'] }}1c;color:{{ $c['color'] ?? $c['bg'] }};">
            <i class="fas {{ $c['icon'] }}"></i>
          </div>
          <div class="counter kpi-value" data-val="{{ (int)$c['value'] }}">0</div>
          <div class="kpi-label">{{ $c['label'] }}</div>
          @php $w=min(100,round(($c['value']/$max)*100)); @endphp
          <div class="kpi-bar"><span style="width:{{ $w }}%"></span></div>
        </div>
      </div>
    @endforeach
  </div>

  {{-- ================== STATUS CARDS (Row 2: 5 per row) ================== --}}
  @php
    $statusCards = [
      
      ['label'=>'Ongoing','value'=>$statusCounts['ongoing'] ?? 0,'icon'=>'fa-circle-notch','bg'=>'#0ea5e9','query'=>'ongoing'],
      ['label'=>'Done','value'=>$statusCounts['done'] ?? 0,'icon'=>'fa-check-circle','bg'=>'#10b981','query'=>'done'],
      ['label'=>'Rejected','value'=>$statusCounts['rejected'] ?? 0,'icon'=>'fa-times-circle','bg'=>'#ef4444','query'=>'rejected'],
    ];
    $maxS = max(array_map(fn($x)=>$x['value'],$statusCards)) ?: 1;
  @endphp

  <div class="row g-3 mt-1">
    @foreach($statusCards as $s)
      <div class="col-6 col-md-4 col-lg-1-5" style="flex:0 0 20%;max-width:20%;">
        {{-- Clickable status filter --}}
        <a href="{{ route('passports.index', ['status' => $s['query']]) }}" class="text-decoration-none" style="display:block;">
          <div class="kpi-card" style="--accent:{{ $s['bg'] }};">
            <div class="kpi-icon" style="background:{{ $s['bg'] }}1c;color:{{ $s['color'] ?? $s['bg'] }};">
              <i class="fas {{ $s['icon'] }}"></i>
            </div>
            <div class="counter kpi-value" data-val="{{ (int)$s['value'] }}">0</div>
            <div class="kpi-label">{{ $s['label'] }}</div>
            @php $ws=min(100,round(($s['value']/$maxS)*100)); @endphp
            <div class="kpi-bar"><span style="width:{{ $ws }}%"></span></div>
          </div>
        </a>
      </div>
    @endforeach
  </div>

  {{-- ================== CHART + NOTES ================== --}}
  <div class="row g-3 mt-2">

    {{-- Pie Chart --}}
    <div class="col-lg-7">
      <div class="card h-100">
        <div class="card-body">
          <div class="section-title"><i class="fas fa-chart-pie"></i> Processing Status Overview</div>
          <div class="chart-wrap">
            <canvas id="chart" height="80"></canvas>
          </div>
          <div class="row text-center mt-3 small fw-semibold" style="row-gap:.4rem;">
            <div class="col" style="color:#d97706;">● Pending: {{ $statusCounts['pending'] ?? 0 }}</div>
            <div class="col" style="color:#0284c7;">● Ongoing: {{ $statusCounts['ongoing'] ?? 0 }}</div>
            <div class="col" style="color:#059669;">● Done: {{ $statusCounts['done'] ?? 0 }}</div>
            <div class="col" style="color:#dc2626;">● Rejected: {{ $statusCounts['rejected'] ?? 0 }}</div>
          </div>
        </div>
      </div>
    </div>

    {{-- Notes --}}
    <div class="col-lg-5">
      <div class="card h-100">
        <div class="card-body">
          <div class="section-title"><i class="fas fa-circle-info"></i> Status Guide</div>
          <ul class="status-guide">
            <li style="--c:#f59e0b;"><strong>Pending</strong> — আবেদন গ্রহণ হয়েছে, প্রক্রিয়া শুরু বাকি।</li>
            <li style="--c:#0ea5e9;"><strong>Ongoing</strong> — ডকুমেন্ট/ভেরিফিকেশন/সাবমিশন চলমান।</li>
            <li style="--c:#10b981;"><strong>Done</strong> — কাজ সম্পন্ন; ডেলিভারি/কলেক্টের জন্য প্রস্তুত।</li>
            <li style="--c:#ef4444;"><strong>Rejected</strong> — ডকুমেন্ট/নীতিগত কারণে বাতিল।</li>
          </ul>
          <div class="tip-box">
            টিপস: <strong>Active Processing</strong> গণনায় কেবল যে পাসপোর্টগুলো এখনো
            <em>Collected</em> হয়নি সেগুলো ধরা হয়।
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Animate numbers on all cards
  document.querySelectorAll('.counter').forEach(el=>{
    const end = +el.dataset.val; let cur=0;
    const step=()=>{ cur+=Math.max(1,Math.ceil((end-cur)*0.2));
      el.textContent = cur >= end ? end.toLocaleString() : cur;
      if(cur<end) requestAnimationFrame(step);
    };
    requestAnimationFrame(step);
  });

  // Chart (compact)
  const ctx=document.getElementById('chart');
  if(ctx){
    new Chart(ctx,{
      type:'doughnut',
      data:{
        labels:['Pending','Ongoing','Done','Rejected'],
        datasets:[{
          data:[
            {{ (int)($statusCounts['pending'] ?? 0) }},
            {{ (int)($statusCounts['ongoing'] ?? 0) }},
            {{ (int)($statusCounts['done'] ?? 0) }},
            {{ (int)($statusCounts['rejected'] ?? 0) }},
          ],
          backgroundColor:['#fbbf24','#0ea5e9','#10b981','#ef4444'],
          borderColor:'#f1f5f9',
          borderWidth:2
        }]
      },
      options:{ cutout:'68%', plugins:{ legend:{ display:false } }, layout:{ padding:4 } }
    });
  }
</script>
@endsection