<!-- 1. Home Section -->
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

<!-- 2. About Us Section -->
<section class="about-us" id="about">
    <div class="container-about">
        <div class="about-content">
            <div class="about-text">
                <h2 class="about-title" data-text="About Us">About Us</h2>
                <p class="description" data-text="Lorem ipsum dolor sit, amet consectetur adipisicing elit. Obcaecati quae, iusto quod culpa vitae harum repudiandae reiciendis rerum, tempora odit minus in eum facere. Dolores harum ipsam, et porro non totam exercitationem.">
                    Lorem ipsum dolor sit, amet consectetur adipisicing elit.
                    Obcaecati quae, iusto quod culpa vitae harum repudiandae reiciendis rerum,
                    tempora odit minus in eum facere. Dolores harum ipsam, et porro non totam exercitationem.
                </p>
            </div>
            <div class="card-stat-container">
                <div class="card-stat">
                    <h3 class="count-up" data-target="11">11</h3>
                    <p class="text" data-text="Active Members">Active Members</p>
                </div>
                <div class="card-stat">
                    <h3 class="count-up" data-target="50"> 50</h3>
                    <p class="text" data-text="Related Articles">Related Articles</p>
                </div>
                <div class="card-stat">
                    <h3 class="count-up" data-target="5">5</h3>
                    <p class="text" data-text="Prototypes">Prototypes</p>
                </div>
                <div class="card-stat">
                    <h3 class="count-up" data-target="50"> 50</h3>
                    <p class="text" data-text="Student Involved">Student Involved</p>
                </div>
            </div>
        </div>
        <div class="image-content">
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

<!-- 5. Research Focus Section -->
<section id="research-focus">
    <div style="max-width: 1200px; width: 100%;">
        <h2 class="section-title">Core Research Focus</h2>
        <div class="research-grid">
            <div class="research-card">
                <i class="fas fa-robot research-icon"></i>
                <h3>Artificial Intelligence</h3>
                <p>Advanced machine learning algorithms and neural networks for intelligent systems</p>
            </div>
            <div class="research-card">
                <i class="fas fa-microchip research-icon"></i>
                <h3>Internet of Things</h3>
                <p>Smart sensor networks and connected devices for automation solutions</p>
            </div>
            <div class="research-card">
                <i class="fas fa-database research-icon"></i>
                <h3>Big Data Analytics</h3>
                <p>Processing and analyzing large-scale datasets for actionable insights</p>
            </div>
            <div class="research-card">
                <i class="fas fa-shield-alt research-icon"></i>
                <h3>Cybersecurity</h3>
                <p>Protecting systems and networks from digital threats and vulnerabilities</p>
            </div>
        </div>
    </div>
</section>

<!-- 2. Team Section -->
<section id="team">
    <h2 class="section-title">Our Dedicated Team</h2>
    <div class="team-grid">
        <div class="team-card">
            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=400&fit=crop" alt="Dr. Ahmad Santoso" class="team-image">
            <div class="team-info">
                <h3 class="team-name">Dr. Ahmad Santoso, M.T.</h3>
                <p class="team-position">Lab Head / Professor</p>
                <div class="team-social">
                    <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon"><i class="fas fa-envelope"></i></a>
                </div>
            </div>
        </div>

        <div class="team-card">
            <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=400&h=400&fit=crop" alt="Siti Rahma" class="team-image">
            <div class="team-info">
                <h3 class="team-name">Siti Rahma, S.Kom, M.Kom</h3>
                <p class="team-position">Senior Researcher</p>
                <div class="team-social">
                    <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon"><i class="fas fa-envelope"></i></a>
                </div>
            </div>
        </div>

        <div class="team-card">
            <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=400&h=400&fit=crop" alt="Budi Prasetyo" class="team-image">
            <div class="team-info">
                <h3 class="team-name">Budi Prasetyo, M.Sc</h3>
                <p class="team-position">Research Associate</p>
                <div class="team-social">
                    <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon"><i class="fas fa-envelope"></i></a>
                </div>
            </div>
        </div>

        <div class="team-card">
            <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=400&h=400&fit=crop" alt="Diana Kartika" class="team-image">
            <div class="team-info">
                <h3 class="team-name">Diana Kartika, S.T.</h3>
                <p class="team-position">Lab Assistant</p>
                <div class="team-social">
                    <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon"><i class="fas fa-envelope"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 3. Facilities Section -->
