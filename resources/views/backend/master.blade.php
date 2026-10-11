<!DOCTYPE html>
{{-- ============================================================
     ডিজাইন নোট (মাস্টার লেআউটে যা কাজ হয়েছে):
     • পুরনো নেভি+গোল্ড থিম বাদ। নতুন ডার্ক থিম (public/css/theme.css) পুরো অ্যাডমিনে বসানো হয়েছে।
     • ফন্ট Poppins বাদ দিয়ে Inter আনা হয়েছে — ডেমোর মতো পরিচ্ছন্ন টাইপোগ্রাফি।
     • নতুন শেল: বামে ফিক্সড সাইডবার + ডানে স্টিকি হেডার ও কনটেন্ট কলাম।
     • মোবাইলে সাইডবার অফ-ক্যানভাস ড্রয়ার, ডেস্কটপে কলাপ্স করা যায় (localStorage-এ মনে থাকে)।
     • নিচে ছোট ফুটার স্ট্রিপ যোগ করা হয়েছে।
     ============================================================ --}}
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('page-title', 'Dashboard') · Passport MS</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <meta name="theme-color" content="#0d1322">
    <meta name="robots" content="noindex">

    <!-- Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.17/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- Admin design system -->
    <link href="{{ asset('css/theme.css') }}?v=3" rel="stylesheet">

    <!-- Flatpickr (date-range filters on list/report pages) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    @stack('styles')
</head>

<body class="app-body">
    <script>
        // Apply the saved theme before first paint (default: dark)
        try {
            if (localStorage.getItem('pms-theme') === 'light') {
                document.body.classList.add('light-mode');
            }
        } catch (e) {}
    </script>

    <div class="app-shell">
        @include('backend.pages.partial.sidebar')

        <div class="app-main">
            @include('backend.pages.partial.header')

            <main class="app-content">
                @yield('content')
            </main>

            <footer class="app-footer">
                <span>&copy; {{ date('Y') }} Shahriar Worldwide Venture</span>
                <span class="dot">·</span>
                <span>Passport Management System</span>
            </footer>
        </div>
    </div>

    <!-- Scripts -->
    @include('notify::components.notify')
    @notifyJs
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.17/dist/sweetalert2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    @stack('scripts')

    <script>
        // Sidebar: mobile drawer + desktop collapse (persisted)
        (function () {
            var sidebar = document.getElementById('sidebar');
            var burger = document.getElementById('sidebarBurger');
            if (!sidebar || !burger) return;

            if (localStorage.getItem('pms-sidebar') === 'collapsed' && window.innerWidth >= 992) {
                sidebar.classList.add('collapsed');
            }

            burger.addEventListener('click', function () {
                if (window.innerWidth >= 992) {
                    sidebar.classList.toggle('collapsed');
                    localStorage.setItem('pms-sidebar', sidebar.classList.contains('collapsed') ? 'collapsed' : 'open');
                } else {
                    document.body.classList.toggle('sidebar-open');
                }
            });

            document.addEventListener('click', function (e) {
                if (document.body.classList.contains('sidebar-open')
                    && !sidebar.contains(e.target)
                    && !burger.contains(e.target)) {
                    document.body.classList.remove('sidebar-open');
                }
            });

            // Tooltips
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
                new bootstrap.Tooltip(el);
            });
        })();
    </script>
</body>
</html>
