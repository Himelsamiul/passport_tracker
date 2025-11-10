<div class="sidebar py-3" id="sidebar">
  <button onclick="toggleSidebar()" 
          class="btn btn-outline-light mb-3 w-100 text-white border-0 sidebar-toggle-btn">
    Toggle Sidebar
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
    --deepblue:#02152E;      /* match header; change once to recolor all */
    --deepblue-2:#0a2a52;    /* lighter shade for inputs */
    --hover:#0b203f;
    --active:#12824A;        /* active pill color */
    --ink:#fff;
  }

  .sidebar{
    width:260px;
    background:var(--deepblue);
    color:var(--ink);
    padding:15px;
    border-radius:10px;
    height:100vh;           /* make it full-height */
    position:sticky; top:0; /* stick while page scrolls */
    overflow-y:auto;        /* scroll inside sidebar if long */
    box-shadow:0 0 15px rgba(0,0,0,.22);
  }
  .sidebar.collapsed{ width:72px; }
  .sidebar.collapsed .sidebar-link-title,
  .sidebar.collapsed .submenu{ display:none !important; }
  .sidebar.collapsed .fa-chevron-right{ display:none; }

  .sidebar-toggle-btn{
    background:linear-gradient(90deg,var(--deepblue-2),var(--deepblue));
    border:1px solid rgba(255,255,255,.15);
    border-radius:8px;
    transition:.2s;
  }
  .sidebar-toggle-btn:hover{ background:var(--hover); }

  .sidebar-list-item{ margin-bottom:10px; }

  .sidebar-link{
    color:var(--ink);
    display:flex; align-items:center;
    padding:10px; border-radius:8px;
    text-decoration:none;
    transition:background .2s,color .2s;
  }
  .sidebar-link:hover{ background:var(--hover); }
  .sidebar-link.active{ background:var(--active); }

  .submenu{ padding-left:20px; }
  .fa-chevron-right{ transition:transform .25s; }
  .rotate-icon{ transform:rotate(90deg); }

  .sidebar-search{
    background:var(--deepblue-2);
    color:#fff;
    border:1px solid rgba(255,255,255,.2);
    border-radius:8px;
  }
  .sidebar-search::placeholder{ color:#c9d1d9; }
</style>