<section id="facilities">
    <div class="facilities-container">
        <h2 class="section-title">Lab Facilities & Equipment</h2>

        <div class="facility-item">
            <img src="https://images.unsplash.com/photo-1518770660439-4636190af475?w=600&h=400&fit=crop" alt="High-Performance Computing Cluster" class="facility-image">
            <div class="facility-content">
                <h3>High-Performance Computing Cluster</h3>
                <p>State-of-the-art computing infrastructure with 128 CPU cores and 512GB RAM for intensive computational tasks and simulations.</p>
                <div class="facility-meta">
                    <span class="facility-tag">Operational</span>
                    <span class="facility-tag">Qty: 2</span>
                </div>
            </div>
        </div>

        <div class="facility-item">
            <img src="https://images.unsplash.com/photo-1581092160562-40aa08e78837?w=600&h=400&fit=crop" alt="IoT Development Kits" class="facility-image">
            <div class="facility-content">
                <h3>IoT Development Kits</h3>
                <p>Complete sets of Arduino, Raspberry Pi, and ESP32 boards with various sensors for prototyping smart systems and embedded solutions.</p>
                <div class="facility-meta">
                    <span class="facility-tag">Operational</span>
                    <span class="facility-tag">Qty: 25</span>
                </div>
            </div>
        </div>

        <div class="facility-item">
            <img src="https://images.unsplash.com/photo-1587440871875-191322ee64b0?w=600&h=400&fit=crop" alt="VR Development Lab" class="facility-image">
            <div class="facility-content">
                <h3>VR Development Lab</h3>
                <p>Professional virtual reality equipment including Oculus Quest 2, HTC Vive, and development workstations for immersive applications.</p>
                <div class="facility-meta">
                    <span class="facility-tag">Operational</span>
                    <span class="facility-tag">Qty: 5</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 4. Projects Section -->
