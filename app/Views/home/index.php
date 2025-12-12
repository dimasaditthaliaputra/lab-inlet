<section id="hero-slider" class="position-relative overflow-hidden">
    <div id="hero-slides-wrapper" class="hero-slides-wrapper">
        <div class="loader-placeholder d-flex align-items-center justify-content-center h-100 w-100 bg-light">
            <div class="spinner-border text-primary" role="status"></div>
        </div>
    </div>

    <button class="slider-nav prev" aria-label="Previous Slide">
        <i class="bi bi-chevron-left"></i>
    </button>
    <button class="slider-nav next" aria-label="Next Slide">
        <i class="bi bi-chevron-right"></i>
    </button>

    <div id="hero-dots" class="slider-dots">
    </div>
</section>

<section id="about-us" class="py-section bg-light position-relative overflow-hidden">
    <div class="position-absolute top-0 end-0 translate-middle-y opacity-25" style="z-index: 0;">
        <svg width="400" height="400" viewBox="0 0 400 400" fill="none">
            <circle cx="200" cy="200" r="200" fill="url(#paint0_linear)" />
            <defs>
                <linearGradient id="paint0_linear" x1="200" y1="0" x2="200" y2="400" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#0066FF" stop-opacity="0.1" />
                    <stop offset="1" stop-color="white" stop-opacity="0" />
                </linearGradient>
            </defs>
        </svg>
    </div>

    <div class="container position-relative" style="z-index: 1;">
        <div class="row align-items-center g-5">

            <div class="col-lg-6 order-2 order-lg-1">
                <div id="about-content">
                    <div class="placeholder-glow">
                        <span class="placeholder col-6 mb-3"></span>
                        <span class="placeholder col-12 mb-2"></span>
                        <span class="placeholder col-12 mb-2"></span>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 order-1 order-lg-2">
                <div id="about-grid" class="about-image-grid">
                </div>
            </div>

        </div>
    </div>
</section>

<section id="research" class="py-section">
    <div class="container">
        <div class="container text-center mb-5">
            <h2 class="fw-bold">Our Research Areas</h2>
            <p class="text-muted mx-auto" style="max-width: 600px;">
                We continue to expand our research focus in areas such as climate change, renewable energy, and sustainable development, aiming to deliver impactful work and an excellent learning environment.
            </p>
        </div>
        <div id="research-container" class="row g-4 justify-content-center">
            <div class="col-12 text-center text-muted">Loading research areas...</div>
        </div>
    </div>
</section>

<div class="seamless-wrapper position-relative overflow-hidden">

    <div class="bg-decoration blob-top-left"></div>

    <div class="bg-decoration blob-center-right"></div>

    <div class="bg-decoration blob-bottom-left"></div>


    <section id="team-carousel" class="py-5 bg-transparent position-relative z-2">
        <div class="container position-relative" style="z-index: 2;">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8 text-center">
                    <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary px-3 py-2 mb-3 fw-bold">
                        MEET THE EXPERTS
                    </span>
                    <h2 class="display-6 fw-bold text-dark mb-3" style="font-family: var(--font-heading);">
                        Our Dedicated Team
                    </h2>
                    <p class="text-muted lead">
                        Innovators and researchers working together to shape the future of technology.
                    </p>
                </div>
            </div>
        </div>

        <div class="team-slider-wrapper">
            <div class="slider-fade fade-left"></div>
            <div class="slider-fade fade-right"></div>

            <div id="team-track" class="team-track">
            </div>
        </div>
    </section>


    <section id="facilities" class="py-section bg-transparent position-relative z-2 pt-0">
        <div class="container position-relative z-2">
            <div class="row mb-5 align-items-end">
                <div class="col-lg-6">
                    <span class="text-primary fw-bold text-uppercase ls-1 small">Infrastructure</span>
                    <h2 class="display-5 fw-bold font-heading text-dark mt-2">Lab Facilities & Equipment</h2>
                </div>
                <div class="col-lg-6 text-lg-end">
                    <p class="text-muted mb-0 lead">State-of-the-art tools powering our research.</p>
                </div>
            </div>

            <div id="facilities-container" class="row g-4">
            </div>
        </div>
    </section>

</div>

<div class="modal fade" id="facilityModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content glass-modal border-0 rounded-4 overflow-hidden">
            <div class="position-relative">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3 z-3 bg-dark rounded-circle p-2 opacity-75 hover-opacity-100" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-img-wrapper bg-black d-flex align-items-center justify-content-center" style="min-height: 400px;">
                    <img src="" id="modalFacilityImg" class="img-fluid" alt="Facility Preview" style="max-height: 80vh;">
                </div>
                <div class="modal-caption p-4 text-white position-absolute bottom-0 start-0 w-100"
                    style="background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.6) 50%, transparent 100%);">

                    <h3 class="h4 fw-bold mb-1" id="modalFacilityTitle"></h3>
                    <p class="mb-0 opacity-75 small" id="modalFacilityDesc"></p>
                    <span class="badge mt-2" id="modalFacilityBadge"></span>

                </div>
            </div>
        </div>
    </div>
</div>

