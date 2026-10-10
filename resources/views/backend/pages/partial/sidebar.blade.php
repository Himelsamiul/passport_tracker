<div class="sidebar py-3" id="sidebar">
  <button onclick="toggleSidebar()" title="Toggle sidebar"
          class="btn btn-outline-light mb-3 w-100 text-white border-0 sidebar-toggle-btn">
    <i class="fas fa-bars me-2"></i><span class="btn-text">Collapse Menu</span>
  </button>

  <input type="text" id="sidebarSearch" placeholder="Search..." class="form-control sidebar-search mb-3">

  <ul class="list-unstyled">

    {{-- Dashboard --}}
    <li class="sidebar-list-item">
      <a class="sidebar-link d-flex align-items-center" 
         href="{{ route('dashboard') }}" onclick="setActiveLink(this)">
        <i class="fas fa-tachometer-alt me-3"></i><span class="sidebar-link-title">Dashboard</span>
      </a>
    </li>

    {{-- Agent --}}
    <li class="sidebar-list-item">
      <a class="sidebar-link d-flex align-items-center" 
         onclick="toggleSubmenu('agentMenu', this); setActiveLink(this)">
        <i class="fas fa-user-tie me-3"></i><span class="sidebar-link-title">Agent</span>
        <i class="fas fa-chevron-right ms-auto"></i>
      </a>
      <ul id="agentMenu" class="submenu list-unstyled ps-4 {{ request()->routeIs('agents.*') ? '' : 'd-none' }}">
        <li><a href="{{ route('agents.create') }}" class="sidebar-link" onclick="setActiveSubmenuLink(this)">Create Agent</a></li>
        <li><a href="{{ route('agents.index') }}" class="sidebar-link" onclick="setActiveSubmenuLink(this)">Agent List</a></li>
      </ul>
    </li>

    {{-- Passport --}}
    <li class="sidebar-list-item">
      <a class="sidebar-link d-flex align-items-center" 
         onclick="toggleSubmenu('passportMenu', this); setActiveLink(this)">
        <i class="fas fa-passport me-3"></i><span class="sidebar-link-title">Passport</span>
        <i class="fas fa-chevron-right ms-auto"></i>
      </a>
      <ul id="passportMenu" class="submenu list-unstyled ps-4 {{ request()->routeIs('passports.*') ? '' : 'd-none' }}">
        <li><a href="{{ route('passports.create') }}" class="sidebar-link" onclick="setActiveSubmenuLink(this)">Create Passport</a></li>
        <li><a href="{{ route('passports.index') }}" class="sidebar-link" onclick="setActiveSubmenuLink(this)">Passport List</a></li>
      </ul>
    </li>

    {{-- Employee --}}
    <li class="sidebar-list-item">
      <a class="sidebar-link d-flex align-items-center" 
         onclick="toggleSubmenu('employeeMenu', this); setActiveLink(this)">
        <i class="fas fa-users me-3"></i><span class="sidebar-link-title">Employee</span>
        <i class="fas fa-chevron-right ms-auto"></i>
      </a>
      <ul id="employeeMenu" class="submenu list-unstyled ps-4 {{ request()->routeIs('employees.*') ? '' : 'd-none' }}">
        <li><a href="{{ route('employees.create') }}" class="sidebar-link" onclick="setActiveSubmenuLink(this)">Create Employee</a></li>
        <li><a href="{{ route('employees.index') }}" class="sidebar-link" onclick="setActiveSubmenuLink(this)">Employee List</a></li>
      </ul>
    </li>

    {{-- Working Category --}}
    <li class="sidebar-list-item">
      <a class="sidebar-link d-flex align-items-center" 
         onclick="toggleSubmenu('categoryMenu', this); setActiveLink(this)">
        <i class="fas fa-layer-group me-3"></i><span class="sidebar-link-title">Working Category</span>
        <i class="fas fa-chevron-right ms-auto"></i>
      </a>
      <ul id="categoryMenu" class="submenu list-unstyled ps-4 {{ request()->routeIs('categories.*') ? '' : 'd-none' }}">
        <li><a href="{{ route('categories.create') }}" class="sidebar-link" onclick="setActiveSubmenuLink(this)">Add Category</a></li>
        <li><a href="{{ route('categories.index') }}" class="sidebar-link" onclick="setActiveSubmenuLink(this)">Category List</a></li>
      </ul>
    </li>

    {{-- Agencies --}}
    <li class="sidebar-list-item">
      <a class="sidebar-link d-flex align-items-center" 
         onclick="toggleSubmenu('agenciesMenu', this); setActiveLink(this)">
        <i class="fas fa-building me-3"></i><span class="sidebar-link-title">Agencies</span>
        <i class="fas fa-chevron-right ms-auto"></i>
      </a>
      <ul id="agenciesMenu" class="submenu list-unstyled ps-4 {{ request()->routeIs('agencies.*') ? '' : 'd-none' }}">
        <li><a href="{{ route('agencies.create') }}" class="sidebar-link" onclick="setActiveSubmenuLink(this)">Add Agency</a></li>
        <li><a href="{{ route('agencies.index') }}" class="sidebar-link" onclick="setActiveSubmenuLink(this)">Agencies List</a></li>
      </ul>
    </li>

    {{-- Passport Officer --}}
    <li class="sidebar-list-item">
      <a class="sidebar-link d-flex align-items-center" 
         onclick="toggleSubmenu('officerMenu', this); setActiveLink(this)">
        <i class="fas fa-user-shield me-3"></i><span class="sidebar-link-title">Passport Officer</span>
        <i class="fas fa-chevron-right ms-auto"></i>
      </a>
      <ul id="officerMenu" class="submenu list-unstyled ps-4 {{ request()->routeIs('officers.*') ? '' : 'd-none' }}">
        <li><a href="{{ route('officers.create') }}" class="sidebar-link" onclick="setActiveSubmenuLink(this)">Add Officer</a></li>
        <li><a href="{{ route('officers.index') }}" class="sidebar-link" onclick="setActiveSubmenuLink(this)">Officer List</a></li>
      </ul>
    </li>
{{-- Processing --}}
<li class="sidebar-list-item">
  <a class="sidebar-link d-flex align-items-center"
     onclick="toggleSubmenu('processingMenu', this); setActiveLink(this)">
    <i class="fas fa-cogs me-3"></i>
    <span class="sidebar-link-title">Processing</span>
    <i class="fas fa-chevron-right ms-auto"></i>
  </a>
  <ul id="processingMenu"
      class="submenu list-unstyled ps-4 {{ request()->routeIs('processings.*') ? '' : 'd-none' }}">
    <li>
      <a href="{{ route('processings.create') }}" class="sidebar-link" onclick="setActiveSubmenuLink(this)">
        Create Process
      </a>
    </li>
    <li>
      <a href="{{ route('processings.index') }}" class="sidebar-link" onclick="setActiveSubmenuLink(this)">
        Process List
      </a>
    </li>
  </ul>