<section id="projects">
    <div style="max-width: 1200px; width: 100%;">
        <h2 class="section-title">Featured Lab Projects</h2>

        <div class="project-filters">
            <button class="filter-btn active" data-filter="all">All Projects</button>
            <button class="filter-btn" data-filter="ai">AI & ML</button>
            <button class="filter-btn" data-filter="iot">IoT</button>
            <button class="filter-btn" data-filter="web">Web Development</button>
            <button class="filter-btn" data-filter="mobile">Mobile Apps</button>
        </div>

        <div class="projects-grid">
            <div class="project-card" data-category="ai">
                <img src="https://images.unsplash.com/photo-1677442136019-21780ecad995?w=600&h=400&fit=crop" alt="Smart Assistant" class="project-image">
                <div class="project-content">
                    <div class="project-tags">
                        <span class="project-tag">#AI</span>
                        <span class="project-tag">#NLP</span>
                    </div>
                    <h3>AI-Powered Smart Assistant</h3>
                    <p>Intelligent virtual assistant using natural language processing and machine learning for automated customer support.</p>
                </div>
            </div>

            <div class="project-card" data-category="iot">
                <img src="https://images.unsplash.com/photo-1558346490-a72e53ae2d4f?w=600&h=400&fit=crop" alt="Smart Home" class="project-image">
                <div class="project-content">
                    <div class="project-tags">
                        <span class="project-tag">#IoT</span>
                        <span class="project-tag">#Automation</span>
                    </div>
                    <h3>Smart Home Automation System</h3>
                    <p>IoT-based home automation with energy monitoring, security features, and mobile control interface.</p>
                </div>
            </div>

            <div class="project-card" data-category="web">
                <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600&h=400&fit=crop" alt="Analytics Dashboard" class="project-image">
                <div class="project-content">
                    <div class="project-tags">
                        <span class="project-tag">#Web</span>
                        <span class="project-tag">#Analytics</span>
                    </div>
                    <h3>Real-Time Analytics Dashboard</h3>
                    <p>Web-based dashboard for visualizing complex datasets with interactive charts and real-time updates.</p>
                </div>
            </div>

            <div class="project-card" data-category="mobile">
                <img src="https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=600&h=400&fit=crop" alt="Health App" class="project-image">
                <div class="project-content">
                    <div class="project-tags">
                        <span class="project-tag">#Mobile</span>
                        <span class="project-tag">#Health</span>
                    </div>
                    <h3>Personal Health Tracker</h3>
                    <p>Mobile application for tracking health metrics, medications, and appointments with AI-powered insights.</p>
                </div>
            </div>

            <div class="project-card" data-category="ai">
                <img src="https://images.unsplash.com/photo-1555949963-aa79dcee981c?w=600&h=400&fit=crop" alt="Image Recognition" class="project-image">
                <div class="project-content">
                    <div class="project-tags">
                        <span class="project-tag">#AI</span>
                        <span class="project-tag">#Vision</span>
                    </div>
                    <h3>Computer Vision Detection System</h3>
                    <p>Advanced image recognition system for object detection and classification using deep learning.</p>
                </div>
            </div>

            <div class="project-card" data-category="iot">
                <img src="https://images.unsplash.com/photo-1473341304170-971dccb5ac1e?w=600&h=400&fit=crop" alt="Agriculture" class="project-image">
                <div class="project-content">
                    <div class="project-tags">
                        <span class="project-tag">#IoT</span>
                        <span class="project-tag">#AgriTech</span>
                    </div>
                    <h3>Smart Agriculture Monitoring</h3>
                    <p>IoT sensor network for monitoring soil conditions, weather, and crop health in real-time.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 5. News Section -->
<section id="news">
    <div style="max-width: 1200px; width: 100%;">
        <h2 class="section-title">Latest News & Announcements</h2>
        <div class="news-grid">
            <div class="news-card">
                <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=600&h=400&fit=crop" alt="Conference" class="news-image">
                <div class="news-content">
                    <div class="news-date">December 5, 2025</div>
                    <h3>Lab Team Presents Research at International AI Conference</h3>
                    <a href="#" class="news-link">Read More <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>

            <div class="news-card">
                <img src="https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=600&h=400&fit=crop" alt="Award" class="news-image">
                <div class="news-content">
                    <div class="news-date">November 28, 2025</div>
                    <h3>Awarded Best Innovation Grant for Smart City Project</h3>
                    <a href="#" class="news-link">Read More <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>

            <div class="news-card">
                <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=600&h=400&fit=crop" alt="Workshop" class="news-image">
                <div class="news-content">
                    <div class="news-date">November 15, 2025</div>
                    <h3>Upcoming Workshop: Introduction to Machine Learning</h3>
                    <a href="#" class="news-link">Read More <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 6. Partners Section -->
