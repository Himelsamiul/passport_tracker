<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">

    <!-- Google fonts - Poppins for copy-->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&amp;display=swap" rel="stylesheet">

    <!-- Bootstrap CSS (পুরনো theme CDN মরে গেছে, তাই jsdelivr থেকে) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Favicon-->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.17/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
      body{ font-family:'Poppins',system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif; }

      .notify {
        z-index: 9999;
        justify-content: start;
      }
      /* Bubbly theme এর .page-holder/.bg-gray-100 এর বিকল্প */
      .page-holder {
        background:#f4f6fa;
        min-height:calc(100vh - 62px);
        padding:20px;
        flex:1 1 auto;
      }
      .header{ position:sticky; top:0; z-index:1030; }
    </style>
</head>

<body>
    <!-- navbar-->
    @include('backend.pages.partial.header')

    <div class="d-flex align-items-stretch">
        @include('backend.pages.partial.sidebar')

        <div class="page-holder bg-gray-100">
            <div class="container">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- JavaScript files -->
    @include('notify::components.notify')
    @notifyJs
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.17/dist/sweetalert2.min.js"></script>

    <!-- Push custom scripts for SweetAlert -->
    @stack('scripts')

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('collapsed');
        }
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        });
    </script>
</body>
</html>
