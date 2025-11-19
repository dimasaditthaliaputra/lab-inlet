<section class="home" id="home">
    <div class="home-slider">
        <div class="home-list">
            <div class="item">
                <img src="<?= asset('assets/images/1.jpg') ?>" alt="Laboratorium" loading="eager" fetchpriority="high">
                <div class="content">
                    <h2 class="title" data-text="Welcome to Our Laboratory">Welcome to Our Laboratory</h2>
                    <p class="description" data-text="Innovation Starts Here">Innovation Starts Here</p>
                </div>
            </div>
            <div class="item">
                <img src="<?= asset('assets/images/2.jpg') ?>" alt="Research Team" loading="lazy">
                <div class="content">
                    <h2 class="title" data-text="Excellence in Research">Excellence in Research</h2>
                    <p class="description" data-text="Committed to Scientific Advancement">Committed to Scientific Advancement</p>
                </div>
            </div>
            <div class="item">
                <img src="<?= asset('assets/images/3.jpg') ?>" alt="Laboratory Equipment" loading="lazy">
                <div class="content">
                    <h2 class="title" data-text="Modern Facilities">Modern Facilities</h2>
                    <p class="description" data-text="Equipped for Innovation and Discovery">Equipped for Innovation and Discovery</p>
                </div>
            </div>
            <div class="item">
                <img src="<?= asset('assets/images/4.webp') ?>" alt="Collaboration" loading="lazy">
                <div class="content">
                    <h2 class="title" data-text="Collaborate. Innovate. Discover.">Collaborate. Innovate. Discover.</h2>
                    <p class="description" data-text="Together We Build the Future of Science">Together We Build the Future of Science</p>
                </div>
            </div>
        </div>
        <ul class="slider-dots">
            <li class="active"></li>
            <li></li>
            <li></li>
            <li></li>
        </ul>
    </div>
</section>