<section id="projects" class="py-section bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold font-heading">Featured Lab Projects</h2>
            <div id="project-filters" class="d-flex flex-wrap justify-content-center gap-2 mt-4" role="tablist">
            </div>
        </div>
        <div id="projects-grid" class="row g-4">
        </div>
    </div>
</section>

<section id="news" class="py-section bg-black overflow-hidden position-relative" style="min-height: 700px;">
    <div class="position-absolute top-50 start-50 translate-middle" style="width: 100%; height: 100%; background: radial-gradient(circle, rgba(20, 20, 20, 1) 0%, rgba(0,0,0,1) 80%); z-index: 0;"></div>

    <div class="container position-relative h-100 d-flex flex-column justify-content-center" style="z-index: 1;">
        <div class="text-center mb-5">
            <h2 class="fw-bold font-heading text-white">Latest News</h2>
            <p class="text-white-50">Updates from our laboratory</p>
        </div>

        <div class="news-3d-container position-relative w-100" style="height: 350px;">
            <div id="news-track" class="news-track h-100 w-100 position-relative">
            </div>
        </div>

        <div class="d-flex justify-content-center align-items-center gap-4 mt-5">
            <button class="news-nav-btn prev" id="news-prev" aria-label="Previous">
                <i class="bi bi-arrow-left"></i>
            </button>

            <div id="news-indicators" class="d-flex gap-2"></div>

            <button class="news-nav-btn next" id="news-next" aria-label="Next">
                <i class="bi bi-arrow-right"></i>
            </button>
        </div>
    </div>
</section>

<section id="partners" class="py-5 bg-white border-top border-bottom overflow-hidden">
    <div class="container">
        <h6 class="text-center text-uppercase text-muted ls-2 mb-4">Our Valued Partners</h6>
        <div class="partner-wrapper">
            <div id="partners-track" class="partner-track d-flex align-items-center gap-5">
            </div>
        </div>
    </div>
</section>

<section id="gallery" class="py-section">
    <div class="container">
        <h2 class="fw-bold font-heading mb-5">Lab Snapshot</h2>
        <div id="gallery-grid" class="row g-3" data-masonry='{"percentPosition": true }'>
        </div>
    </div>
</section>

<section id="products" class="product-section position-relative overflow-hidden">
    <div class="ambient-glow"></div>

    <div class="container position-relative z-2">
        <div class="row mb-5 align-items-end">
            <div class="col-lg-6">
                <h6 class="text-blue fw-bold ls-2 text-uppercase mb-2">Our Innovations</h6>
                <h2 class="display-4 fw-bold text-white lh-1">Digital <br>Craftsmanship.</h2>
            </div>
            <div class="col-lg-6 text-lg-end">
                <p class="text-white mb-0 max-w-400 ms-auto">
                    Exploring the boundaries of technology with cutting-edge applications designed for the future.
                </p>
            </div>
        </div>

        <div id="product-grid" class="product-grid">
        </div>
    </div>
</section>

<section id="maps-section" class="py-section bg-light position-relative overflow-hidden">
    <div class="container">
        <div class="row align-items-center g-5">

            <div class="col-lg-5 order-2 order-lg-1">
                <div class="pe-lg-4">
                    <span class="badge bg-primary-subtle text-primary mb-3 rounded-pill px-3 py-2 fw-semibold">
                        <i class="bi bi-geo-alt-fill me-1"></i> Visit Us
                    </span>
                    <h2 class="display-5 fw-bold font-heading mb-3">Find Us Easily</h2>
                    <p class="text-muted lead mb-4">
                        Our campus laboratory can be visited for collaboration, research and academic consultation.
                    </p>

                    <div class="d-flex flex-column gap-3 mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-circle bg-white text-primary shadow-sm">
                                <i class="bi bi-envelope"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Email Support</small>
                                <span class="fw-semibold text-dark">contact@labtech.edu</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-circle bg-white text-primary shadow-sm">
                                <i class="bi bi-telephone"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Call Us</small>
                                <span class="fw-semibold text-dark">+62 812 3456 7890</span>
                            </div>
                        </div>
                    </div>

                    <a href="https://maps.google.com" target="_blank" class="btn btn-outline-primary rounded-pill px-4 py-2 fw-semibold">
                        Get Directions <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-7 order-1 order-lg-2">
                <div class="map-wrapper shadow-lg position-relative" data-aos="fade-up">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3951.437194721996!2d112.6142844!3d-7.9536966!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e78827928958613%3A0xd604b360251f49c0!2sUniversitas%20Brawijaya!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid"
                        width="100%"
                        height="100%"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Lab Location"
                        class="map-iframe">
                    </iframe>

                    <div class="floating-map-card bg-white p-3 p-md-4 shadow-lg rounded-4 d-flex align-items-center gap-3">
                        <div class="map-icon-box bg-primary text-white rounded-3 d-flex align-items-center justify-content-center flex-shrink-0">
                            <i class="bi bi-building-fill fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1 font-heading">Main Laboratory</h6>
                            <p class="small text-muted mb-0">Building A4, 2nd Floor, Science Park St.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<?php ob_start(); ?>
    <script src="<?= asset('js/utils.js') ?>"></script>
<?php $pageScripts = ob_get_clean(); ?>