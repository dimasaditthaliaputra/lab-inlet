<?php

function isActive($link)
{
    return ($link === current_url()) ? 'active' : '';
}

function isSubmenuActive($children)
{
    foreach ($children as $submenu) {
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
                    // 1. MAIN MENU
                    ['header' => 'Main Menu'],
                    [
                        'title' => 'Dashboard',
                        'icon'  => 'bi bi-grid-fill',
                        'link'  => base_url('admin/dashboard'),
                    ],

                    // 2. CONTENT MANAGEMENT (CMS)
                    ['header' => 'Content Management'],
                    [
                        'title' => 'News',
                        'icon'  => 'bi bi-newspaper',
                        'link'  => base_url('admin/news'),
                    ],
                    [
                        'title'    => 'Project Management',
                        'icon'     => 'bi bi-folder-fill',
                        'children' => [
                            ['title' => 'Data Project', 'link' => base_url('admin/project-lab')],
                            ['title' => 'Categories', 'link' => base_url('admin/kategori-project')],
                        ]
                    ],
                    [
                        'title'    => 'Company Profile',
                        'icon'     => 'bi bi-building-fill',
                        'children' => [
                            ['title' => 'About Us', 'link' => base_url('admin/aboutus')],
                            ['title' => 'Team', 'link' => base_url('admin/team')],
                            ['title' => 'Partner', 'link' => base_url('admin/partner')],
                        ]
                    ],
                    [
                        'title'    => 'Gallery',
                        'icon'     => 'bi bi-images',
                        'children' => [
                            ['title' => 'Image', 'link' => base_url('admin/gallery/image')],
                            ['title' => 'Video', 'link' => base_url('admin/gallery/video')],
                        ]
                    ],
                    [
                        'title' => 'Product',
                        'icon'  => 'bi bi-box-seam',
                        'link'  => base_url('admin/product'),
                    ],
                    [
                        'title' => 'Hero Slider',
                        'icon'  => 'bi bi-sliders',
                        'link'  => base_url('admin/hero-slider'),
                    ],

                    // 3. ATTENDANCE
                    ['header' => 'Attendance System'],
                    [
                        'title' => 'Time Setting',
                        'icon'  => 'bi bi-clock-fill',
                        'link'  => base_url('admin/attendance-settings'),
                    ],

                    // 4. SYSTEM MANAGEMENT
                    ['header' => 'System Management'],
                    [
                        'title'    => 'User Management',
                        'icon'     => 'bi bi-gear-fill',
                        'children' => [
                            ['title' => 'Roles', 'link' => base_url('admin/roles')],
                            ['title' => 'Users', 'link' => base_url('admin/user')],
                        ]
                    ],
                    [
                        'title' => 'Site Settings',
                        'icon'  => 'bi bi-sliders2-vertical',
                        'link'  => base_url('admin/site-settings'),
                    ],
                    [
                        'title' => 'Log Activity',
                        'icon'  => 'bi bi-file-earmark-text-fill',
                        'link'  => base_url('admin/log-activity'),
                    ],
                ];

                foreach ($menus as $menu):
                    if (isset($menu['header'])): ?>
                        <li class="sidebar-title"><?= $menu['header'] ?></li>

                    <?php
                    elseif (isset($menu['children'])):
                        $isSubmenuActive = isSubmenuActive($menu['children']);
                    ?>
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

                    <?php
                    else:
                        $activeClass = isActive($menu['link']);
                    ?>
                        <li class="sidebar-item <?= $activeClass ?>">
                            <a href="<?= $menu['link'] ?>" class="sidebar-link">
                                <i class="<?= $menu['icon'] ?> <?= $activeClass ? '' : 'icon-white' ?>"></i>
                                <span><?= $menu['title'] ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>