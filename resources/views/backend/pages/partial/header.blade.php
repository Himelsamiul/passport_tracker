<header class="header">
  <nav class="navbar navbar-expand-lg shadow-sm py-2" style="background:var(--deepblue);">
    <div class="container-fluid nav-grid px-3">

      {{-- LEFT: Clock --}}
      <div class="nav-left d-flex align-items-center">
        <div id="clock" class="text-gold fw-semibold fs-5"></div>
      </div>

      {{-- CENTER: Brand --}}
      <a class="navbar-brand nav-center d-flex align-items-center gap-2 fw-bold text-uppercase text-gold"
         href="{{ url('/') }}">
        <img src="{{ asset('logo.jpg') }}" alt="Logo"
             class="rounded-circle border border-2 border-light" width="42" height="42">
        <span class="brand-title">Passport Management System</span>
      </a>

      {{-- RIGHT: User --}}
      <div class="nav-right d-flex align-items-center">
        <ul class="navbar-nav align-items-center">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle user-toggle d-flex align-items-center gap-2"
               href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <div class="icon-circle"><i class="fas fa-user-tie"></i></div>
              <span class="text-white fw-semibold text-capitalize user-label">
                {{ session('user')->name ?? Auth::user()->name ?? 'Guest User' }}
              </span>
            </a>

            <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 p-2"
                 aria-labelledby="userDropdown">
              <div class="px-2 py-1 text-center">
                <div class="fw-semibold text-capitalize text-dark">
                  {{ session('user')->name ?? Auth::user()->name ?? 'Guest User' }}
                </div>
                <small class="text-secondary">Shahriar Worldwide Venture</small>
              </div>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item d-flex align-items-center gap-2" href="#">
                <i class="fas fa-cog text-muted"></i><span>Settings</span>
              </a>
              <a class="dropdown-item d-flex align-items-center gap-2" href="#">
                <i class="fas fa-list text-muted"></i><span>Activity Log</span>
              </a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item d-flex align-items-center gap-2 text-danger"
                 href="{{ route('logout') }}"
                 onclick="return confirm('Are you sure you want to logout?');">
                <i class="fas fa-sign-out-alt"></i><span>Logout</span>
              </a>
            </div>
          </li>
        </ul>
      </div>

    </div>
  </nav>
</header>

<style>
  :root{ --deepblue:#02152E; --gold:#FFD700; }

  /* Grid locks positions: [left clock] [center brand] [right user] */
  .nav-grid{
    display:grid;
    grid-template-columns: 1fr auto 1fr;
    align-items:center;
    gap:12px;
  }
  .nav-left  { justify-self:start;
             margin-left: 30px;    }
  .nav-center{ justify-self:center; }
  .nav-right { justify-self:end;
              margin-right: 40px;  }

  /* Gold gradient text */
  .text-gold{
    background:linear-gradient(90deg,#FFD700,#E6BE8A,#B8860B);
    -webkit-background-clip:text; -webkit-text-fill-color:transparent;
  }
  .brand-title{ font-size:clamp(20px,2.3vw,26px); letter-spacing:.5px; }

  /* Clock */
  #clock{ letter-spacing:1px; text-shadow:0 0 8px rgba(255,215,0,.5); font-weight:700; }

  /* User dropdown styles (same as before) */
  .user-toggle{ color:#fff !important; border-radius:12px; padding:6px 10px; transition:background .25s, box-shadow .25s; }
  .user-toggle:hover{ background:rgba(255,255,255,.07); box-shadow:0 2px 8px rgba(0,0,0,.2); }
  .user-toggle::after{ display:none; }
  .icon-circle{
    background:linear-gradient(145deg,var(--gold),#e0b84e);
    color:#0d1117; width:42px; height:42px; border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    font-size:20px; border:2px solid #fff; box-shadow:0 0 8px rgba(255,215,0,.4);
    transition:transform .25s, box-shadow .25s;
  }
  .icon-circle:hover{ transform:scale(1.08); box-shadow:0 0 15px rgba(255,215,0,.6); }
  .user-label{ font-size:15px; letter-spacing:.3px; }
  .dropdown-menu{ min-width:240px; }
  .dropdown-item{ border-radius:8px; padding:.5rem .7rem; font-weight:500; }
  .dropdown-item:hover{ background:#f8f9fa; }
  .dropdown-menu.show::before{
    content:""; position:absolute; top:-8px; right:25px; width:14px; height:14px;
    background:#fff; transform:rotate(45deg); box-shadow:-2px -2px 6px rgba(0,0,0,.05);
  }

  /* Mobile tweak: keep positions but allow wrapping cleanly */
  @media (max-width: 576px){
    .nav-grid{ grid-template-columns: 1fr auto auto; }
    .nav-center{ justify-self:center; }
    .brand-title{ font-size:18px; }
    #clock{ font-size:14px; }
  }
</style>

<script>
  function updateClock(){
    const now=new Date();
    let h=now.getHours(), m=now.getMinutes(), s=now.getSeconds();
    const ampm=h>=12?'PM':'AM'; h=h%12||12;
    document.getElementById('clock').textContent =
      `${String(h).padStart(2,'0')}:${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')} ${ampm}`;
  }
  setInterval(updateClock,1000); updateClock();
</script>
