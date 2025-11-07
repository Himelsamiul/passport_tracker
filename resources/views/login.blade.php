<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"><title>Admin Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    :root{
      /* === Deep blue theme (change anytime) === */
      --theme-color:#0a1b3d;
      --theme-color-dark:#07142e;
      --theme-focus: rgba(10,27,61,.35);
      --input-text-color:#0a1b3d; /* 🟦 Typing text color */

      --glass-bg: rgba(255,255,255,.10);
      --glass-brd: rgba(255,255,255,.22);
      --glass-shadow: rgba(0,0,0,.45);
      --ink:#e8eefc; 
      --ink-soft:#c9d7fb;
    }

    body{
      min-height:100vh;
      background:url("{{ url('admin2.png') }}") center/cover no-repeat fixed;
      display:flex; align-items:center; justify-content:center;
      padding:20px; color:var(--ink);
      font-family:system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;
    }

    .glass-card{
      width:100%; max-width:430px;
      background:var(--glass-bg);
      border:1px solid var(--glass-brd);
      border-radius:18px;
      backdrop-filter:blur(12px); -webkit-backdrop-filter:blur(12px);
      box-shadow:0 20px 60px var(--glass-shadow);
      animation:fadeIn .9s ease-out both;
    }
    @keyframes fadeIn{from{opacity:0;transform:translateY(18px)}to{opacity:1;transform:translateY(0)}}

    .card-header{
      background:linear-gradient(135deg, var(--theme-color), var(--theme-color-dark));
      color:#fff; text-align:center; font-weight:800; letter-spacing:.3px;
      padding:18px; border-top-left-radius:18px; border-top-right-radius:18px;
    }
    .card-body{ padding:22px; }

    /* Transparent Inputs */
    .form-floating>.form-control{
      background:rgba(255,255,255,.05); /* ✨ transparent look */
      border:1px solid rgba(255,255,255,.25);
      color:var(--input-text-color); /* deep blue text */
      font-weight:500;
    }
    .form-floating label{ color:var(--ink-soft); }
    .form-control::placeholder{ color:#999; }
    .form-control:focus{
      border-color: var(--theme-color);
      box-shadow: 0 0 0 .25rem var(--theme-focus);
      background:rgba(255,255,255,.08); /* keep transparent on focus */
      color:var(--input-text-color);
    }

    /* Deep-blue button */
    .btn-theme{
      background:linear-gradient(135deg, var(--theme-color), var(--theme-color-dark));
      border:none; color:#fff; font-weight:700;
      box-shadow:0 10px 28px rgba(10,27,61,.45);
      transition:transform .15s ease, filter .15s ease, box-shadow .15s ease;
    }
    .btn-theme:hover{ transform:translateY(-2px); filter:brightness(1.06); }

    /* Rows */
    .check-row{ display:flex; align-items:center; gap:10px; margin-bottom:.5rem; }
    .form-check-input{ background:rgba(255,255,255,.07); border-color:rgba(255,255,255,.35); }
    .form-check-input:checked{ background-color:var(--theme-color); border-color:var(--theme-color); }
    .form-check-label{ color:var(--ink-soft); }

    .links-row{
      display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;
      font-size:.95rem;
    }
    .links-row a{ text-decoration:none; }
    .links-row a:first-child{ color:rgba(8, 26, 112, 1); }
    .links-row a:last-child{ color:rgba(158, 6, 6, 1); }

    .tiny{font-size:12px; text-align:center; color:#d5defa; opacity:.85; padding:0 0 12px;}
  </style>
</head>
<body>

  <div class="glass-card">
    <div class="card-header">Admin Login</div>

    <div class="card-body">
      @if(session('error'))
        <div class="alert alert-danger py-2 mb-3">{{ session('error') }}</div>
      @endif

      <form method="POST" action="{{ route('login.submit') }}">
        @csrf

        <div class="form-floating mb-3">
          <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required autofocus>
          <label for="email">Email address</label>
        </div>

        <div class="form-floating mb-3">
          <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
          <label for="password">Password</label>
        </div>

        <div class="check-row">
          <div class="form-check m-0">
            <input class="form-check-input" type="checkbox" id="showOff">
            <label class="form-check-label" for="showOff" style="color: rgba(6, 56, 17, 1);">Keep me signed in</label>
          </div>
        </div>

        <div class="links-row">
          <a href="">Register now</a>
          <a href="">Forgot password?</a>
        </div>

        <button class="btn btn-theme w-100 py-2">Login</button>
      </form>
    </div>

    <div class="tiny">© {{ date('Y') }} — Secure Access Panel</div>
  </div>

</body>
</html>
