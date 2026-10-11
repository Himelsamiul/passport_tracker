{{-- ============================================================
     PDF export layout (dompdf) — clean, organised, English-only.
     Structure: brand header → title + meta → filter line → summary
     chips → striped data table with repeating header on every page.
     ============================================================ --}}
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
  @page { margin: 26px 22px 30px 22px; }
  * { box-sizing: border-box; }
  body { font-family: "DejaVu Sans", sans-serif; font-size: 10px; color: #1e293b; margin: 0; }

  .brand-bar { background: #0f3460; color: #ffffff; padding: 12px 16px; border-radius: 4px; }
  .brand-bar .name { font-size: 15px; font-weight: bold; letter-spacing: .4px; }
  .brand-bar .sub { font-size: 8.5px; color: #c7d5ef; margin-top: 2px; }

  .head-block { margin: 14px 0 10px; }
  .report-title { font-size: 16px; font-weight: bold; color: #0f172a; margin: 0; }
  .report-sub { font-size: 9.5px; color: #64748b; margin: 2px 0 0; }
  .meta-line { font-size: 8.5px; color: #64748b; margin-top: 5px; }
  .meta-line b { color: #334155; }

  .chips { margin: 10px 0 12px; }
  .chip { display: inline-block; background: #eef3fb; color: #0f3460; border: 1px solid #c9d8f2;
          border-radius: 8px; padding: 3px 9px; font-size: 8.5px; font-weight: bold; margin-right: 6px; }

  table.data { width: 100%; border-collapse: collapse; }
  table.data thead th {
    background: #4e86e8; color: #ffffff; font-size: 8.5px; font-weight: bold;
    text-align: left; padding: 6px 8px; border-bottom: 1px solid #2f5cb0;
  }
  table.data tbody td { padding: 5px 8px; border-bottom: 1px solid #e2e8f0; font-size: 9px; }
  table.data tbody tr:nth-child(even) td { background: #f4f7fc; }
  table.data td.c, table.data th.c { text-align: center; }
  .muted { color: #94a3b8; }

  .empty { text-align: center; color: #64748b; padding: 26px 0; font-size: 10px; }

  .summary { margin-top: 16px; border: 1px solid #dbe4f1; border-radius: 4px; background: #f8fafc; padding: 10px 14px; }
  .summary .s-title { font-size: 10px; font-weight: bold; color: #0f3460; margin-bottom: 6px;
                      text-transform: uppercase; letter-spacing: .6px; }
  .summary table { width: 100%; border-collapse: collapse; }
  .summary td { font-size: 9px; padding: 3px 0; }
  .summary td.k { color: #475569; width: 40%; }
  .summary td.v { font-weight: bold; color: #0f3460; }

  .foot { margin-top: 18px; border-top: 1px solid #e2e8f0; padding-top: 7px;
          font-size: 8px; color: #94a3b8; text-align: center; }
</style>
</head>
<body>

  <div class="brand-bar">
    <div class="name">Passport MS — Shahriar Aviation</div>
    <div class="sub">Management control report</div>
  </div>

  <div class="head-block">
    <h1 class="report-title">{{ $d['title'] }}</h1>
    <p class="report-sub">{{ $d['subtitle'] }}</p>
    <p class="meta-line">
      Generated: <b>{{ now()->format('d M Y, h:i A') }}</b> &nbsp;|&nbsp;
      Rows: <b>{{ count($d['rows']) }}</b> &nbsp;|&nbsp;
      Filters: <b>{{ $d['filters'] }}</b>
    </p>
  </div>

  <div class="chips">
    @foreach ($d['summary'] as $label => $value)
      <span class="chip">{{ $label }}: {{ $value }}</span>
    @endforeach
  </div>

  @if (count($d['rows']) === 0)
    <div class="empty">No data found for the selected filters.</div>
  @else
    <table class="data">
      <thead>
        <tr>
          @foreach ($d['columns'] as $col)
            <th {{ $col === 'SL' ? 'class="c"' : '' }}>{{ $col }}</th>
          @endforeach
        </tr>
      </thead>
      <tbody>
        @foreach ($d['rows'] as $i => $row)
          <tr>
            <td class="c muted">{{ $i + 1 }}</td>
            @foreach ($row as $cell)
              <td>{{ $cell }}</td>
            @endforeach
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif

  <div class="summary">
    <div class="s-title">Summary</div>
    <table>
      @foreach ($d['summary'] as $label => $value)
        <tr>
          <td class="k">{{ $label }}</td>
          <td class="v">{{ $value }}</td>
        </tr>
      @endforeach
    </table>
  </div>

  <div class="foot">
    Passport MS · {{ $d['title'] }} · Generated on {{ now()->format('d M Y, h:i A') }} — confidential, internal use only
  </div>

</body>
</html>
