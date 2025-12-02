<?php

use App\Models\Menu;

$menuModel = new Menu();

$user = session('user');
$roleId = $user->id_roles ?? 0;

if ($roleId) {
    $menus = $menuModel->getMenusByRole($roleId);
} else {
    $menus = [];
}

if (!function_exists('isActive')) {
    function isActive($link)
    {
        if (empty($link)) return '';
        return (strpos(current_url(), $link) !== false) ? 'active' : '';
    }
}

if (!function_exists('isSubmenuActive')) {
    function isSubmenuActive($children)
    {
        foreach ($children as $submenu) {
            if (!empty($submenu['link']) && strpos(current_url(), $submenu['link']) !== false) {
                return true;
            }
        }
        return false;
    }
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

                <?php foreach ($menus as $menu): ?>
                    <?php
                    if ($menu['is_active'] == false):
                    ?>
                        <li class="sidebar-title"><?= $menu['title'] ?></li>

                    <?php
                    elseif (!empty($menu['children'])):
                        $isSubmenuActive = isSubmenuActive($menu['children']);
                    ?>
                        <li class="sidebar-item has-sub <?= $isSubmenuActive ? 'active' : '' ?>">
                            <a href="#" class="sidebar-link">
                                <i class="<?= $menu['icon'] ?> icon-white"></i>
                                <span><?= $menu['title'] ?></span>
                            </a>
                            <ul class="submenu <?= $isSubmenuActive ? 'active' : '' ?>">
                                <?php foreach ($menu['children'] as $submenu): ?>
                                    <li class="submenu-item <?= isActive($submenu['link']) ?>">
                                        <a href="<?= base_url($submenu['link']) ?>" class="submenu-link">
                                            <?= $submenu['title'] ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>

                    <?php
                    else:
                        $activeClass = isActive($menu['link']);
                    ?>
                        <li class="sidebar-item <?= $activeClass ?>">
                            <a href="<?= base_url($menu['link']) ?>" class="sidebar-link">
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