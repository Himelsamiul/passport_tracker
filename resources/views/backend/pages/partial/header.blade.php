<header class="header">
  <nav class="navbar navbar-expand-lg px-4 py-2 bg-dark shadow">
    
    <a class="navbar-brand fw-bold text-uppercase text-animated" href="">
      <span class="d-none d-brand-partial">Shahriar Worldwide</span>
      <span class="d-none d-sm-inline">Venture</span>
    </a>

    <ul class="ms-auto d-flex align-items-center list-unstyled mb-0">
      <li class="nav-item dropdown ms-auto">
        <a class="nav-link pe-0 text-white" id="userInfo" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <img class="avatar p-1 rounded-circle border border-light" src="{{ asset('sharmin.jpg') }}" alt="Sharmin Akter">
        </a>
        <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated" aria-labelledby="userInfo">
          <div class="dropdown-header text-gray-700">
            <h6 class="text-uppercase font-weight-bold"></h6>
            <small>Web Developer</small>
          </div>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#">Settings</a>
          <a class="dropdown-item" href="#">Activity Log</a>
          <div class="dropdown-divider"></div>
          <!-- Logout link changed to button with id -->
          <a class="dropdown-item text-danger" href="#" id="logout-link">
            <i class="fas fa-sign-out-alt"></i> Logout
          </a>
        </div>
      </li>
    </ul>
  </nav>
</header>

<style>
  .navbar {
    background: #2c3e50;
    border-radius: 10px;
  }

  .navbar-brand {
    font-size: 1.4rem;
    letter-spacing: 1px;
    font-weight: bold;
    animation: colorChange 5s infinite alternate;
  }

  @keyframes colorChange {
    0% {
      color: #f39c12; /* Golden */
    }
    25% {
      color: #e74c3c; /* Red */
    }
    50% {
      color: #2ecc71; /* Green */
    }
    75% {
      color: #3498db; /* Blue */
    }
    100% {
      color: #9b59b6; /* Purple */
    }
  }

  .navbar-brand span {
    text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.4);
  }

  .avatar {
    width: 40px;
    height: 40px;
    object-fit: cover;
  }

  .dropdown-menu {
    border-radius: 8px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
  }

  .dropdown-item:hover {
    background: #34495e;
    color: white;
  }
</style>

<!-- Include SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const logoutLink = document.getElementById('logout-link');
    if(logoutLink) {
      logoutLink.addEventListener('click', function(e) {
        e.preventDefault();

        Swal.fire({
          title: 'Are you sure you want to logout?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes, logout',
          cancelButtonText: 'Cancel',
          reverseButtons: true
        }).then((result) => {
          if (result.isConfirmed) {
            // Redirect to logout route
            window.location.href = "";
          }
        });
      });
    }
  });
</script>
