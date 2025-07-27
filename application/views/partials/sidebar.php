<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"data-sidebar-position="fixed" data-header-position="fixed">
    <aside class="left-sidebar">
        <div>
            <div class="brand-logo d-flex align-items-center justify-content-between">
                <a class="text-nowrap logo-img"><br>
                    <img src="<?= base_url('assets/images/logos/icon_full.png'); ?>" width="200" alt="">
                </a>
                <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                    <i class="ti ti-x fs-8"></i>
                </div>
            </div>
            <?php $role = $this->session->userdata('role'); ?>
            <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
                <ul id="sidebarnav">

                    <?php if (in_array($role, ['Admin', 'Kepala Lingkungan'])): ?>
                    <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                        <span class="hide-menu">Home</span>
                    </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="<?= site_url('dashboard'); ?>">
                                <i class="ti ti-layout-dashboard"></i>
                                <span class="hide-menu">Dashboard</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                        <span class="hide-menu">Master Data</span>
                    </li>
                    <?php if ($role == 'Admin'): ?>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="<?= site_url('dashboard/admin/view'); ?>">
                                <i class="ti ti-user-shield"></i>
                                <span class="hide-menu">Data Admin</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="<?= site_url('dashboard/wilayah/view'); ?>">
                                <i class="ti ti-map"></i>
                                <span class="hide-menu">Data Wilayah</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="<?= site_url('dashboard/kaling/view'); ?>">
                                <i class="ti ti-users"></i>
                                <span class="hide-menu">Data Kepala Lingkungan</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (in_array($role, ['Admin', 'Kepala Lingkungan'])): ?>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="<?= site_url('dashboard/pj/view'); ?>">
                                <i class="ti ti-users-group"></i>
                                <span class="hide-menu">Data Penanggung Jawab</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="<?= site_url('dashboard/penghuni/view'); ?>">
                                <i class="ti ti-users-group"></i>
                                <span class="hide-menu">Data Pendatang</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (in_array($role, ['Penanggung Jawab'])): ?>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="<?= site_url('dashboard/pj/editdata'); ?>">
                                <i class="ti ti-user"></i>
                                <span class="hide-menu">My Profil</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="<?= site_url('dashboard/penghuni/viewpj'); ?>">
                                <i class="ti ti-users-group"></i>
                                <span class="hide-menu">Data Pendatang</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                        <span class="hide-menu">Dokumen</span>
                    </li>

                    <?php if (in_array($role, ['Admin', 'Kepala Lingkungan', 'Penanggung Jawab'])): ?>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="<?= site_url('dashboard/surat/view'); ?>">
                                <i class="ti ti-file-description"></i>
                                <span class="hide-menu">Surat Pengantar</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (in_array($role, ['Admin', 'Kepala Lingkungan'])): ?>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="<?= site_url('dashboard/report/view'); ?>">
                                <i class="ti ti-report"></i>
                                <span class="hide-menu">Laporan</span>
                            </a>
                        </li>
                    <?php endif; ?>

                </ul>

                <div class="card mb-4 mx-3">
                    <div class="card-body text-center">
                        <h5 class="card-title text-primary"><?= $this->session->userdata('nama'); ?></h5>
                        <p class="card-text"><strong><?= ucfirst($this->session->userdata('role')); ?></strong></p>
                    </div>
                </div>

            </nav>

        </div>
    </aside>
<div class="body-wrapper">