</li>

    {{-- Registration --}}
    <li class="sidebar-list-item">
      <a class="sidebar-link d-flex align-items-center" 
         onclick="toggleSubmenu('registrationMenu', this); setActiveLink(this)">
        <i class="fas fa-user-plus me-3"></i><span class="sidebar-link-title">Registration</span>
        <i class="fas fa-chevron-right ms-auto"></i>
      </a>
      <ul id="registrationMenu" class="submenu list-unstyled ps-4 {{ request()->routeIs('register.*') ? '' : 'd-none' }}">
        <li><a href="{{ route('register.form') }}" class="sidebar-link" onclick="setActiveSubmenuLink(this)">Create</a></li>
        <li><a href="{{ route('register.list') }}" class="sidebar-link" onclick="setActiveSubmenuLink(this)">List</a></li>
      </ul>
    </li>

    {{-- Passport Collections --}}
    <li class="sidebar-list-item">
      <a class="sidebar-link d-flex align-items-center" 
         onclick="toggleSubmenu('collectionsMenu', this); setActiveLink(this)">
        <i class="fas fa-briefcase me-3"></i><span class="sidebar-link-title">Passport Collections</span>
        <i class="fas fa-chevron-right ms-auto"></i>
      </a>
      <ul id="collectionsMenu" class="submenu list-unstyled ps-4 {{ request()->routeIs('collections.*') ? '' : 'd-none' }}">
        <li><a href="{{ route('collections.create') }}" class="sidebar-link" onclick="setActiveSubmenuLink(this)">Create</a></li>
        <li><a href="{{ route('collections.index') }}" class="sidebar-link" onclick="setActiveSubmenuLink(this)">List</a></li>
      </ul>
    </li>

    {{-- Reports --}}
    <li class="sidebar-list-item">
      <a class="sidebar-link d-flex align-items-center" 
         onclick="toggleSubmenu('reportsMenu', this); setActiveLink(this)">
        <i class="fas fa-chart-bar me-3"></i><span class="sidebar-link-title">Reports</span>
        <i class="fas fa-chevron-right ms-auto"></i>
      </a>
      <ul id="reportsMenu" class="submenu list-unstyled ps-4 {{ request()->routeIs('reports.*') ? '' : 'd-none' }}">
        <li><a href="{{ route('reports.processing') }}" class="sidebar-link" onclick="setActiveSubmenuLink(this)"><i class="fas fa-tasks me-2"></i>Processing Report</a></li>
        <li><a href="{{ route('reports.collection') }}" class="sidebar-link" onclick="setActiveSubmenuLink(this)"><i class="fas fa-box-open me-2"></i>Collection Report</a></li>
        <li><a href="{{ route('reports.summary') }}" class="sidebar-link" onclick="setActiveSubmenuLink(this)"><i class="fas fa-chart-pie me-2"></i>Summary Report</a></li>
      </ul>
    </li>

    {{-- Logout --}}
    <li class="sidebar-list-item">
      <a class="sidebar-link d-flex align-items-center" href="{{ route('logout') }}">
        <i class="fas fa-sign-out-alt me-3"></i><span class="sidebar-link-title">Logout</span>
      </a>
    </li>
  </ul>
