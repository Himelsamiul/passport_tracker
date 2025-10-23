<div class="sidebar py-3" id="sidebar">
  <button onclick="toggleSidebar()" class="btn btn-outline-light mb-3 w-100 text-white border-0" style="background: transparent;">Toggle Sidebar</button>

  <input type="text" id="sidebarSearch" placeholder="Search..." class="form-control sidebar-search mb-2">

  <ul class="list-unstyled">
    <!-- Dashboard -->
    <li class="sidebar-list-item">
      <a class="sidebar-link text-white d-flex align-items-center" href="{{ route('dashboard') }}" role="button" id="dashboardLink" onclick="setActiveLink(this)">
        <i class="fas fa-tachometer-alt me-3"></i><span class="sidebar-link-title">Dashboard</span>
      </a>
    </li>

    <!-- Agent -->
    <li class="sidebar-list-item">
      <a class="sidebar-link text-white d-flex align-items-center" href="#"
         onclick="toggleSubmenu('agentMenu', this); setActiveLink(this)">
        <i class="fas fa-user-tie me-3"></i><span class="sidebar-link-title">Agent</span>
        <i class="fas fa-chevron-right ms-auto"></i>
      </a>
      <ul id="agentMenu" class="submenu list-unstyled d-none ps-4">
        <li><a href="{{ route('agents.create') }}" class="sidebar-link text-white" onclick="setActiveSubmenuLink(this)">Create Agent</a></li>
        <li><a href="{{ route('agents.index') }}" class="sidebar-link text-white" onclick="setActiveSubmenuLink(this)">Agent List</a></li>
      </ul>
    </li>

    <!-- Passport -->
    <li class="sidebar-list-item">
      <a class="sidebar-link text-white d-flex align-items-center" href="#"
         onclick="toggleSubmenu('passportMenu', this); setActiveLink(this)">
        <i class="fas fa-passport me-3"></i><span class="sidebar-link-title">Passport</span>
        <i class="fas fa-chevron-right ms-auto"></i>
      </a>
      <ul id="passportMenu" class="submenu list-unstyled d-none ps-4">
        <li><a href="{{ route('passports.create') }}" class="sidebar-link text-white" onclick="setActiveSubmenuLink(this)">Create Passport</a></li>
        <li><a href="{{ route('passports.index') }}" class="sidebar-link text-white" onclick="setActiveSubmenuLink(this)">Passport List</a></li>
      </ul>
    </li>

    <!-- Employee -->
    <li class="sidebar-list-item">
      <a class="sidebar-link text-white d-flex align-items-center" href="#"
         onclick="toggleSubmenu('employeeMenu', this); setActiveLink(this)">
        <i class="fas fa-users me-3"></i><span class="sidebar-link-title">Employee</span>
        <i class="fas fa-chevron-right ms-auto"></i>
      </a>
      <ul id="employeeMenu" class="submenu list-unstyled d-none ps-4">
        <li><a href="{{ route('employees.create') }}" class="sidebar-link text-white" onclick="setActiveSubmenuLink(this)">Create Employee</a></li>
        <li><a href="{{ route('employees.index') }}" class="sidebar-link text-white" onclick="setActiveSubmenuLink(this)">Employee List</a></li>
      </ul>
    </li>

    <!-- Working Category -->
    <li class="sidebar-list-item">
      <a class="sidebar-link text-white d-flex align-items-center" href="#"
         onclick="toggleSubmenu('categoryMenu', this); setActiveLink(this)">
        <i class="fas fa-layer-group me-3"></i><span class="sidebar-link-title">Working Category</span>
        <i class="fas fa-chevron-right ms-auto"></i>
      </a>
      <ul id="categoryMenu" class="submenu list-unstyled d-none ps-4">
        <li><a href="{{ route('categories.create') }}" class="sidebar-link text-white" onclick="setActiveSubmenuLink(this)">Add Category</a></li>
        <li><a href="{{ route('categories.index') }}" class="sidebar-link text-white" onclick="setActiveSubmenuLink(this)">Category List</a></li>
      </ul>
    </li>

    <!-- Agencies -->
    <li class="sidebar-list-item">
      <a class="sidebar-link text-white d-flex align-items-center" href="#"
         onclick="toggleSubmenu('agenciesMenu', this); setActiveLink(this)">
        <i class="fas fa-building me-3"></i><span class="sidebar-link-title">Agencies</span>
        <i class="fas fa-chevron-right ms-auto"></i>
      </a>
      <ul id="agenciesMenu" class="submenu list-unstyled d-none ps-4">
        <li><a href="{{ route('agencies.create') }}" class="sidebar-link text-white" onclick="setActiveSubmenuLink(this)">Add Agency</a></li>
        <li><a href="{{ route('agencies.index') }}" class="sidebar-link text-white" onclick="setActiveSubmenuLink(this)">Agencies List</a></li>
      </ul>
    </li>

    <!-- Processing -->
    <li class="sidebar-list-item">
      <a class="sidebar-link text-white d-flex align-items-center" href="#"
         onclick="toggleSubmenu('processingMenu', this); setActiveLink(this)">
        <i class="fas fa-cogs me-3"></i><span class="sidebar-link-title">Processing</span>
        <i class="fas fa-chevron-right ms-auto"></i>
      </a>
      <ul id="processingMenu" class="submenu list-unstyled d-none ps-4">
        <li><a href="{{ route('processings.create') }}" class="sidebar-link text-white" onclick="setActiveSubmenuLink(this)">Create Process</a></li>
        <li><a href="{{ route('processings.index') }}" class="sidebar-link text-white" onclick="setActiveSubmenuLink(this)">Process List</a></li>
      </ul>
    </li>

    <!-- Passport Officer -->
    <li class="sidebar-list-item">
      <a class="sidebar-link text-white d-flex align-items-center" href="#"
         onclick="toggleSubmenu('officerMenu', this); setActiveLink(this)">
        <i class="fas fa-user-shield me-3"></i><span class="sidebar-link-title">Passport Officer</span>
        <i class="fas fa-chevron-right ms-auto"></i>
      </a>
      <ul id="officerMenu" class="submenu list-unstyled d-none ps-4">
        <li><a href="{{ route('officers.create') }}" class="sidebar-link text-white" onclick="setActiveSubmenuLink(this)">Add Officer</a></li>
        <li><a href="{{ route('officers.index') }}" class="sidebar-link text-white" onclick="setActiveSubmenuLink(this)">Officer List</a></li>
      </ul>
    </li>

    <!-- ✅ Passport Collections (unique id!) -->
    <li class="sidebar-list-item">
      <a class="sidebar-link text-white d-flex align-items-center" href="#"
         onclick="toggleSubmenu('collectionsMenu', this); setActiveLink(this)">
        <i class="fas fa-briefcase me-3"></i><span class="sidebar-link-title">Passport Collections</span>
        <i class="fas fa-chevron-right ms-auto"></i>
      </a>
      <ul id="collectionsMenu" class="submenu list-unstyled d-none ps-4">
        <li><a href="{{ route('collections.create') }}" class="sidebar-link text-white" onclick="setActiveSubmenuLink(this)">Create</a></li>
        <li><a href="{{ route('collections.index') }}" class="sidebar-link text-white" onclick="setActiveSubmenuLink(this)">List</a></li>
      </ul>
    </li>

    <!-- Report -->
    <li class="sidebar-list-item">
      <a class="sidebar-link text-white d-flex align-items-center" href="#">
        <i class="fas fa-chart-bar me-3"></i><span class="sidebar-link-title">Report</span>
      </a>
    </li>

    <!-- Logout -->
    <li class="sidebar-list-item">
      <a class="sidebar-link text-white d-flex align-items-center" href="{{ route('logout') }}">
        <i class="fas fa-sign-out-alt me-3"></i><span class="sidebar-link-title">Logout</span>
      </a>
    </li>
  </ul>