<section id="partners">
    <div style="width:100%;">
        <h2 class="section-title">Our Valued Partners</h2>

        <div class="slider">
            <div class="slide-track">
                <!-- gunakan logo transparan -->
                <div class="slide"><img src="https://icon.horse/icon/google.com" alt="Google"></div>
                <div class="slide"><img src="https://icon.horse/icon/microsoft.com" alt="Microsoft"></div>
                <div class="slide"><img src="https://icon.horse/icon/apple.com" alt="Apple"></div>
                <div class="slide"><img src="https://icon.horse/icon/amazon.com" alt="Amazon"></div>
                <div class="slide"><img src="https://icon.horse/icon/netflix.com" alt="Netflix"></div>
                <div class="slide"><img src="https://icon.horse/icon/spotify.com" alt="Spotify"></div>
                <div class="slide"><img src="https://icon.horse/icon/meta.com" alt="Meta"></div>

                <!-- duplikasi untuk infinite slide -->
                <div class="slide"><img src="https://icon.horse/icon/google.com" alt="Google"></div>
                <div class="slide"><img src="https://icon.horse/icon/microsoft.com" alt="Microsoft"></div>
                <div class="slide"><img src="https://icon.horse/icon/apple.com" alt="Apple"></div>
                <div class="slide"><img src="https://icon.horse/icon/amazon.com" alt="Amazon"></div>
                <div class="slide"><img src="https://icon.horse/icon/netflix.com" alt="Netflix"></div>
                <div class="slide"><img src="https://icon.horse/icon/spotify.com" alt="Spotify"></div>
                <div class="slide"><img src="https://icon.horse/icon/meta.com" alt="Meta"></div>

            </div>
        </div>
    </div>
</section>


<!-- Product -->
<section id="products">
    <div style="max-width: 1200px; width: 100%;">
        <h2 class="section-title">Latest Products</h2>
        <div class="product-grid">

            <!-- Product 1 -->
            <div class="product-card">
                <div class="product-image-container">
                    <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=600&h=400&fit=crop"
                        alt="Nama Produk" class="product-image">
                    <span class="product-release-tag">Rilis: 15 Jan 2025</span>
                </div>
                <div class="product-info">
                    <h3 class="product-name">Nama Produk Inovatif Terbaru</h3>
                    <p class="product-description">
                        Inovasi teknologi yang dirancang untuk memudahkan pekerjaanmu.
                    </p>
                    <a href="detail-product.html?id=1" class="btn-detail">
                        Lihat Detail <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Product 2 -->
            <div class="product-card">
                <div class="product-image-container">
                    <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=600&h=400&fit=crop"
                        alt="Nama Produk" class="product-image">
                    <span class="product-release-tag">Rilis: 15 Jan 2025</span>
                </div>
                <div class="product-info">
                    <h3 class="product-name">Nama Produk Inovatif Terbaru</h3>
                    <p class="product-description">
                        Inovasi baru yang lebih efisien dan ramah lingkungan.
                    </p>
                    <a href="detail-product.html?id=1" class="btn-detail">
                        Lihat Detail <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="product-card">
                <div class="product-image-container">
                    <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=600&h=400&fit=crop"
                        alt="Nama Produk" class="product-image">
                    <span class="product-release-tag">Rilis: 15 Jan 2025</span>
                </div>
                <div class="product-info">
                    <h3 class="product-name">Nama Produk Inovatif Terbaru</h3>
                    <p class="product-description">
                        Inovasi baru yang lebih efisien dan ramah lingkungan.
                    </p>
                    <a href="detail-product.html?id=1" class="btn-detail">
                        Lihat Detail <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="product-card">
                <div class="product-image-container">
                    <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=600&h=400&fit=crop"
                        alt="Nama Produk" class="product-image">
                    <span class="product-release-tag">Rilis: 15 Jan 2025</span>
                </div>
                <div class="product-info">
                    <h3 class="product-name">Nama Produk Inovatif Terbaru</h3>
                    <p class="product-description">
                        Inovasi baru yang lebih efisien dan ramah lingkungan.
                    </p>
                    <a href="detail-product.html?id=1" class="btn-detail">
                        Lihat Detail <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="product-card">
                <div class="product-image-container">
                    <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=600&h=400&fit=crop"
                        alt="Nama Produk" class="product-image">
                    <span class="product-release-tag">Rilis: 15 Jan 2025</span>
                </div>
                <div class="product-info">
                    <h3 class="product-name">Nama Produk Inovatif Terbaru</h3>
                    <p class="product-description">
                        Inovasi baru yang lebih efisien dan ramah lingkungan.
                    </p>
                    <a href="detail-product.html?id=1" class="btn-detail">
                        Lihat Detail <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Gallery -->
