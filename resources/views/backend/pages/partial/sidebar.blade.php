<div class="sidebar py-3" id="sidebar">
  <button onclick="toggleSidebar()" class="btn btn-outline-light mb-3 w-100 text-white border-0" style="background: transparent;">Toggle Sidebar</button>

  <input type="text" id="sidebarSearch" placeholder="Search..." class="form-control sidebar-search mb-2">

  <ul class="list-unstyled">
    <li class="sidebar-list-item">
      <a class="sidebar-link text-white d-flex align-items-center" href="{{ route('dashboard') }}" role="button" id="dashboardLink" onclick="setActiveLink(this)">
        <i class="fas fa-tachometer-alt me-3"></i><span class="sidebar-link-title">Dashboard</span>
      </a>
    </li>

    <li class="sidebar-list-item">
      <a class="sidebar-link text-white d-flex align-items-center" href="#" onclick="toggleSubmenu('categoryMenu', this); setActiveLink(this)">
        <i class="fas fa-tags me-3"></i><span class="sidebar-link-title">Agent</span>
        <i class="fas fa-chevron-right ms-auto"></i>
      </a>
      <ul id="categoryMenu" class="submenu list-unstyled d-none ps-4">
        <li><a href="{{ route('agents.create') }}" class="sidebar-link text-white" onclick="setActiveSubmenuLink(this)">Create Agent</a></li>
        <li><a href="{{ route('agents.index') }}" class="sidebar-link text-white" onclick="setActiveSubmenuLink(this)">Agent List</a></li>
      </ul>
    </li>

    <!-- Replaced Menu with Products -->
    <li class="sidebar-list-item">
      <a class="sidebar-link text-white d-flex align-items-center" href="#" onclick="toggleSubmenu('productsMenu', this); setActiveLink(this)">
        <i class="fas fa-box me-3"></i><span class="sidebar-link-title">Passport</span>
        <i class="fas fa-chevron-right ms-auto"></i>
      </a>
      <ul id="productsMenu" class="submenu list-unstyled d-none ps-4">
        <li><a href="{{ route('passports.create') }}" class="sidebar-link text-white" onclick="setActiveSubmenuLink(this)">Create Passport</a></li>
        <li><a href="{{ route('passports.index') }}" class="sidebar-link text-white" onclick="setActiveSubmenuLink(this)">Passport List</a></li>
      </ul>
    </li>

    <!-- New "Unidt" Sidebar -->
    <li class="sidebar-list-item">
      <a class="sidebar-link text-white d-flex align-items-center" href="#" onclick="toggleSubmenu('unitMenu', this); setActiveLink(this)">
        <i class="fas fa-warehouse me-3"></i><span class="sidebar-link-title">Employee</span>
        <i class="fas fa-chevron-right ms-auto"></i>
      </a>
      <ul id="unitMenu" class="submenu list-unstyled d-none ps-4">
        <li><a href="" class="sidebar-link text-white" onclick="setActiveSubmenuLink(this)">Create Employee</a></li>
        <li><a href="" class="sidebar-link text-white" onclick="setActiveSubmenuLink(this)">Employee list</a></li>
      </ul>
    </li>


    <li class="sidebar-list-item">
  <a class="sidebar-link text-white d-flex align-items-center" href="#" onclick="toggleSubmenu('sizeMenu', this); setActiveLink(this)">
    <i class="fas fa-tshirt me-3"></i><span class="sidebar-link-title">Agencies</span>
    <i class="fas fa-chevron-right ms-auto"></i>
  </a>
  <ul id="sizeMenu" class="submenu list-unstyled d-none ps-4">
    <li><a href="" class="sidebar-link text-white" onclick="setActiveSubmenuLink(this)">Add Agencies</a></li>
    <li><a href="" class="sidebar-link text-white" onclick="setActiveSubmenuLink(this)">Agencies List</a></li>
  </ul>
</li>


    <li class="sidebar-list-item">
      <a class="sidebar-link text-white d-flex align-items-center" href="#" role="button" id="dashboardLink" onclick="setActiveLink(this)">
        <i class="fas fa-receipt me-3"></i><span class="sidebar-link-title">Orders</span>
      </a>
    </li>


    <li class="sidebar-list-item">
      <a class="sidebar-link text-white d-flex align-items-center" href="#" role="button" id="customerListLink" onclick="setActiveLink(this)">
        <i class="fas fa-users me-3"></i><span class="sidebar-link-title">Customers</span>
      </a>

    </li>
    <li class="sidebar-list-item">
        <a class="sidebar-link text-white d-flex align-items-center" href="#" onclick="setActiveLink(this)">
        <i class="fas fa-hand-holding-usd me-3"></i><span class="sidebar-link-title">Payment Collection</span>
      </a>
    </li>

    <li class="sidebar-list-item">
     <a class="sidebar-link text-white d-flex align-items-center" href="#">
        <i class="fas fa-envelope me-3"></i><span class="sidebar-link-title">Contacts message </span>
      </a>
    </li>


    <li class="sidebar-list-item">
      <a class="sidebar-link text-white d-flex align-items-center" href="#">
        <i class="fas fa-file-alt me-3"></i><span class="sidebar-link-title">Report</span>
      </a>
    </li>

    <li class="sidebar-list-item">
      <a class="sidebar-link text-white d-flex align-items-center" href="#">
        <i class="fas fa-question-circle me-2"></i><span class="sidebar-link-title">Questions</span>
      </a>
    </li>


    <li class="sidebar-list-item">
      <a class="sidebar-link text-white d-flex align-items-center" href="#">
        <i class="fas fa-star me-3"></i><span class="sidebar-link-title">Reviews</span>
      </a>
    </li>

    <li class="sidebar-list-item">
      <a class="sidebar-link text-white d-flex align-items-center" href="#">
        <i class="fas fa-sign-out-alt me-3"></i><span class="sidebar-link-title">Logout</span>
      </a>
    </li>
  </ul>
</div>

<script>
  document.getElementById('sidebarSearch').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    document.querySelectorAll('.sidebar-list-item').forEach(function(item) {
      const text = item.textContent.toLowerCase();
      item.style.display = text.includes(searchTerm) ? 'block' : 'none';
    });
  });

  function toggleSubmenu(id, element) {
    const submenu = document.getElementById(id);
    submenu.classList.toggle('d-none');

    element.querySelector(".fa-chevron-right").classList.toggle("rotate-icon");
  }

  function setActiveLink(link) {
    // Remove active class from all links
    document.querySelectorAll('.sidebar-link').forEach(function(item) {
      item.classList.remove('active');
    });
    // Add active class to the clicked link
    link.classList.add('active');
  }

  function setActiveSubmenuLink(link) {
    // Remove active class from all submenu links
    document.querySelectorAll('.submenu .sidebar-link').forEach(function(item) {
      item.classList.remove('active');
    });
    // Add active class to the clicked submenu link
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

  .sidebar-list-item {
    margin-bottom: 10px;
  }

  .sidebar-link {
    display: flex;
    align-items: center;
    padding: 10px;
    border-radius: 5px;
    transition: background 0.3s;
    color: white;
  }

  .sidebar-link:hover {
    background: #34495e;
    color: white;
  }

  .sidebar-link.active {
    background: #16a085;
    /* Highlight color for active link */
    color: white;
  }

  .submenu {
    padding-left: 20px;
  }

  .fa-chevron-right {
    transition: transform 0.3s;
  }

  .rotate-icon {
    transform: rotate(90deg);
  }
</style>