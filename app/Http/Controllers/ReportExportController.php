<?php

/*
 * ফিচার নোট (রিপোর্ট এক্সপোর্ট — এই ফাইলে যা কাজ হয়েছে):
 * • ৩টি রিপোর্টের (Processing / Collection / Summary) Excel + PDF ডাউনলোড।
 * • Excel: স্টাইল করা SpreadsheetML (.xls) — টাইটেল ব্লক, ফিল্টার লাইন,
 *   রঙিন হেডার, জেব্রা রো, নিচে সামারি; Excel/LibreOffice-এ সরাসরি খোলে।
 * • PDF: dompdf দিয়ে পরিষ্কার প্রিন্ট লেআউট — ব্র্যান্ড হেডার, সামারি চিপ,
 *   প্রতি পেজে হেডার সহ টেবিল, শেষে সামারি সেকশন।
 * • স্ক্রিনের ফিল্টার (status/employee/agency/date/search/stage) ডাউনলোডেও কাজ করে।
 * • ইউজারের নির্দেশে এক্সপোর্টে কোনো বাংলা নেই — সব লেবেল ইংরেজি।
 */

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Employee;
use App\Models\Passport;
use App\Models\PassportCollection;
use App\Models\PassportProcessing;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * Downloads for the three reports (processing / collection / summary).
 *
 * excel() streams a styled SpreadsheetML (.xls) file — opens natively in
 * Excel/LibreOffice with a title block, filter summary, coloured header row
 * and a totals footer. No dependency needed.
 *
 * pdf() renders the same data through dompdf with a clean print layout.
 * Noto Sans Bengali is registered so Bangla names print correctly.
 *
 * Both respect the on-screen filters via the same query-string parameters
 * the report pages use.
 */
class ReportExportController extends Controller
{
    private const TYPES = ['processing', 'collection', 'summary'];

