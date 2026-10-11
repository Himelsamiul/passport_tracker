<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

/*
 * ফিচার নোট (ট্র্যাক রেকর্ড পেজ — এই ফাইলে যা কাজ হয়েছে):
 * • সব অ্যাডমিন অ্যাক্টিভিটির অডিট লগ দেখায়: কে, কখন, কী করলো
 *   (create / update / delete / login / logout / failed login)।
 * • ফিল্টার: ইউজার, মডিউল, অ্যাকশন, সার্চ, তারিখ রেঞ্জ + প্রিসেট + পেজিনেশন।
 * • অ্যাকসেস: perm:audit.view (সুপার অ্যাডমিন ডিফল্ট, রোলে দেওয়া যায়)।
 */

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $search   = trim((string) $request->query('q', ''));
        $userId   = $request->query('user_id', '');
        $module   = $request->query('module', '');
        $action   = $request->query('action', '');
        $dateFrom = (string) $request->query('date_from', '');
        $dateTo   = (string) $request->query('date_to', '');

        $q = ActivityLog::query()->latest('id');

        if ($search !== '') {
            $q->where(function ($qq) use ($search) {
                $qq->where('description', 'like', "%{$search}%")
                    ->orWhere('user_name', 'like', "%{$search}%");
            });
        }
        if ($userId !== '') {
            $q->where('user_id', $userId);
        }
        if ($module !== '') {
            $q->where('module', $module);
        }
        if ($action !== '') {
            $q->where('action', $action);
        }
        if ($dateFrom !== '') {
            $q->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo !== '') {
            $q->whereDate('created_at', '<=', $dateTo);
        }

        $logs = $q->paginate(50)->withQueryString();

        return view('backend.pages.admin.audit.index', [
            'logs'      => $logs,
            'search'    => $search,
            'userId'    => $userId,
            'module'    => $module,
            'action'    => $action,
            'dateFrom'  => $dateFrom,
            'dateTo'    => $dateTo,
            'users'     => User::orderBy('name')->get(['id', 'name']),
            'modules'   => ActivityLog::select('module')->distinct()->orderBy('module')->pluck('module'),
            'actions'   => ['created', 'updated', 'deleted', 'login', 'logout', 'login_failed'],
        ]);
    }
}
