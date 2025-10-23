<?php

namespace App\Http\Controllers;

use App\Models\Passport;
use App\Models\Agent;
use App\Models\Employee;
use App\Models\Agency;
use App\Models\Category;
use App\Models\PassportOfficer;
use App\Models\PassportCollection;
use App\Models\PassportProcessing;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Totals
        $totalPassports   = Passport::count();
        $totalAgents      = Agent::count();
        $totalOfficers    = class_exists(PassportOfficer::class) ? PassportOfficer::count() : 0;
        $totalEmployees   = Employee::count();
        $totalAgencies    = Agency::count();
        $totalCategories  = Category::count();
        $totalCollections = PassportCollection::count();
        $totalProcessings = PassportProcessing::count();

        // Active processing = processings for passports NOT yet collected
        $collectedPassportIds = PassportCollection::pluck('passport_id')->unique()->toArray();
        $activeProcessings    = PassportProcessing::whereNotIn('passport_id', $collectedPassportIds)->count();

        // Latest processing per passport (by MAX(id); switch to MAX(created_at) if you prefer)
        $latestIds = PassportProcessing::select(DB::raw('MAX(id) as id'))
            ->groupBy('passport_id')
            ->pluck('id');

        $latestProcessings = PassportProcessing::with('passport')
            ->whereIn('id', $latestIds)
            ->get();

        // Map all your statuses to four buckets
        $mapToBucket = function (?string $status) {
            $s = strtolower((string)$status);
            return match (true) {
                in_array($s, ['pending','received','submitted'])                    => 'pending',
                in_array($s, ['processing','in process','ongoing','under process']) => 'ongoing',
                in_array($s, ['approved','delivered','done','completed','issued'])  => 'done',
                in_array($s, ['rejected','failed','declined'])                      => 'rejected',
                default => 'ongoing',
            };
        };

        $statusCounts = ['pending'=>0,'ongoing'=>0,'done'=>0,'rejected'=>0];
        foreach ($latestProcessings as $p) {
            $statusCounts[$mapToBucket($p->status)]++;
        }

        // ✅ Total Processing (sum of the 4 latest-status buckets)
        $processingLatestTotal = array_sum($statusCounts);

        // ✅ (Optional) Money totals (ONLY if you have a numeric column on passport_processings, e.g. 'amount')
        // Comment these 6 lines out if you don't track amounts.
        $AMOUNT_COLUMN = 'amount'; // <- change to your real column if different
        $sumDone = $latestProcessings->filter(fn($p) => $mapToBucket($p->status) === 'done')
                                     ->sum($AMOUNT_COLUMN);
        $sumRejected = $latestProcessings->filter(fn($p) => $mapToBucket($p->status) === 'rejected')
                                         ->sum($AMOUNT_COLUMN);

        return view('backend.pages.fixed.dashboard', [
            'stats' => [
                'passports'          => $totalPassports,
                'agents'             => $totalAgents,
                'officers'           => $totalOfficers,
                'employees'          => $totalEmployees,
                'agencies'           => $totalAgencies,
                'categories'         => $totalCategories,

                // All records in passport_processings (historical rows)
                'processings_total'  => $totalProcessings,

                // Not collected yet
                'processings_active' => $activeProcessings,

                'collections'        => $totalCollections,
            ],

            // Latest-status buckets for cards & pie
            'statusCounts' => $statusCounts,

            // ✅ Use this for the "Total Processing" card (one card for all 4 statuses)
            'processingLatestTotal' => $processingLatestTotal,

            // ✅ (Optional) show on UI if you want “Done Amount” / “Rejected Amount”
            'sumDone'     => $sumDone ?? 0,
            'sumRejected' => $sumRejected ?? 0,
        ]);
    }
}