<section class="about-us" id="about">
    <div class="container-about">
        <div class="about-content">
            <div class="about-text">
                <h2 class="about-title text-slide-animate text-slide-left" data-text="About Us">About Us</h2>
                <p class="description fade-animate fade-in-left" data-text="Lorem ipsum dolor sit, amet consectetur adipisicing elit. Obcaecati quae, iusto quod culpa vitae harum repudiandae reiciendis rerum, tempora odit minus in eum facere. Dolores harum ipsam, et porro non totam exercitationem.">
                    Lorem ipsum dolor sit, amet consectetur adipisicing elit.
                    Obcaecati quae, iusto quod culpa vitae harum repudiandae reiciendis rerum,
                    tempora odit minus in eum facere. Dolores harum ipsam, et porro non totam exercitationem.
                </p>
            </div>
            <div class="card-stat-container">
                <div class="card-stat">
                    <h3 class="count-up" data-target="11">11</h3>
                    <p class="text text-slide-animate" data-text="Active Members">Active Members</p>
                </div>
                <div class="card-stat">
                    <h3 class="count-up" data-target="50"> 50</h3>
                    <p class="text text-slide-animate" data-text="Related Articles">Related Articles</p>
                </div>
                <div class="card-stat">
                    <h3 class="count-up" data-target="5">5</h3>
                    <p class="text text-slide-animate" data-text="Prototypes">Prototypes</p>
                </div>
                <div class="card-stat">
                    <h3 class="count-up" data-target="50"> 50</h3>
                    <p class="text text-slide-animate" data-text="Student Involved">Student Involved</p>
                </div>
            </div>
        </div>
        <div class="image-content fade-animate fade-in fade-delay-400">
            <!-- Foto 1 - Kiri Atas -->
            <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" class="blob-1">
                <defs>
                    <clipPath id="blobClip1">
                        <path d="M55.9,-61C67,-44.9,66.6,-22.4,64.8,-1.8C63,18.8,59.7,37.7,48.7,52.5C37.7,67.4,18.8,78.2,1.9,76.3C-15,74.4,-30.1,59.8,-44.4,44.9C-58.8,30.1,-72.5,15,-74.5,-2C-76.5,-19.1,-66.9,-38.2,-52.6,-54.3C-38.2,-70.4,-19.1,-83.5,1.7,-85.2C22.4,-86.8,44.9,-77.1,55.9,-61Z" transform="translate(100 100)" />
                    </clipPath>
                </defs>
                <image href="<?= asset('assets/images/1.jpg') ?>"
                    width="200"
                    height="200"
                    clip-path="url(#blobClip1)"
                    preserveAspectRatio="xMidYMid slice" />
            </svg>

            <!-- Foto 2 - Tengah Bawah -->
            <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" class="blob-2">
                <defs>
                    <clipPath id="blobClip2">
                        <path d="M40.1,-40C50.7,-29.4,57.3,-14.7,57,-0.4C56.6,14,49.3,28,38.7,40.7C28,53.4,14,64.7,-1.7,66.4C-17.5,68.1,-34.9,60.2,-51.1,47.6C-67.4,34.9,-82.4,17.5,-80.5,1.9C-78.6,-13.6,-59.6,-27.2,-43.4,-37.8C-27.2,-48.4,-13.6,-56,0.6,-56.5C14.7,-57.1,29.4,-50.6,40.1,-40Z" transform="translate(100 100)" />
                    </clipPath>
                </defs>
                <image href="<?= asset('assets/images/2.jpg') ?>"
                    width="200"
                    height="200"
                    clip-path="url(#blobClip2)"
                    preserveAspectRatio="xMidYMid slice" />
            </svg>

            <!-- Foto 3 - Kanan Atas -->
            <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" class="blob-3">
                <defs>
                    <clipPath id="blobClip3">
                        <path d="M20.5,-34.2C24.9,-20.7,25.6,-12.7,35.8,0.3C46,13.4,65.7,31.3,63.7,38.5C61.7,45.8,37.8,42.2,19.1,46.4C0.3,50.6,-13.5,62.7,-22,59.9C-30.6,57,-34.1,39.3,-42.2,24.6C-50.4,9.9,-63.3,-1.8,-65.2,-15C-67.1,-28.3,-57.9,-43.2,-45.2,-55.2C-32.5,-67.2,-16.2,-76.4,-4.1,-71.5C8,-66.7,16.1,-47.7,20.5,-34.2Z" transform="translate(100 100)" />
                    </clipPath>
                </defs>
                <image href="<?= asset('assets/images/3.jpg') ?>"
                    width="200"
                    height="200"
                    clip-path="url(#blobClip3)"
                    preserveAspectRatio="xMidYMid slice" />
            </svg>

            <!-- Blob Background Dekoratif -->
            <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" class="blob-bg blob-bg-1">
                <path fill="#2F54EB" d="M45.8,-45.5C62.1,-40.8,80.1,-29.1,83.5,-14.1C86.9,0.9,75.8,19.1,64.8,36.7C53.8,54.3,43,71.4,30.2,70.8C17.3,70.2,2.5,52.1,-7.2,39.8C-16.8,27.5,-21.3,21.2,-26.4,14.3C-31.4,7.4,-37.1,-0.1,-38.3,-9.1C-39.5,-18.1,-36.2,-28.7,-29.1,-35C-22,-41.3,-11,-43.2,1.9,-45.4C14.7,-47.7,29.4,-50.1,45.8,-45.5Z" transform="translate(100 100)" />
            </svg>

            <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" class="blob-bg blob-bg-2">
                <path fill="#2F54EB" d="M55.2,-58C71.6,-52.1,84.9,-34.7,89.2,-15C93.5,4.6,88.8,26.6,77.7,43.9C66.6,61.2,49,73.9,31.3,76C13.6,78,-4.3,69.6,-20.6,61.5C-36.8,53.4,-51.4,45.8,-55.7,34.2C-60,22.6,-53.9,7.1,-53.2,-11.5C-52.4,-30.1,-57.1,-51.8,-49.1,-59.2C-41.1,-66.6,-20.6,-59.7,-0.6,-59C19.4,-58.4,38.9,-63.9,55.2,-58Z" transform="translate(100 100)" />
            </svg>

            <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" class="blob-bg blob-bg-3">
                <path fill="#D46B08" d="M41.7,-52.3C52.4,-43.2,58.3,-28.4,61.5,-12.4C64.7,3.6,65.2,20.8,58.4,34.3C51.6,47.8,37.5,57.6,21.8,62.4C6.1,67.2,-11.2,67,-26.3,61.3C-41.4,55.6,-54.3,44.4,-61.5,30.1C-68.7,15.8,-70.2,-1.6,-65.3,-16.8C-60.4,-32,-49.1,-45,-35.9,-53.7C-22.7,-62.4,-7.6,-66.8,5.3,-63.9C18.2,-61,31,-50.4,41.7,-52.3Z" transform="translate(100 100)" />
            </svg>

            <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" class="blob-bg blob-bg-4">
                <path fill="#D46B08" d="M48.2,-56.7C61.3,-48.3,70.2,-32.3,73.8,-15C77.4,2.3,75.7,20.9,67.4,35.9C59.1,50.9,44.2,62.3,27.6,67.5C11,72.7,-7.3,71.7,-24.1,65.8C-40.9,59.9,-56.2,49.1,-64.8,34.2C-73.4,19.3,-75.3,0.3,-71.1,-16.5C-66.9,-33.3,-56.6,-47.9,-43.2,-56.2C-29.8,-64.5,-13.2,-66.5,2.3,-69.3C17.8,-72.1,35.1,-65.1,48.2,-56.7Z" transform="translate(100 100)" />
            </svg>
        </div>
    </div>
</section>