<section id="gallery">
    <div style="max-width: 1200px; width: 100%;">
        <h2 class="section-title">Lab Snapshot</h2>

        <div class="gallery-carousel-container">
            <button class="gallery-nav prev">
                <i class="fas fa-chevron-left"></i>
            </button>

            <div class="gallery-carousel">
                <div class="gallery-slide">
                    <img src="https://images.unsplash.com/photo-1581092160562-40aa08e78837?w=600&h=500&fit=crop" alt="Lab Equipment">
                    <div class="gallery-overlay">
                        <div class="gallery-overlay-title">Modern Lab Equipment</div>
                        <div class="gallery-overlay-desc">State-of-the-art technology for research</div>
                    </div>
                </div>

                <div class="gallery-slide">
                    <img src="https://images.unsplash.com/photo-1532094349884-543bc11b234d?w=600&h=500&fit=crop" alt="Team Collaboration">
                    <div class="gallery-overlay">
                        <div class="gallery-overlay-title">Team Collaboration</div>
                        <div class="gallery-overlay-desc">Working together on innovative projects</div>
                    </div>
                </div>

                <div class="gallery-slide">
                    <img src="https://images.unsplash.com/photo-1573164713714-d95e436ab8d6?w=600&h=500&fit=crop" alt="Workshop Session">
                    <div class="gallery-overlay">
                        <div class="gallery-overlay-title">Workshop Session</div>
                        <div class="gallery-overlay-desc">Hands-on training and learning</div>
                    </div>
                </div>

                <div class="gallery-slide">
                    <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=600&h=500&fit=crop" alt="Research Discussion">
                    <div class="gallery-overlay">
                        <div class="gallery-overlay-title">Research Discussion</div>
                        <div class="gallery-overlay-desc">Sharing ideas and insights</div>
                    </div>
                </div>

                <div class="gallery-slide">
                    <img src="https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=600&h=500&fit=crop" alt="Development Work">
                    <div class="gallery-overlay">
                        <div class="gallery-overlay-title">Development Work</div>
                        <div class="gallery-overlay-desc">Building next-gen solutions</div>
                    </div>
                </div>

                <div class="gallery-slide">
                    <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=600&h=500&fit=crop" alt="Team Meeting">
                    <div class="gallery-overlay">
                        <div class="gallery-overlay-title">Team Meeting</div>
                        <div class="gallery-overlay-desc">Planning and strategy sessions</div>
                    </div>
                </div>

                <div class="gallery-slide">
                    <img src="https://images.unsplash.com/photo-1587440871875-191322ee64b0?w=600&h=500&fit=crop" alt="VR Testing">
                    <div class="gallery-overlay">
                        <div class="gallery-overlay-title">VR Testing</div>
                        <div class="gallery-overlay-desc">Exploring immersive technologies</div>
                    </div>
                </div>

                <div class="gallery-slide">
                    <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=600&h=500&fit=crop" alt="Data Analysis">
                    <div class="gallery-overlay">
                        <div class="gallery-overlay-title">Data Analysis</div>
                        <div class="gallery-overlay-desc">Processing and visualizing insights</div>
                    </div>
                </div>

                <div class="gallery-slide">
                    <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?w=600&h=500&fit=crop" alt="Presentation">
                    <div class="gallery-overlay">
                        <div class="gallery-overlay-title">Project Presentation</div>
                        <div class="gallery-overlay-desc">Showcasing our achievements</div>
                    </div>
                </div>
            </div>

            <button class="gallery-nav next">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>

        <div class="gallery-indicators">
            <div class="gallery-indicator active"></div>
            <div class="gallery-indicator"></div>
            <div class="gallery-indicator"></div>
        </div>
    </div>
</section>