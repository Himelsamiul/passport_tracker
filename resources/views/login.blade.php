<!doctype html>
{{-- ============================================================
     ডিজাইন নোট (লগইন পেজে যা কাজ হয়েছে):
     • পুরনো গ্লাস-কার্ড + ব্যাকগ্রাউন্ড ইমেজ ডিজাইন পুরোপুরি বাদ।
     • ডেমোর মতো স্প্লিট-স্ক্রিন: বামে ব্র্যান্ড প্যানেল (আইকন টাইল, হেডলাইন, ৪টি ফিচার, কপিরাইট)।
     • ডানে সাইন-ইন কার্ড — "Admin access only" ব্যাজ, ইমেইল/পাসওয়ার্ড, চোখ বাটন, Forgot password।
     • লোগো ইমেজ বাদ — ব্র্যান্ড আইকন টাইল (স্যুটকেস আইকন) ব্যবহার করা হয়েছে।
     • Bootstrap CSS লোড করা হয়েছে যাতে ইউটিলিটি ক্লাস (w-100, d-flex ইত্যাদি) কাজ করে।
     • ফর্মের অ্যাকশন/CSRF/session error আগের মতোই — লজিকে কোনো পরিবর্তন নেই।
     ============================================================ --}}
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Sign in · Passport MS</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <meta name="theme-color" content="#0d1322">
  <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
  <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="{{ asset('css/theme.css') }}?v=3" rel="stylesheet">

  <style>
    /* Login-scoped helpers (Bootstrap utilities + theme tokens) */
    .login-icon-tile {
      width: 46px; height: 46px;
      border-radius: 14px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      flex: 0 0 46px;
      font-size: 1.05rem;
      color: #fff;
      background: linear-gradient(135deg, rgba(255,255,255,.22), rgba(255,255,255,.1));
      box-shadow: inset 0 0 0 1px rgba(255,255,255,.25);
    }
    .login-icon-tile.dark {
      background: linear-gradient(135deg, var(--accent) 0%, #7c5ff0 100%);
      box-shadow: inset 0 0 0 1px rgba(255,255,255,.18), 0 10px 24px -10px rgba(78,134,232,.7);
    }
    .lbp-name { font-size: 1.02rem; font-weight: 650; letter-spacing: -0.01em; color: #fff; }
    .login-mobile-brand { display: flex; align-items: center; gap: 11px; margin-bottom: 22px; }
    .login-mobile-brand .lmb-text { font-weight: 650; font-size: .95rem; color: var(--text); }

    body.login-page .login-form-side .login-card { padding: 34px 32px 30px; }

    /* Left brand panel — assembled from tokens */
    .login-brand-panel {
      position: relative;
      overflow: hidden;
      display: none;
      flex-direction: column;
      justify-content: space-between;
      padding: 52px 56px;
      color: #fff;
      background:
        radial-gradient(90% 70% at 110% -10%, rgba(78, 134, 232, 0.35) 0%, transparent 55%),
        radial-gradient(70% 60% at -15% 110%, rgba(124, 95, 240, 0.28) 0%, transparent 55%),
        linear-gradient(165deg, #123a72 0%, var(--accent-deep) 48%, #0a2647 100%);
    }
    @media (min-width: 992px) { .login-brand-panel { display: flex; } }
    .lbp-glow { pointer-events:none; position:absolute; border-radius:50%; filter:blur(70px); }
    .lbp-glow-1 { width:320px; height:320px; top:-110px; right:-90px; background:rgba(255,255,255,.09); }
    .lbp-glow-2 { width:380px; height:380px; bottom:-150px; left:-110px; background:rgba(56,200,240,.14); }
    .lbp-brand { position:relative; display:flex; align-items:center; gap:13px; }
    .lbp-headline {
      position: relative;
      font-size: clamp(1.7rem, 2.6vw, 2.3rem);
      font-weight: 700;
      line-height: 1.22;
      letter-spacing: -0.025em;
      max-width: 21em;
      color: #fff;
    }
    .lbp-tagline {
      position: relative;
      margin: 14px 0 0;
      max-width: 30em;
      font-size: 0.92rem;
      line-height: 1.65;
      color: rgba(255, 255, 255, 0.62);
    }
    .login-features { position: relative; display: grid; gap: 18px; }
    .login-feature { display: flex; gap: 13px; align-items: flex-start; }
    .login-feature .lf-icon {
      width: 38px; height: 38px;
      flex: 0 0 38px;
      border-radius: 11px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      background: rgba(255, 255, 255, 0.1);
      box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.14);
      font-size: 0.9rem;
      color: #fff;
    }
    .login-feature .lf-title { font-size: 0.9rem; font-weight: 600; margin: 0 0 2px; color: #fff; }
    .login-feature .lf-desc { font-size: 0.79rem; color: rgba(255, 255, 255, 0.52); margin: 0; line-height: 1.55; }
    .lbp-copy { position: relative; font-size: 0.76rem; color: rgba(255, 255, 255, 0.42); }
  </style>
</head>
<body class="login-page">

  {{-- ============ Left: brand panel ============ --}}
  <div class="login-brand-panel">
    <span class="lbp-glow lbp-glow-1"></span>
    <span class="lbp-glow lbp-glow-2"></span>

    <div class="lbp-brand">
      <span class="login-icon-tile"><i class="fas fa-suitcase-rolling"></i></span>
      <span class="lbp-name">Shahriar Worldwide Venture</span>
    </div>

    <div>
      <h1 class="lbp-headline">Run your passport operation with confidence</h1>
      <p class="lbp-tagline">One secure place to manage agents, applications, processing stages, deliveries and reports.</p>
    </div>

    <div class="login-features">
      <div class="login-feature">
        <span class="lf-icon"><i class="fas fa-passport"></i></span>
        <div>
          <p class="lf-title">Track every passport</p>
          <p class="lf-desc">Follow each application from agent handover to final delivery.</p>
        </div>
      </div>
      <div class="login-feature">
        <span class="lf-icon"><i class="fas fa-gears"></i></span>
        <div>
          <p class="lf-title">Control processing</p>
          <p class="lf-desc">Monitor pending, ongoing and completed files in real time.</p>
        </div>
      </div>
      <div class="login-feature">
        <span class="lf-icon"><i class="fas fa-user-shield"></i></span>
        <div>
          <p class="lf-title">Coordinate the team</p>
          <p class="lf-desc">Keep agents, officers and employees aligned on every file.</p>
        </div>
      </div>
      <div class="login-feature">
        <span class="lf-icon"><i class="fas fa-chart-pie"></i></span>
        <div>
          <p class="lf-title">See the full picture</p>
          <p class="lf-desc">Lifecycle reports on processing, collection and delivery.</p>
        </div>
      </div>
    </div>

    <p class="lbp-copy">&copy; {{ date('Y') }} Shahriar Worldwide Venture. Secure admin access.</p>
  </div>

  {{-- ============ Right: form ============ --}}
  <div class="login-form-side">
    <div style="width:100%;max-width:420px;">
      {{-- Mobile brand (panel is hidden on small screens) — icon tile, no logo image --}}
      <div class="login-mobile-brand d-lg-none">
        <span class="login-icon-tile dark"><i class="fas fa-suitcase-rolling"></i></span>
        <span class="lmb-text">Passport MS</span>
      </div>

      <div class="login-card">
        <span class="login-badge"><i class="fas fa-shield-halved"></i> Admin access only</span>
        <h1 class="login-title">Welcome back</h1>
        <p class="login-sub">Sign in to your admin account</p>

        @if(session('error'))
          <div class="alert alert-danger mt-3 py-2">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}" class="mt-4">
          @csrf

          <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control w-100" id="email" name="email"
                   placeholder="admin@example.com" value="{{ old('email') }}" required autofocus
                   autocomplete="email">
          </div>

          <div class="mb-2">
            <label for="password" class="form-label">Password</label>
            <div class="position-relative">
              <input type="password" class="form-control w-100 pe-5" id="password" name="password"
                     placeholder="Enter your password" required autocomplete="current-password">
              <button type="button" class="pw-toggle" id="pwToggle" aria-label="Show password">
                <i class="far fa-eye" id="pwIcon"></i>
              </button>
            </div>
          </div>

          <div class="d-flex justify-content-end mb-3">
            <a href="#" class="text-decoration-none" style="font-size:.78rem;font-weight:600">Forgot password?</a>
          </div>

          <button type="submit" class="btn btn-primary w-100 d-block">
            <i class="fas fa-arrow-right-to-bracket me-1"></i> Sign in
          </button>
        </form>
      </div>

      <p class="login-footnote">&copy; {{ date('Y') }} Shahriar Worldwide Venture · Secure admin access</p>
    </div>
  </div>

  <script>
    // Password visibility toggle
    document.getElementById('pwToggle').addEventListener('click', function () {
      var input = document.getElementById('password');
      var icon = document.getElementById('pwIcon');
      var show = input.type === 'password';
      input.type = show ? 'text' : 'password';
      icon.classList.toggle('fa-eye', !show);
      icon.classList.toggle('fa-eye-slash', show);
      this.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
    });
  </script>
</body>
</html>
