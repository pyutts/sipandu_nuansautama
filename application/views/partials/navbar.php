<header class="app-header">
  <nav class="navbar navbar-expand-lg navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item d-block d-xl-none">
        <a class="nav-link sidebartoggler nav-icon-hover" href="#" id="headerCollapse">
          <i class="ti ti-menu-2"></i>
        </a>
      </li>
    </ul>

    <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
      <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
        <li class="nav-item dropdown">
          <a class="nav-link nav-icon-hover" id="notifDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="position:relative;">
            <i class="ti ti-bell-ringing position-relative" style="font-size: 1.5rem;">
              <span class="position-absolute top-0 start-100 translate-middle p-0 bg-danger border border-light rounded-circle d-none" id="notifDot" style="width:15px;height:15px;display:flex;align-items:center;justify-content:center;">
                <span class="visually-hidden"></span>
              </span>
            </i>
          </a>

          <ul class="dropdown-menu dropdown-menu-end shadow" style="width: 340px; border-radius: 12px;" id="notifList"
                aria-labelledby="notifDropdown" id="notifList" style="border-radius: 12px;">
            </ul>
        </li>

        <li class="nav-item ms-3">
          <form id="logoutForm" action="<?= base_url('auth/logout') ?>" method="post" class="d-inline">
            <button type="button" id="logoutButton" class="btn btn-outline-danger d-flex align-items-center">
              <i class="fas fa-sign-out-alt me-1"></i> Logout
            </button>
          </form>
        </li>
      </ul>
    </div>
  </nav>
</header>
