<header>
    <nav class="navbar navbar-expand navbar-light navbar-top">
        <div class="container-fluid">
            <a href="#" class="burger-btn d-block">
                <i class="bi bi-justify fs-3"></i>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-lg-0">
                    <!-- Jam Realtime -->
                    <li class="nav-item d-flex align-items-center me-3">
                        <div class="d-flex flex-column text-start">
                            <span class="fw-bold text-dark" id="current-time">00:00:00</span>
                            <small class="text-muted" id="current-date">Loading...</small>
                        </div>
                    </li>

                    <!-- Notifikasi -->
                    <li class="nav-item dropdown me-1">
                        <a class="nav-link active dropdown-toggle text-gray-600" href="#"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-bell bi-sub fs-4"></i>
                            <span class="badge bg-danger badge-notification">5</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end notification-dropdown"
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
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-center" href="#">Lihat Semua</a></li>
                        </ul>
                    </li>

                    <!-- User Profile -->
                    <li class="nav-item dropdown me-3">
                        <a class="nav-link active text-gray-600" href="#"
                            data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                            <div class="user-dropdown d-lg-flex d-none">
                                <div class="avatar avatar-md2 me-2">
                                    <img src="https://ui-avatars.com/api/?name=Admin+User&background=4e73df&color=fff"
                                        alt="Avatar">
                                </div>
                                <div class="text">
                                    <h6 class="user-dropdown-name mb-0">Admin User</h6>
                                    <p class="user-dropdown-status text-sm text-muted mb-0">Administrator</p>
                                </div>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>