    public function excel(Request $request, string $type)
    {
        abort_unless(in_array($type, self::TYPES, true), 404);

        $data = $this->build($type, $request);

        $xml = $this->spreadsheetXml($data);

        return response($xml, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $data['file'] . '.xls"',
        ]);
    }

    public function pdf(Request $request, string $type)
    {
        abort_unless(in_array($type, self::TYPES, true), 404);

        $data = $this->build($type, $request);

        $html = view('backend.pages.reports.export_pdf', ['d' => $data])->render();

        $dompdf = new \Dompdf\Dompdf([
            'isRemoteEnabled' => false,
            'defaultFont' => 'DejaVu Sans',
        ]);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('a4', 'landscape');
        $dompdf->render();

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $data['file'] . '.pdf"',
        ]);
    }

    /**
     * Build title / columns / rows / summary for one report type,
     * applying the exact same filters as the on-screen report.
     */
    private function build(string $type, Request $request): array
    {
        $employees = Employee::pluck('name', 'id');
        $agencies = Agency::pluck('name', 'id');
        $q = $request->query('q', '');
        $dateFrom = $request->query('date_from', '');
        $dateTo = $request->query('date_to', '');

        // Human-readable line about active filters
        $filters = [];
        if ($q !== '') {
            $filters[] = 'Search: "' . $q . '"';
        }
        if ($dateFrom !== '' || $dateTo !== '') {
            $filters[] = 'Date: ' . ($dateFrom ?: '…') . ' to ' . ($dateTo ?: '…');
        }
        $filterLine = count($filters) ? implode(' | ', $filters) : 'All records';

        if ($type === 'processing') {
            $status = $request->query('status', '');
            $employeeId = $request->query('employee_id', '');
            $agencyId = $request->query('agency_id', '');
            $collected = $request->query('collected', '');

            if ($status !== '') {
                $filters[] = 'Status: ' . $status;
            }
            if ($employeeId !== '') {
                $filters[] = 'Employee: ' . ($employees[$employeeId] ?? '—');
            }
            if ($agencyId !== '') {
                $filters[] = 'Agency: ' . ($agencies[$agencyId] ?? '—');
            }
            if ($collected !== '') {
                $filters[] = 'Collected: ' . str_replace('_', ' ', $collected);
            }
            $filterLine = count($filters) ? implode(' | ', $filters) : 'All records';

            $query = PassportProcessing::with([
                'passport:id,passport_number,applicant_name',
                'employee:id,name',
                'agency:id,name',
                'passport.collections:id,passport_id',
            ])->orderByDesc('created_at');

            if ($status !== '') {
                $query->where('status', $status);
            }
            if ($employeeId !== '') {
                $query->where('employee_id', $employeeId);
            }
            if ($agencyId !== '') {
                $query->where('agency_id', $agencyId);
            }
            if ($q !== '') {
                $query->whereHas('passport', function ($qq) use ($q) {
                    $qq->where('passport_number', 'like', "%{$q}%")
                        ->orWhere('applicant_name', 'like', "%{$q}%");
                });
            }
            if ($dateFrom !== '') {
                $query->whereDate('created_at', '>=', $dateFrom);
            }
            if ($dateTo !== '') {
                $query->whereDate('created_at', '<=', $dateTo);
            }
            if ($collected === 'collected') {
                $query->whereHas('passport.collections');
            } elseif ($collected === 'not_collected') {
                $query->whereDoesntHave('passport.collections');
            }

            $rows = $query->get()->map(fn ($r) => [
                $r->passport?->passport_number ?? '—',
                $r->passport?->applicant_name ?? '—',
                $r->employee?->name ?? '—',
                $r->agency?->name ?? 'Unassigned',
                $r->status,
                ($r->passport && $r->passport->collections->isNotEmpty()) ? 'Collected' : 'Not Collected',
                optional($r->created_at)->format('d M Y, h:i A'),
            ])->all();

            $counts = PassportProcessing::selectRaw('status, COUNT(*) c')
                ->when($q !== '', fn ($qq) => $qq->whereHas('passport', fn ($p) => $p
                    ->where('passport_number', 'like', "%{$q}%")
                    ->orWhere('applicant_name', 'like', "%{$q}%")))
                ->when($dateFrom !== '', fn ($qq) => $qq->whereDate('created_at', '>=', $dateFrom))
                ->when($dateTo !== '', fn ($qq) => $qq->whereDate('created_at', '<=', $dateTo))
                ->groupBy('status')->pluck('c', 'status');

            return [
                'type' => $type,
                'title' => 'Processing Report',
                'subtitle' => 'Every processing entry with its current outcome',
                'file' => 'processing-report-' . now()->format('Ymd-His'),
                'filters' => $filterLine,
                'columns' => ['SL', 'Passport No', 'Applicant', 'Employee', 'Agency', 'Status', 'Collected', 'Created'],
                'rows' => $rows,
                'summary' => [
                    'Total' => $counts->sum(),
                    'Pending' => $counts['PENDING'] ?? 0,
                    'In Progress' => $counts['IN_PROGRESS'] ?? 0,
                    'Done' => $counts['DONE'] ?? 0,
                    'Rejected' => $counts['REJECTED'] ?? 0,
                ],
            ];
        }

        if ($type === 'collection') {
            $employeeId = $request->query('employee_id', '');
            $agencyId = $request->query('agency_id', '');

            if ($employeeId !== '') {
                $filters[] = 'Collected By: ' . ($employees[$employeeId] ?? '—');
            }
            if ($agencyId !== '') {
                $filters[] = 'Agency: ' . ($agencies[$agencyId] ?? '—');
            }
            $filterLine = count($filters) ? implode(' | ', $filters) : 'All records';

            $query = PassportCollection::with([
                'employee:id,name',
                'passport:id,passport_number,applicant_name,agent_id',
                'passport.agent:id,name',
                'passport.processings.agency:id,name',
            ])->orderByDesc('collected_at');

            if ($employeeId !== '') {
                $query->where('employee_id', $employeeId);
            }
            if ($q !== '') {
                $query->whereHas('passport', function ($qq) use ($q) {
                    $qq->where('passport_number', 'like', "%{$q}%")
                        ->orWhere('applicant_name', 'like', "%{$q}%");
                });
            }
            if ($dateFrom !== '') {
                $query->whereDate('collected_at', '>=', $dateFrom);
            }
            if ($dateTo !== '') {
                $query->whereDate('collected_at', '<=', $dateTo);
            }
            if ($agencyId !== '') {
                $query->whereHas('passport.processings', function ($qq) use ($agencyId) {
                    $qq->where('agency_id', $agencyId);
                });
            }

            $all = $query->get();
            $rows = $all->map(fn ($r) => [
                $r->passport?->passport_number ?? '—',
                $r->passport?->applicant_name ?? '—',
                $r->passport?->agent?->name ?? '—',
                optional($r->passport?->processings->first()?->agency)->name ?? '—',
                $r->employee?->name ?? '—',
                optional($r->collected_at)->format('d M Y, h:i A'),
            ])->all();

            return [
                'type' => $type,
                'title' => 'Collection Report',
                'subtitle' => 'Every passport handover recorded on the platform',
                'file' => 'collection-report-' . now()->format('Ymd-His'),
                'filters' => $filterLine,
                'columns' => ['SL', 'Passport No', 'Applicant', 'Agent', 'Agency', 'Collected By', 'Collected At'],
                'rows' => $rows,
                'summary' => [
                    'Total' => $all->count(),
                    'Today' => $all->where('collected_at', '>=', now()->startOfDay())->count(),
                    'This Week' => $all->where('collected_at', '>=', now()->startOfWeek())->count(),
                    'This Month' => $all->where('collected_at', '>=', now()->startOfMonth())->count(),
                ],
            ];
        }

        // summary (lifecycle)
        $stage = $request->query('stage', '');
        $employeeId = $request->query('employee_id', '');
        $agencyId = $request->query('agency_id', '');

        if ($stage !== '') {
            $filters[] = 'Stage: ' . ucfirst($stage);
        }
        if ($employeeId !== '') {
            $filters[] = 'Employee: ' . ($employees[$employeeId] ?? '—');
        }
        if ($agencyId !== '') {
            $filters[] = 'Agency: ' . ($agencies[$agencyId] ?? '—');
        }
        $filterLine = count($filters) ? implode(' | ', $filters) : 'All records';

        $query = Passport::with([
            'processings' => function ($qq) {
                $qq->with(['employee:id,name', 'agency:id,name'])->orderByDesc('id');
            },
            'collections' => function ($qq) {
                $qq->with(['employee:id,name'])->orderByDesc('id');
            },
        ])->orderByDesc('id');

        if ($q !== '') {
            $query->where(function ($qq) use ($q) {
                $qq->where('passport_number', 'like', "%{$q}%")
                    ->orWhere('applicant_name', 'like', "%{$q}%");
            });
        }
        if ($dateFrom !== '') {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo !== '') {
            $query->whereDate('created_at', '<=', $dateTo);
        }
        if ($employeeId !== '') {
            $query->whereHas('processings', fn ($qq) => $qq->where('employee_id', $employeeId));
        }
        if ($agencyId !== '') {
            $query->whereHas('processings', fn ($qq) => $qq->where('agency_id', $agencyId));
        }
        if ($stage === 'collected') {
            $query->whereHas('collections');
        } elseif ($stage === 'processing') {
            $query->whereHas('processings')->whereDoesntHave('collections');
        } elseif ($stage === 'pending') {
            $query->whereDoesntHave('processings');
        }

        $all = $query->get();
        $rows = $all->map(function ($p) {
            $proc = $p->processings->first();
            $col = $p->collections->first();

            return [
                $p->passport_number,
                $p->applicant_name,
                $proc?->status ?? '—',
                $proc?->employee?->name ?? '—',
                $proc?->agency?->name ?? '—',
                $col ? 'Collected' : 'Not Collected',
                $col?->employee?->name ?? '—',
                optional($col?->collected_at)->format('d M Y, h:i A') ?? '—',
            ];
        })->all();

        return [
            'type' => $type,
            'title' => 'Summary Report (Lifecycle)',
            'subtitle' => "Each passport's journey from intake to delivery",
            'file' => 'summary-report-' . now()->format('Ymd-His'),
            'filters' => $filterLine,
            'columns' => ['SL', 'Passport No', 'Applicant', 'Latest Processing', 'Processing Employee', 'Processing Agency', 'Collected?', 'Collected By', 'Collection Date'],
            'rows' => $rows,
            'summary' => [
                'Total Passports' => $all->count(),
                'Collected' => $all->filter(fn ($p) => $p->collections->isNotEmpty())->count(),
                'In Processing' => $all->filter(fn ($p) => $p->processings->isNotEmpty() && $p->collections->isEmpty())->count(),
                'No Processing' => $all->filter(fn ($p) => $p->processings->isEmpty())->count(),
            ],
        ];
    }

    /**
     * Styled SpreadsheetML (Excel 2003 XML). Supports merged title cells,
     * fills, bold headers, column widths and borders — well-organised output.
     */
    private function spreadsheetXml(array $d): string
    {
        $esc = fn ($v) => htmlspecialchars((string) $v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $colCount = count($d['columns']);

        $cell = function ($value, $style = '', $mergeAcross = 0) use ($esc) {
            $merge = $mergeAcross > 0 ? ' ss:MergeAcross="' . $mergeAcross . '"' : '';
            if (is_numeric($value) && ! is_string($value)) {
                return '<Cell ss:StyleID="' . $style . '"' . $merge . '><Data ss:Type="Number">' . (int) $value . '</Data></Cell>';
            }

            return '<Cell ss:StyleID="' . $style . '"' . $merge . '><Data ss:Type="String">' . $esc($value) . '</Data></Cell>';
        };

        // Column widths (pt)
        $widths = [40, 110, 130, 120, 130, 110, 120, 140, 140];
        $cols = '';
        for ($i = 0; $i < $colCount; $i++) {
            $cols .= '<Column ss:AutoFitWidth="0" ss:Width="' . ($widths[$i] ?? 100) . '"/>';
        }

        $xml = '<?xml version="1.0"?>' . PHP_EOL;
        $xml .= '<?mso-application progid="Excel.Sheet"?>' . PHP_EOL;
        $xml .= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"'
            . ' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">'
            . '<Styles>'
            . '<Style ss:ID="title"><Font ss:Bold="1" ss:Size="14" ss:Color="#FFFFFF"/><Interior ss:Color="#0F3460" ss:Pattern="Solid"/><Alignment ss:Vertical="Center"/></Style>'
            . '<Style ss:ID="meta"><Font ss:Color="#555555" ss:Size="9"/></Style>'
            . '<Style ss:ID="head"><Font ss:Bold="1" ss:Color="#FFFFFF"/><Interior ss:Color="#4E86E8" ss:Pattern="Solid"/><Alignment ss:Horizontal="Center" ss:Vertical="Center"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#2F5CB0"/></Borders></Style>'
            . '<Style ss:ID="cell"><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E2E8F0"/></Borders><Alignment ss:Vertical="Center"/></Style>'
            . '<Style ss:ID="cellAlt"><Interior ss:Color="#F4F7FC" ss:Pattern="Solid"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E2E8F0"/></Borders><Alignment ss:Vertical="Center"/></Style>'
            . '<Style ss:ID="sumLabel"><Font ss:Bold="1"/><Interior ss:Color="#EEF3FB" ss:Pattern="Solid"/></Style>'
            . '<Style ss:ID="sumVal"><Font ss:Bold="1" ss:Color="#0F3460"/><Interior ss:Color="#EEF3FB" ss:Pattern="Solid"/></Style>'
            . '</Styles>'
            . '<Worksheet ss:Name="' . $esc($d['title']) . '">'
            . '<Table>' . $cols;

        // Title block (merged across all columns)
        $xml .= '<Row ss:Height="26">' . $cell($d['title'] . '  —  Passport MS', 'title', $colCount - 1) . '</Row>';
        $xml .= '<Row>' . $cell($d['subtitle'], 'meta', $colCount - 1) . '</Row>';
        $xml .= '<Row>' . $cell('Generated: ' . now()->format('d M Y, h:i A') . '    Filters: ' . $d['filters'], 'meta', $colCount - 1) . '</Row>';
        $xml .= '<Row/>';

        // Header
        $xml .= '<Row ss:Height="20">';
        foreach ($d['columns'] as $col) {
            $xml .= $cell($col, 'head');
        }
        $xml .= '</Row>';

        // Data rows (zebra)
        $i = 0;
        foreach ($d['rows'] as $row) {
            $style = ($i % 2 === 1) ? 'cellAlt' : 'cell';
            $xml .= '<Row>';
            $xml .= $cell($i + 1, $style);
            foreach ($row as $value) {
                $xml .= $cell($value, $style);
            }
            $xml .= '</Row>';
            $i++;
        }

        if ($i === 0) {
            $xml .= '<Row>' . $cell('No data found for the selected filters.', 'meta', $colCount - 1) . '</Row>';
        }

        // Summary footer
        $xml .= '<Row/><Row>' . $cell('Summary', 'sumLabel', 1);
        for ($k = 2; $k < $colCount; $k++) {
            $xml .= $cell('', 'sumLabel');
        }
        $xml .= '</Row>';
        foreach ($d['summary'] as $label => $value) {
            $xml .= '<Row>' . $cell($label, 'sumLabel') . $cell($value, 'sumVal') . '</Row>';
        }

        $xml .= '</Table>'
            . '<WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">'
            . '<FreezePanes/><FrozenNoSplit/><SplitHorizontal>5</SplitHorizontal><TopRowBottomPane>5</TopRowBottomPane>'
            . '<ActivePane>2</ActivePane></WorksheetOptions>'
            . '</Worksheet></Workbook>';

        return $xml;
    }
}
