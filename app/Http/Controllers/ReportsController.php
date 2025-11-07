<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PassportProcessing;
use App\Models\PassportCollection;
use App\Models\Passport;
use App\Models\Employee;
use App\Models\Agency;

class ReportsController extends Controller
{
    /** -------------------------
     *  1) Processing Report
     *  ------------------------*/
    public function processing(Request $request)
    {
        $status     = $request->query('status');      // PENDING | IN_PROGRESS | DONE | REJECTED
        $employeeId = $request->query('employee_id'); // int
        $agencyId   = $request->query('agency_id');   // int
        $search     = $request->query('q');           // applicant or passport no
        $dateFrom   = $request->query('date_from');   // YYYY-MM-DD
        $dateTo     = $request->query('date_to');     // YYYY-MM-DD
        $collected  = $request->query('collected');   // collected | not_collected | (null=all)

        $q = PassportProcessing::with([
            'passport:id,passport_number,applicant_name',
            'employee:id,name',
            'agency:id,name',
            'passport.collections:id,passport_id' // for collected check
        ])->orderByDesc('created_at');

        if ($status)     $q->where('status', $status);
        if ($employeeId) $q->where('employee_id', $employeeId);
        if ($agencyId)   $q->where('agency_id', $agencyId);

        if ($search) {
            $q->whereHas('passport', function ($qq) use ($search) {
                $qq->where('passport_number', 'like', "%{$search}%")
                   ->orWhere('applicant_name', 'like', "%{$search}%");
            });
        }

        if ($dateFrom) $q->whereDate('created_at', '>=', $dateFrom);
        if ($dateTo)   $q->whereDate('created_at', '<=', $dateTo);

        // collected filter
        if ($collected === 'collected') {
            $q->whereHas('passport.collections');
        } elseif ($collected === 'not_collected') {
            $q->whereDoesntHave('passport.collections');
        }

        $rows = $q->paginate(25)->withQueryString();

        $employees = Employee::select('id','name')->orderBy('name')->get();
        $agencies  = Agency::select('id','name')->orderBy('name')->get();

        // Simple totals (based on current filters)
        $totals = [
            'total'       => (clone $q)->count(),
            'pending'     => (clone $q)->where('status','PENDING')->count(),
            'in_progress' => (clone $q)->where('status','IN_PROGRESS')->count(),
            'done'        => (clone $q)->where('status','DONE')->count(),
            'rejected'    => (clone $q)->where('status','REJECTED')->count(),
        ];

        return view('backend.pages.reports.processing', compact(
            'rows','employees','agencies','totals',
            'status','employeeId','agencyId','search','dateFrom','dateTo','collected'
        ));
    }

    /** -------------------------
     *  2) Collection Report
     *  ------------------------*/
    public function collection(Request $request)
    {
        $employeeId = $request->query('employee_id'); // who collected
        $agencyId   = $request->query('agency_id');   // last processing agency
        $search     = $request->query('q');           // applicant or passport no
        $dateFrom   = $request->query('date_from');
        $dateTo     = $request->query('date_to');

        $q = PassportCollection::with([
            'employee:id,name',
            'passport:id,passport_number,applicant_name,agent_id',
            'passport.agent:id,name',
            'passport.processings.agency:id,name', // to show last agency
        ])->orderByDesc('collected_at');

        if ($employeeId) $q->where('employee_id', $employeeId);
        if ($search) {
            $q->whereHas('passport', function($qq) use ($search) {
                $qq->where('passport_number','like',"%{$search}%")
                   ->orWhere('applicant_name','like',"%{$search}%");
            });
        }
        if ($dateFrom) $q->whereDate('collected_at','>=',$dateFrom);
        if ($dateTo)   $q->whereDate('collected_at','<=',$dateTo);

        // Filter by agency via latest processing agency (optional)
        if ($agencyId) {
            $q->whereHas('passport.processings', function($qq) use ($agencyId) {
                $qq->where('agency_id', $agencyId);
            });
        }

        $rows = $q->paginate(25)->withQueryString();
        $employees = Employee::select('id','name')->orderBy('name')->get();
        $agencies  = Agency::select('id','name')->orderBy('name')->get();

        // Simple totals
        $totals = [
            'total'      => (clone $q)->count(),
            'today'      => (clone $q)->whereDate('collected_at', now()->toDateString())->count(),
            'this_week'  => (clone $q)->whereBetween('collected_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => (clone $q)->whereBetween('collected_at', [now()->startOfMonth(), now()->endOfMonth()])->count(),
        ];

        return view('backend.pages.reports.collection', compact(
            'rows','employees','agencies','totals',
            'employeeId','agencyId','search','dateFrom','dateTo'
        ));
    }

    /** -------------------------
     *  3) Summary (Lifecycle) Report
     *  ------------------------*/
    public function summary(Request $request)
    {
        $employeeId = $request->query('employee_id'); // processing employee
        $agencyId   = $request->query('agency_id');
        $search     = $request->query('q');
        $dateFrom   = $request->query('date_from');
        $dateTo     = $request->query('date_to');
        $stage      = $request->query('stage'); // processing | collected | pending

        // Base: all passports with relations
        $q = Passport::with([
            'processings' => function($qq){
                $qq->with(['employee:id,name','agency:id,name'])->orderByDesc('id');
            },
            'collections' => function($qq){
                $qq->with(['employee:id,name'])->orderByDesc('id');
            },
        ])->orderByDesc('id');

        if ($search) {
            $q->where(function($qq) use ($search){
                $qq->where('passport_number','like',"%{$search}%")
                   ->orWhere('applicant_name','like',"%{$search}%");
            });
        }

        // Date filters apply to created_at of passport for simplicity
        if ($dateFrom) $q->whereDate('created_at','>=',$dateFrom);
        if ($dateTo)   $q->whereDate('created_at','<=',$dateTo);

        // Filter by employee/agency on latest processing
        if ($employeeId) {
            $q->whereHas('processings', function($qq) use ($employeeId){
                $qq->where('employee_id', $employeeId);
            });
        }
        if ($agencyId) {
            $q->whereHas('processings', function($qq) use ($agencyId){
                $qq->where('agency_id', $agencyId);
            });
        }

        // Stage
        if ($stage === 'collected') {
            $q->whereHas('collections');
        } elseif ($stage === 'processing') {
            $q->whereHas('processings')->whereDoesntHave('collections');
        } elseif ($stage === 'pending') {
            $q->whereDoesntHave('processings');
        }

        $rows = $q->paginate(25)->withQueryString();
        $employees = Employee::select('id','name')->orderBy('name')->get();
        $agencies  = Agency::select('id','name')->orderBy('name')->get();

        return view('backend.pages.reports.summary', compact(
            'rows','employees','agencies','employeeId','agencyId','search','dateFrom','dateTo','stage'
        ));
    }
}