</div>

<script>
  // Avoid JS errors if button exists but function was missing
  function toggleSidebar(){
    const el = document.getElementById('sidebar');
    el.classList.toggle('collapsed');
  }

  // Sidebar search (safe)
  (function(){
    const search = document.getElementById('sidebarSearch');
    if(!search) return;
    search.addEventListener('input', function(){
      const term = (this.value || '').toLowerCase().trim();
      document.querySelectorAll('#sidebar .sidebar-list-item').forEach(item => {
        const text = item.textContent.toLowerCase();
        item.style.display = term ? (text.includes(term) ? 'block' : 'none') : 'block';
      });
    });
  })();

  function toggleSubmenu(id, trigger){
    const submenu = document.getElementById(id);
    if(!submenu) return;
    submenu.classList.toggle('d-none');
    const chevron = trigger.querySelector('.fa-chevron-right');
    if(chevron) chevron.classList.toggle('rotate-icon');
  }

  function setActiveLink(link){
    document.querySelectorAll('#sidebar .sidebar-link').forEach(a => a.classList.remove('active'));
    link.classList.add('active');
  }
  function setActiveSubmenuLink(link){
    document.querySelectorAll('#sidebar .submenu .sidebar-link').forEach(a => a.classList.remove('active'));
    link.classList.add('active');
  }
</script>

