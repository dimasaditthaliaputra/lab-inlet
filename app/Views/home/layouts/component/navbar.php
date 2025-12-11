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
                    <a class="nav-link active" href="#hero-slider">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#about-us">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#projects">Projects</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#maps-section">Contact</a>
                </li>
            </ul>
            
            <a href="#maps-section" class="btn btn-book d-none d-lg-block">
                Visit Lab
            </a>
        </div>
    </div>
</nav>