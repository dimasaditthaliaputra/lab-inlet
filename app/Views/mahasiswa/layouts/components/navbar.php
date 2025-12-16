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
                            <i class="bi bi-clock-history bi-sub fs-4"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end notification-dropdown border"
                            aria-labelledby="dropdownMenuButton">
                            <li class="dropdown-header">
                                <h6>Notification</h6>
                            </li>
                            <?php
                            $notification = get_recent_notifications();

                            $colorMap = [
                                'Create'      => ['icon' => 'bi-check-circle',     'color' => 'text-primary'],
                                'Update'   => ['icon' => 'bi-info-circle',    'color' => 'text-success'],
                                'Delete'   => ['icon' => 'bi-trash-fill', 'color' => 'text-danger']
                            ];
                            ?>

                            <?php foreach ($notification as $item): ?>
                                <?php
                                $type = $item['type'];
                                $icon  = $colorMap[$type]['icon']  ?? 'bi-info-circle';
                                $color = $colorMap[$type]['color'] ?? 'text-primary';
                                ?>

                                <li>
                                    <a class="dropdown-item notification-item">
                                        <i class="bi <?= $icon ?> <?= $color ?>" style="margin-right:1rem;"></i>

                                        <div class="notification-content">
                                            <span class="notification-title"><?= $item['description'] ?></span>
                                            <span class="notification-time"><?= $item['created_at'] ?></span>
                                        </div>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                            <li><a class="dropdown-item text-center" href="<?= base_url('admin/log-activity') ?>">Lihat Semua</a></li>
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
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow border">
                            <li>
                                <a class="dropdown-item" href="<?= base_url('admin/profile/' . session('user')?->id ?? '') ?>">
                                    <i class="bi bi-person me-2"></i> Profile
                                </a>
                            </li>
                            <li>
                                <form class="dropdown-item" id="logout-form">
                                    <a id="logout-link" class="d-flex gap-2" style="cursor: pointer;">
                                        <i class="bi bi-box-arrow-right text-danger"></i>
                                        <span class="text-danger">Logout</span>
                                    </a>
                                </form>
                            </li>
                        </ul>
                        </a>
                    </li>
                </div>
            </ul>
        </div>
    </div>
</nav>