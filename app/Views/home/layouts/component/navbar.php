<nav class="navbar">
    <div class="container-nav">

        <img src="<?= asset('assets/logo/logo.png') ?>" alt="Logo Icon" class="logo">

        <ul class="menu">
            <li><a href="#home" data-text="Home"><span>Home</span></a></li>
            <li><a href="#about" data-text="About Us"><span>About Us</span></a></li>
            <li><a href="<?= base_url('admin/login') ?>" data-text="Team"><span>Team</span></a></li>
            <li><a href="#" data-text="Gallery"><span>Gallery</span></a></li>
            <li><a href="#" data-text="Shop Now" class="mobile-menu" style="display: none"><span>Shop Now</span></a></li>
        </ul>

        <a href="#" class="btn-try" data-text="Try Now">
            <span>Try Now</span>
        </a>

        <div class="tombol">
            &#9776;
        </div>

    </div>
</nav>