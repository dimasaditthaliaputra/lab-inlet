<div id="sidebar">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
            <div class="d-flex justify-content-between align-items-center">
                <div class="logo">
                    <a href="<?= base_url('admin/dashboard') ?>">
                        <img src="<?= asset('assets/logo/logo.png') ?>" alt="Logo" style="width: 120px; height: auto;">
                    </a>
                </div>
                <div class="theme-toggle d-flex gap-2 align-items-center mt-2">
                    <!-- dummy icons -->
                    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" width="20" height="20"
                        viewBox="0 0 21 21" fill="none" stroke="currentColor" stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="10.5" cy="10.5" r="4" />
                        <path d="M10.5 2.5v2m0 14v2m8-8h2M2.5 10.5h2" />
                    </svg>
                    <div class="form-check form-switch fs-6">
                        <input class="form-check-input me-0" type="checkbox" id="toggle-dark" style="cursor: pointer" />
                        <label class="form-check-label"></label>
                    </div>
                </div>
                <div class="sidebar-toggler x">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>

        <div class="sidebar-menu">
            <ul class="menu">
                <?php
                $menus = [
                    [
                        'title' => 'Dashboard',
                        'icon' => 'bi bi-grid-fill',
                        'link' => base_url('admin/dashboard'),
                    ],
                    [
                        'title' => 'Pengaturan',
                        'icon' => 'bi bi-gear-fill',
                        'children' => [
                            ['title' => 'Roles', 'link' => base_url('admin/roles')],
                            ['title' => 'Pengguna', 'link' => base_url('admin/pengguna')],
                        ]
                    ],
                ];

                foreach ($menus as $menu):
                    $hasChildren = isset($menu['children']);
                ?>
                    <?php if ($hasChildren): ?>
                        <li class="sidebar-item has-sub">
                            <a href="#" class="sidebar-link">
                                <i class="<?= $menu['icon'] ?>"></i>
                                <span><?= $menu['title'] ?></span>
                            </a>
                            <ul class="submenu">
                                <?php foreach ($menu['children'] as $submenu): ?>
                                    <li class="submenu-item">
                                        <a href="<?= $submenu['link'] ?>" class="submenu-link"><?= $submenu['title'] ?></a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="sidebar-item">
                            <a href="<?= $menu['link'] ?>" class="sidebar-link">
                                <i class="<?= $menu['icon'] ?>"></i>
                                <span><?= $menu['title'] ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>

                <li class="sidebar-item">
                    <form id="logout-form">
                        <a id="logout-link" href="#" class="sidebar-link">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Logout</span>
                        </a>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>