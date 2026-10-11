{{-- ============================================================
     ডিজাইন নোট (হেডারে যা কাজ হয়েছে):
     • পরিষ্কার ডার্ক স্টিকি হেডার — বামে হ্যামবার্গার + পেজ টাইটেল/সাবটাইটেল।
     • ডানে: লাইভ ঘড়ির চিপ, থিম টগল (🌙/☀️ — ডার্ক ডিফল্ট, localStorage-এ মনে থাকে),
       নোটিফিকেশন বেল (সর্বশেষ প্রসেসিং/কলেকশন অ্যাক্টিভিটি; ২৪ ঘণ্টার মধ্যে নতুন
       হলে লাল ব্যাজ — ড্রপডাউন খুললে "seen" হয়ে ব্যাজ চলে যায়), ইউজার ড্রপডাউন।
     • পেজ টাইটেল @section('page-title') দিয়ে ওভাররাইড করা যায়, নাহলে রুট অনুযায়ী অটো।
     ============================================================ --}}
@php
$user = session('user') ?? Auth::user();

// Per-page title/subtitle come from @section('page-title'/'page-subtitle');
// fall back to sensible route-based defaults.
$routeNames = [
    'dashboard'          => ['Dashboard', 'Platform overview at a glance'],
    'agents.*'           => ['Agents', 'People who submit passports to the desk'],
    'passports.*'        => ['Passports', 'Registry of every application received'],
    'employees.*'        => ['Employees', 'Internal staff handling passport files'],
    'categories.*'       => ['Working Categories', 'Service categories used for agencies'],
    'agencies.*'         => ['Agencies', 'Partner agencies processing abroad'],
    'officers.*'         => ['Passport Officers', 'Officers responsible for processing'],
    'processings.*'      => ['Processing', 'Track each passport through its stages'],
    'register.*'         => ['Registrations', 'Public registration submissions'],
    'collections.*'      => ['Passport Collections', 'Handover records for delivered passports'],
    'reports.*'          => ['Reports', 'Operational and lifecycle reporting'],
];
$title = null; $sub = null;
foreach ($routeNames as $pattern => $t) {
    if (request()->routeIs($pattern)) { $title = $t[0]; $sub = $t[1]; break; }
}
$title = trim($__env->yieldContent('page-title')) ?: $title;
$sub   = trim($__env->yieldContent('page-subtitle')) ?: $sub;
$initial = mb_strtoupper(mb_substr($user->name ?? 'A', 0, 1));

// ---- Notifications: latest processing + collection activity ----
$notifications = collect();
$notifCount = 0;
try {
    $procs = \App\Models\PassportProcessing::with('passport')->latest('id')->limit(6)->get()
        ->map(fn ($p) => [
            'icon'  => 'fa-gears',
            'tone'  => '',
            'title' => 'Processing · ' . ($p->passport->passport_number ?? '—') . ' — ' . ($p->status ?? ''),
            'time'  => optional($p->created_at)->diffForHumans(),
            'at'    => optional($p->created_at)?->getTimestamp() ?? 0,
            'url'   => route('processings.index'),
        ]);

    $cols = \App\Models\PassportCollection::with('passport')->latest('id')->limit(6)->get()
        ->map(fn ($c) => [
            'icon'  => 'fa-box-open',
            'tone'  => 'green',
            'title' => 'Collection · ' . ($c->passport->passport_number ?? '—'),
            'time'  => optional($c->collected_at ?? $c->created_at)->diffForHumans(),
            'at'    => optional($c->collected_at ?? $c->created_at)?->getTimestamp() ?? 0,
            'url'   => route('collections.index'),
        ]);

    $notifications = $procs->merge($cols)->sortByDesc('at')->take(7)->values();
    // Badge = items created in the last 24 hours
    $notifCount = $notifications->filter(fn ($n) => $n['at'] >= now()->subDay()->getTimestamp())->count();
} catch (\Throwable $e) {
    // Tables not ready yet — render an empty bell instead of breaking the header.
}

$newestAt = $notifications->max('at') ?? 0;
@endphp

