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
        <div class="shadow-sm" style="
          border-radius:.7rem;
          background:linear-gradient(135deg,{{ $c['bg'] }},{{ $c['bg'] }}cc);
          color:{{ $c['color'] ?? '#fff' }};
          text-align:center;
          padding:.6rem .7rem;
          min-height:105px; transition:.2s;
        " onmouseover="this.style.transform='scale(1.03)'" onmouseout="this.style.transform='scale(1)'">
          <div style="font-size:1.2rem;margin-bottom:.2rem;"><i class="fas {{ $c['icon'] }}"></i></div>
          <div class="counter" data-val="{{ (int)$c['value'] }}" style="font-size:1.15rem;font-weight:800;">0</div>
          <div style="font-size:.8rem;font-weight:600;">{{ $c['label'] }}</div>
          @php $w=min(100,round(($c['value']/$max)*100)); @endphp
          <div style="height:4px;background:rgba(255,255,255,.3);border-radius:10px;margin-top:.3rem;">
            <div style="width:{{ $w }}%;height:100%;background:#fff;border-radius:10px;"></div>
          </div>
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
        {{-- Make each status card clickable to filter (adjust route if different) --}}
        <a href="{{ route('passports.index', ['status' => $s['query']]) }}" class="text-decoration-none" style="display:block;">
          <div class="shadow-sm" style="
            border-radius:.7rem;
            background:linear-gradient(135deg,{{ $s['bg'] }},{{ $s['bg'] }}cc);
            color:{{ $s['color'] ?? '#fff' }};
            text-align:center;
            padding:.55rem .65rem;
            min-height:96px; transition:.2s;
          " onmouseover="this.style.transform='scale(1.03)'" onmouseout="this.style.transform='scale(1)'">
            <div style="font-size:1.05rem;margin-bottom:.15rem;"><i class="fas {{ $s['icon'] }}"></i></div>
            <div class="counter" data-val="{{ (int)$s['value'] }}" style="font-size:1.05rem;font-weight:800;">0</div>
            <div style="font-size:.78rem;font-weight:700;">{{ $s['label'] }}</div>
            @php $ws=min(100,round(($s['value']/$maxS)*100)); @endphp
            <div style="height:3.5px;background:rgba(255,255,255,.3);border-radius:10px;margin-top:.28rem;">
              <div style="width:{{ $ws }}%;height:100%;background:#fff;border-radius:10px;"></div>
            </div>
          </div>
        </a>
      </div>
    @endforeach
  </div>

  {{-- ================== CHART + NOTES ================== --}}
  <div class="row g-3 mt-2">

    {{-- Pie Chart (small, transparent BLUE block) --}}
    <div class="col-lg-7">
      <div class="shadow-sm p-3" style="
        border-radius:.7rem;
        background:rgba(14,165,233,0.10); /* transparent blue */
        border:1px solid rgba(14,165,233,0.25);
      ">
        <h6 class="fw-semibold mb-2" style="font-size:.95rem;">Processing Status Overview</h6>
        <div style="max-width:240px;margin:auto;background:#ffffffc7;border-radius:.6rem;padding:.5rem;">
          <canvas id="chart" height="60"></canvas>
        </div>
        <div class="row text-center mt-2 small fw-semibold" style="row-gap:.4rem;">
          <div class="col" style="color:#fbbf24;">● Pending: {{ $statusCounts['pending'] ?? 0 }}</div>
          <div class="col" style="color:#0ea5e9;">● Ongoing: {{ $statusCounts['ongoing'] ?? 0 }}</div>
          <div class="col" style="color:#10b981;">● Done: {{ $statusCounts['done'] ?? 0 }}</div>
          <div class="col" style="color:#ef4444;">● Rejected: {{ $statusCounts['rejected'] ?? 0 }}</div>
        </div>
      </div>
    </div>

    {{-- Notes (transparent GOLDEN bg + status-colored text) --}}
    <div class="col-lg-5">
      <div class="shadow-sm p-3" style="
        border-radius:.7rem;
        background:rgba(255,215,0,0.12); /* transparent golden */
        border:1px solid rgba(184,134,11,0.35);
      ">
        <h6 class="fw-bold mb-2" style="color:#8a6d1d;font-size:.95rem;">নোটস</h6>
        <ul style="list-style:none;padding:0;margin:0;display:grid;gap:.5rem;">
          <li style="
            background:#ffffffc2;border-left:4px solid #fbbf24;
            padding:.45rem .6rem;border-radius:.45rem;font-weight:700;color:#4b5563;">
            <span style="color:#fbbf24;">● Pending</span> — আবেদন গ্রহণ হয়েছে, প্রক্রিয়া শুরু বাকি।
          </li>
          <li style="
            background:#ffffffc2;border-left:4px solid #0ea5e9;
            padding:.45rem .6rem;border-radius:.45rem;font-weight:700;color:#4b5563;">
            <span style="color:#0ea5e9;">● Ongoing</span> — ডকুমেন্ট/ভেরিফিকেশন/সাবমিশন চলমান।
          </li>
          <li style="
            background:#ffffffc2;border-left:4px solid #10b981;
            padding:.45rem .6rem;border-radius:.45rem;font-weight:700;color:#4b5563;">
            <span style="color:#10b981;">● Done</span> — কাজ সম্পন্ন; ডেলিভারি/কলেক্টের জন্য প্রস্তুত।
          </li>
          <li style="
            background:#ffffffc2;border-left:4px solid #ef4444;
            padding:.45rem .6rem;border-radius:.45rem;font-weight:700;color:#4b5563;">
            <span style="color:#ef4444;">● Rejected</span> — ডকুমেন্ট/নীতিগত কারণে বাতিল।
          </li>
          <li style="
            background:#ffffffad;border:1px dashed rgba(184,134,11,0.45);
            padding:.45rem .6rem;border-radius:.45rem;font-weight:600;color:#6b7280;">
            টিপস: <strong style="color:#0ea5e9;">Active Processing</strong> গণনায় কেবল যে পাসপোর্টগুলো এখনো
            <em style="color:#ef4444;">Collected</em> হয়নি সেগুলো ধরা হয়।
          </li>
        </ul>
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