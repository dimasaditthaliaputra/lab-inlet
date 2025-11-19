<?php
function isActive($link) {
    // Bandingkan Link Menu (Full URL) dengan URL Browser Saat ini (Full URL)
    // current_url() diambil dari helper Anda yang sudah ada
    return ($link === current_url()) ? 'active' : '';
}

function isSubmenuActive($children) {
    foreach ($children as $submenu) {
        // Cek apakah salah satu anak memiliki link yang sama dengan URL saat ini
        if ($submenu['link'] === current_url()) {
            return true;
        }
    }
    return false;
}
?>

<div id="sidebar">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
            <div class="d-flex justify-content-between align-items-center">
                <div class="logo">
                    <a href="<?= base_url('admin/dashboard') ?>">
                        <img src="<?= asset('assets/logo/logo.png') ?>" alt="Logo" class="logo-image">
                    </a>
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
                        'title' => 'Configuration',
                        'icon' => 'bi bi-gear-fill',
                        'children' => [
                            ['title' => 'Roles', 'link' => base_url('admin/roles')],
                            ['title' => 'User', 'link' => base_url('admin/user')],
                        ]
                    ],
                    [
                        'title' => 'Log Activity',
                        'icon' => 'bi bi-file-earmark-text-fill',
                        'link' => base_url('admin/log-activity'),
                    ]
                ];
                foreach ($menus as $menu):
                    $hasChildren = isset($menu['children']);
                    $isSubmenuActive = $hasChildren && isSubmenuActive($menu['children']);
                ?>
                    <?php if ($hasChildren): ?>
                        <li class="sidebar-item has-sub <?= $isSubmenuActive ? 'active' : '' ?>">
                            <a href="#" class="sidebar-link">
                                <i class="<?= $menu['icon'] ?> icon-white"></i>
                                <span><?= $menu['title'] ?></span>
                            </a>
                            <ul class="submenu">
                                <?php foreach ($menu['children'] as $submenu): ?>
                                    <li class="submenu-item <?= isActive($submenu['link']) ?>">
                                        <a href="<?= $submenu['link'] ?>" class="submenu-link"><?= $submenu['title'] ?></a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="sidebar-item <?= isActive($menu['link']) ?>">
                            <a href="<?= $menu['link'] ?>" class="sidebar-link">
                                <i class="<?= $menu['icon'] ?> icon-white"></i>
                                <span><?= $menu['title'] ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
                <li class="sidebar-item" style="border-bottom: none">
                    <form id="logout-form">
                        <a id="logout-link" class="sidebar-link">
                            <i class="bi bi-box-arrow-right icon-white"></i>
                            <span>Logout</span>
                        </a>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>