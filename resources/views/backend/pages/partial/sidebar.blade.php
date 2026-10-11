@php

$user = session('user') ?? Auth::user();
$initial = mb_strtoupper(mb_substr($user->name ?? 'A', 0, 1));

// Sidebar structure: grouped flat navigation. `perm` filters visibility by
// role (can_do). Group headers hide themselves when no item is visible.
$groups = [
    [
        'label' => 'Overview',
        'items' => [
            ['route' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'fa-gauge-high', 'match' => 'dashboard', 'perm' => 'dashboard.view'],
        ],
    ],
    [
        'label' => 'Operations',
        'items' => [
            ['route' => 'passports.index',  'label' => 'Passports',     'icon' => 'fa-passport', 'match' => 'passports.*', 'perm' => 'passports.view'],
            ['route' => 'processings.index','label' => 'Processing',    'icon' => 'fa-gears',    'match' => 'processings.*', 'perm' => 'processings.view'],
            ['route' => 'collections.index','label' => 'Collections',   'icon' => 'fa-box-open', 'match' => 'collections.*', 'perm' => 'collections.view'],
            ['route' => 'register.list',    'label' => 'Registrations', 'icon' => 'fa-user-plus','match' => 'register.*', 'perm' => 'registrations.view'],
        ],
    ],
    [
        'label' => 'Directory',
        'items' => [
            ['route' => 'agents.index',     'label' => 'Agents',     'icon' => 'fa-user-tie',    'match' => 'agents.*', 'perm' => 'agents.view'],
            ['route' => 'agencies.index',   'label' => 'Agencies',   'icon' => 'fa-building',    'match' => 'agencies.*', 'perm' => 'agencies.view'],
            ['route' => 'officers.index',   'label' => 'Officers',   'icon' => 'fa-user-shield', 'match' => 'officers.*', 'perm' => 'officers.view'],
            ['route' => 'employees.index',  'label' => 'Employees',  'icon' => 'fa-users',       'match' => 'employees.*', 'perm' => 'employees.view'],
            ['route' => 'categories.index', 'label' => 'Categories', 'icon' => 'fa-layer-group', 'match' => 'categories.*', 'perm' => 'categories.view'],
        ],
    ],
    [
        'label' => 'Reports',
        'items' => [
            ['route' => 'reports.processing', 'label' => 'Processing Report', 'icon' => 'fa-tasks',       'match' => 'reports.processing', 'perm' => 'reports.view'],
            ['route' => 'reports.collection', 'label' => 'Collection Report', 'icon' => 'fa-box-archive', 'match' => 'reports.collection', 'perm' => 'reports.view'],
            ['route' => 'reports.summary',    'label' => 'Summary Report',    'icon' => 'fa-chart-pie',   'match' => 'reports.summary',    'perm' => 'reports.view'],
        ],
    ],
    [
        'label' => 'Administration',
        'items' => [
            ['route' => 'admin.roles.index', 'label' => 'Roles & Permissions', 'icon' => 'fa-user-lock', 'match' => 'admin.roles.*', 'perm' => 'roles.view'],
            ['route' => 'admin.users.index', 'label' => 'Admin Users',         'icon' => 'fa-users-gear','match' => 'admin.users.*', 'perm' => 'admin-users.view'],
            ['route' => 'admin.audit.index', 'label' => 'Track Record',        'icon' => 'fa-clipboard-list', 'match' => 'admin.audit*', 'perm' => 'audit.view'],
        ],
    ],
];

// Keep only the items the current admin may see, then drop empty groups.
$groups = collect($groups)
    ->map(function ($group) {
        $group['items'] = array_values(array_filter(
            $group['items'],
            fn ($item) => ! isset($item['perm']) || can_do($item['perm'])
        ));
        return $group;
    })
    ->filter(fn ($group) => count($group['items']) > 0)
    ->values()
    ->all();
@endphp

<aside class="app-sidebar" id="sidebar">
    <div class="sidebar-brand">
        <span class="brand-icon"><i class="fas fa-suitcase-rolling"></i></span>
        <div class="brand-text">
            <div class="brand-name">Passport MS</div>
            <div class="brand-sub">Control Center</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        @foreach ($groups as $group)
            <div class="sidebar-group">
                <div class="sidebar-group-label">{{ $group['label'] }}</div>
                @foreach ($group['items'] as $item)
                    <div class="sidebar-item">
                        <a href="{{ route($item['route']) }}"
                           class="sidebar-link {{ request()->routeIs($item['match']) ? 'active' : '' }}">
                            <i class="fas {{ $item['icon'] }}"></i>
                            <span class="link-text">{{ $item['label'] }}</span>
                        </a>
                    </div>
                @endforeach
            </div>
        @endforeach
    </nav>

    <div class="sidebar-foot">
        <div class="sidebar-user">
            <span class="avatar">{{ $initial }}</span>
            <div class="su-meta">
                <div class="su-name">{{ $user->name ?? 'Guest User' }}</div>
                <div class="su-role">Shahriar Worldwide Venture</div>
            </div>
        </div>
        <a href="{{ route('logout') }}" class="signout-btn"
           onclick="return confirm('Are you sure you want to logout?');">
            <i class="fas fa-arrow-right-from-bracket"></i>
            <span class="link-text">Sign out</span>
        </a>
    </div>
</aside>