<style>
  :root{
    --deepblue:#02152E;
    --deepblue-2:#0a2a52;
    --hover:rgba(255,255,255,.07);
    --active-bg:linear-gradient(90deg,rgba(255,215,0,.16),rgba(255,215,0,.05));
    --ink:#fff;
  }

  .sidebar{
    width:264px;
    background:linear-gradient(180deg,#04203f 0%, var(--deepblue) 55%, #010b1c 100%);
    color:var(--ink);
    padding:16px 12px;
    border-radius:14px;
    margin:10px 0 10px 10px;
    height:calc(100vh - 62px);
    position:sticky; top:62px;
    overflow-y:auto;
    box-shadow:12px 0 32px -18px rgba(2,21,46,.55);
    transition:width .28s ease, margin .28s ease;
    scrollbar-width:thin;
    scrollbar-color:rgba(255,255,255,.25) transparent;
  }
  .sidebar::-webkit-scrollbar{ width:6px; }
  .sidebar::-webkit-scrollbar-thumb{ background:rgba(255,255,255,.22); border-radius:6px; border:none; }

  .sidebar.collapsed{ width:76px; margin-left:6px; }
  .sidebar.collapsed .sidebar-link-title,
  .sidebar.collapsed .submenu{ display:none !important; }
  .sidebar.collapsed .fa-chevron-right{ display:none; }
  .sidebar.collapsed .sidebar-link{ justify-content:center; padding:11px 0; }
  .sidebar.collapsed .sidebar-search,
  .sidebar.collapsed .sidebar-toggle-btn .btn-text{ display:none; }
  .sidebar.collapsed .sidebar-toggle-btn i{ margin:0 !important; }

  /* Top: collapse toggle + search */
  .sidebar-toggle-btn{
    background:rgba(255,255,255,.06);
    border:1px solid rgba(255,255,255,.14);
    border-radius:10px;
    font-size:.82rem;
    padding:.45rem .6rem;
    transition:.2s;
  }
  .sidebar-toggle-btn:hover{ background:rgba(255,255,255,.12); }

  .sidebar-search{
    background:rgba(255,255,255,.07);
    color:#fff;
    border:1px solid rgba(255,255,255,.16);
    border-radius:10px;
    font-size:.85rem;
    padding:.5rem .75rem;
  }
  .sidebar-search:focus{
    background:rgba(255,255,255,.1);
    border-color:rgba(255,215,0,.55);
    box-shadow:0 0 0 .2rem rgba(255,215,0,.12);
    color:#fff;
  }
  .sidebar-search::placeholder{ color:rgba(255,255,255,.55); }

  /* Menu */
  .sidebar-list-item{ margin-bottom:4px; }

  .sidebar-link{
    position:relative;
    color:rgba(255,255,255,.88);
    display:flex; align-items:center;
    padding:10px 12px;
    border-radius:10px;
    font-size:.9rem;
    font-weight:500;
    text-decoration:none;
    cursor:pointer;
    transition:background .2s ease, color .2s ease, transform .15s ease;
  }
  .sidebar-link i.fas:first-child{
    width:34px; height:34px;
    display:inline-flex; align-items:center; justify-content:center;
    border-radius:9px;
    background:rgba(255,255,255,.07);
    font-size:.85rem;
    transition:background .2s ease, color .2s ease;
    flex:0 0 34px;
  }
  .sidebar-link:hover{ background:var(--hover); color:#fff; transform:translateX(2px); }
  .sidebar-link:hover i.fas:first-child{ background:rgba(255,215,0,.18); color:var(--gold); }

  .sidebar-link.active{
    background:var(--active-bg);
    color:var(--gold);
    font-weight:600;
  }
  .sidebar-link.active i.fas:first-child{ background:rgba(255,215,0,.2); color:var(--gold); }
  .sidebar-link.active::before{
    content:"";
    position:absolute; left:-12px; top:20%;
    height:60%; width:3px;
    border-radius:0 4px 4px 0;
    background:linear-gradient(180deg,var(--gold),var(--gold-soft));
  }

  /* Submenu */
  .submenu{ padding-left:14px; margin-top:2px; position:relative; }
  .submenu::before{
    content:"";
    position:absolute; left:28px; top:2px; bottom:6px;
    width:1.5px;
    background:rgba(255,255,255,.12);
    border-radius:2px;
  }
  .submenu .sidebar-link{
    padding:7px 10px;
    font-size:.82rem;
    color:rgba(255,255,255,.72);
  }
  .submenu .sidebar-link:hover{ color:#fff; }
  .submenu .sidebar-link.active{ color:var(--gold); }

  .fa-chevron-right{ transition:transform .25s; font-size:.7rem; opacity:.6; }
  .rotate-icon{ transform:rotate(90deg); }
</style>