</div>

<script>
  // Sidebar search
  document.getElementById('sidebarSearch').addEventListener('input', function() {
    const term = this.value.toLowerCase();
    document.querySelectorAll('.sidebar-list-item').forEach(function(item) {
      const text = item.textContent.toLowerCase();
      item.style.display = text.includes(term) ? 'block' : 'none';
    });
  });

  // ✅ Unified toggleSubmenu (prevents jump for all)
  function toggleSubmenu(eventOrId, maybeElement, maybeLink) {
    let event = null, id, element;

    // handle both call styles
    if (typeof eventOrId === 'string') {
      id = eventOrId;
      element = maybeElement;
    } else {
      event = eventOrId;
      id = maybeElement;
      element = maybeLink;
    }

    // prevent the '#' anchor jump
    if (event) event.preventDefault();

    const submenu = document.getElementById(id);
    if (!submenu) return;

    submenu.classList.toggle('d-none');

    const chevron = element.querySelector('.fa-chevron-right');
    if (chevron) chevron.classList.toggle('rotate-icon');
  }

  function setActiveLink(link) {
    document.querySelectorAll('.sidebar-link').forEach(item => item.classList.remove('active'));
    link.classList.add('active');
  }

  function setActiveSubmenuLink(link) {
    document.querySelectorAll('.submenu .sidebar-link').forEach(item => item.classList.remove('active'));
    link.classList.add('active');
  }
</script>


<style>
  .sidebar {
    width: 250px;
    background: #2c3e50;
    color: white;
    padding: 15px;
    border-radius: 10px;
  }
  .sidebar-list-item { margin-bottom: 10px; }

  .sidebar-link {
    display: flex;
    align-items: center;
    padding: 10px;
    border-radius: 5px;
    transition: background 0.3s;
    color: white;
    text-decoration: none;
  }
  .sidebar-link:hover { background: #34495e; color: white; }
  .sidebar-link.active { background: #16a085; color: white; }

  .submenu { padding-left: 20px; }
  .fa-chevron-right { transition: transform 0.3s; }
  .rotate-icon { transform: rotate(90deg); }
</style>
