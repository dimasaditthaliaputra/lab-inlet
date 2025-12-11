<?php

use App\Models\SiteSettings;

$siteSetting = new SiteSettings();

$configName = $siteSetting->getConfig('site_name');
?>

<footer>
    <div class="footer clearfix mb-0 text-muted">
        <div class="float-start">
            <p>2025 &copy; <?php echo $configName;?> </p>
        </div>
        <div class="float-end">
            <p>Crafted with <span class="text-danger">Love</p>
        </div>
    </div>
</footer>