<header class="app-header">
    <button class="sidebar-burger" id="sidebarBurger" type="button" aria-label="Toggle sidebar">
        <i class="fas fa-bars"></i>
    </button>

    <div class="header-titles">
        <h1 class="page-title">{{ $title ?? 'Dashboard' }}</h1>
        @if ($sub)<p class="page-subtitle">{{ $sub }}</p>@endif
    </div>

    <div class="header-actions">
        <span class="clock-chip"><i class="far fa-clock"></i><span id="headerClock">--:--:--</span></span>

        {{-- Theme toggle (dark by default) --}}
        <button class="header-icon-btn" id="themeToggle" type="button" aria-label="Toggle light/dark mode"
                title="Toggle light / dark mode">
            <i class="fas fa-moon" id="themeIcon"></i>
        </button>

        {{-- Notification bell --}}
        <div class="dropdown">
            <button class="header-icon-btn" id="notifBtn" type="button"
                    data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false"
                    aria-label="Notifications" data-newest="{{ $newestAt }}">
                <i class="far fa-bell"></i>
                <span class="notif-badge" id="notifBadge" {{ $notifCount === 0 ? 'hidden' : '' }}>{{ $notifCount > 9 ? '9+' : $notifCount }}</span>
            </button>

            <div class="dropdown-menu dropdown-menu-end notif-menu" aria-labelledby="notifBtn">
                <div class="notif-head"><i class="far fa-bell" style="color:var(--accent-2)"></i> Notifications</div>
                <div class="notif-list">
                    @forelse ($notifications as $n)
                        <a class="notif-item" href="{{ $n['url'] }}">
                            <span class="ni-icon {{ $n['tone'] }}"><i class="fas {{ $n['icon'] }}"></i></span>
                            <span>
                                <span class="ni-title">{{ $n['title'] }}</span>
                                <span class="ni-time">{{ $n['time'] }}</span>
                            </span>
                        </a>
                    @empty
                        <div class="notif-empty">No notifications yet.</div>
                    @endforelse
                </div>
                <div class="notif-foot">
                    <a href="{{ route('processings.index') }}">View all activity</a>
                </div>
            </div>
        </div>

        {{-- User menu --}}
        <div class="dropdown">
            <button class="header-user dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="avatar avatar-sm">{{ $initial }}</span>
                <span class="hu-name">{{ $user->name ?? 'Guest User' }}</span>
                <i class="fas fa-chevron-down" style="font-size:.62rem;color:var(--faint)"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li class="px-3 py-2">
                    <div class="fw-semibold text-capitalize" style="font-size:.88rem">{{ $user->name ?? 'Guest User' }}</div>
                    <div class="text-muted" style="font-size:.74rem">Shahriar Aviation</div>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item d-flex align-items-center gap-2 text-danger" href="{{ route('logout') }}"
                       onclick="return confirm('Are you sure you want to logout?');">
                        <i class="fas fa-arrow-right-from-bracket"></i><span>Sign out</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</header>

<script>
    (function () {
        // ---- Live clock ----
        function tick() {
            var el = document.getElementById('headerClock');
            if (!el) return;
            var now = new Date();
            var h = now.getHours(), m = now.getMinutes(), s = now.getSeconds();
            var ampm = h >= 12 ? 'PM' : 'AM'; h = h % 12 || 12;
            el.textContent = String(h).padStart(2, '0') + ':' + String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0') + ' ' + ampm;
        }
        setInterval(tick, 1000); tick();

        // ---- Theme toggle (dark default) ----
        var themeBtn = document.getElementById('themeToggle');
        var themeIcon = document.getElementById('themeIcon');
        if (themeBtn) {
            // Sync icon with the mode applied before paint
            if (document.body.classList.contains('light-mode')) themeIcon.className = 'fas fa-sun';

            themeBtn.addEventListener('click', function () {
                var light = !document.body.classList.contains('light-mode');
                document.body.classList.toggle('light-mode', light);
                themeIcon.className = light ? 'fas fa-sun' : 'fas fa-moon';
                try { localStorage.setItem('pms-theme', light ? 'light' : 'dark'); } catch (e) {}
                // Let charts on the page re-color themselves
                window.dispatchEvent(new CustomEvent('pms-themechange', { detail: { light: light } }));
            });
        }

        // ---- Notification badge: mark seen when the dropdown opens ----
        var notifBtn = document.getElementById('notifBtn');
        var notifBadge = document.getElementById('notifBadge');
        if (notifBtn && notifBadge) {
            notifBtn.addEventListener('show.bs.dropdown', function () {
                try {
                    var newest = parseInt(notifBtn.dataset.newest || '0', 10);
                    localStorage.setItem('pms-notif-seen', String(newest));
                } catch (e) {}
                notifBadge.hidden = true;
            });

            // On load: hide badge if nothing newer than last seen
            try {
                var seen = parseInt(localStorage.getItem('pms-notif-seen') || '0', 10);
                var newest = parseInt(notifBtn.dataset.newest || '0', 10);
                if (newest > 0 && newest <= seen) notifBadge.hidden = true;
            } catch (e) {}
        }
    })();
</script>
