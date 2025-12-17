<?php

use App\Models\SiteSettings;

$siteSetting = new SiteSettings();

$configLogo = $siteSetting->getConfig('logo_path');

$path = __DIR__ . '/../../../../../public/uploads/settings/' . $configLogo->logo_path;

$logo = '';
if ($configLogo->logo_path && file_exists($path)) {
    $logo = asset('uploads/settings/' . $configLogo->logo_path);
} else {
    $logo = asset('assets/logo/logo.png');
}

$current_url = current_url();
$base_url = base_url();
$relative_url = '';
if (strpos($current_url, $base_url) === 0) {
    $relative_url = substr($current_url, strlen($base_url));
}

$relative_url = trim($relative_url, '/');
$segments = explode('/', $relative_url);
$main_segment = $segments[0] ?? '';

?>

<nav class="navbar navbar-expand-lg fixed-top" id="mainNav">
    <div class="container container-nav">
        <a class="navbar-brand fw-bold d-flex align-items-center" href="#hero-slider">
            <img src="<?= $logo ?>" alt="Logo Lab" class="logo">
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link <?= ($main_segment === '') ? 'active' : '' ?>" href="<?= base_url() ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($main_segment === 'gallery') ? 'active' : '' ?>" href="<?= base_url('gallery') ?>">Gallery</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($main_segment === 'news') ? 'active' : '' ?>" href="<?= base_url('news') ?>">News</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($main_segment === 'products') ? 'active' : '' ?>" href="<?= base_url('products') ?>">Products</a>
                </li>
            </ul>
            
            <a href="<?= base_url() ?>#maps-section" class="btn btn-book d-none d-lg-block">
                Visit Lab
            </a>
        </div>
    </div>
</nav>