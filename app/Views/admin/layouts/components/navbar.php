<nav class="navbar navbar-expand navbar-light navbar-top">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav justify-content-between mb-lg-0 w-100">
                <!-- Jam Realtime -->
                <li class="nav-item d-flex align-items-center me-3">
                    <div class="d-flex flex-column text-start">
                        <span class="fw-bold text-dark" id="current-time">00:00:00</span>
                        <small class="text-muted" id="current-date">Loading...</small>
                    </div>
                </li>

                <div class="d-flex gap-2">
                    <!-- Notifikasi -->
                    <li class="nav-item dropdown me-1">
                        <a class="nav-link active dropdown-toggle text-gray-600" href="#"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-bell bi-sub fs-4"></i>
                            <span class="badge bg-danger badge-notification">5</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end notification-dropdown border"
                            aria-labelledby="dropdownMenuButton">
                            <li class="dropdown-header">
                                <h6>Notifikasi</h6>
                            </li>
                            <li>
                                <a class="dropdown-item notification-item">
                                    <i class="bi bi-info-circle text-primary"></i>
                                    <div class="notification-content">
                                        <span class="notification-title">Sistem Update</span>
                                        <span class="notification-time">5 menit lalu</span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item notification-item">
                                    <i class="bi bi-check-circle text-success"></i>
                                    <div class="notification-content">
                                        <span class="notification-title">Data berhasil disimpan</span>
                                        <span class="notification-time">10 menit lalu</span>
                                    </div>
                                </a>
                            </li>
                            <li><a class="dropdown-item text-center" href="#">Lihat Semua</a></li>
                        </ul>
                    </li>

                    <!-- User Profile -->
                    <li class="nav-item dropdown me-3">
                        <a class="nav-link active text-gray-600" href="#"
                            data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                            <div class="user-dropdown d-lg-flex d-none gap-2">
                                <div class="avatar avatar-md2 me-2">
                                    <img src="<?php echo asset('assets/mazer/static/images/faces/2.jpg'); ?>"
                                        alt="Avatar">
                                </div>
                                <div class="text">
                                    <h6 class="user-dropdown-name mb-0"><?php echo session('user')?->full_name ?? 'Username'; ?></h6>
                                    <p class="user-dropdown-status text-sm text-muted mb-0"><?php echo session('user')?->role_name ?? 'Admin'; ?></p>
                                </div>
                            </div>
                        </a>
                    </li>
                </div>
            </ul>
        </div>
    </div>
</nav>