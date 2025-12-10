/**
 * main.js - LabTech Landing Page Logic
 * Handles data fetching (mocked), rendering, and interactions.
 */

// --- 1. Mock Data / Schema (Simulasi Backend) ---
const MOCK_API = {
  news: [
    {
      id: 1,
      title: "Research Grant Awarded",
      image_name: "https://placehold.co/400x300",
      publish_date: "2025-11-01",
      excerpt: "We secured $1M funding for clean energy research.",
      slug: "grant-awarded",
    },
    {
      id: 2,
      title: "New Partnership with TechCorp",
      image_name: "https://placehold.co/400x300",
      publish_date: "2025-10-15",
      excerpt: "Collaborating on industrial automation standards.",
      slug: "tech-corp",
    },
  ],
  partners: [
    { id: 1, name: "Google", partner_logo: "https://placehold.co/150x50/png?text=Google" },
    { id: 2, name: "Microsoft", partner_logo: "https://placehold.co/150x50/png?text=Microsoft" },
    { id: 3, name: "Nvidia", partner_logo: "https://placehold.co/150x50/png?text=Nvidia" },
    { id: 4, name: "Intel", partner_logo: "https://placehold.co/150x50/png?text=Intel" },
    { id: 5, name: "Tesla", partner_logo: "https://placehold.co/150x50/png?text=Tesla" },
  ],
  gallery: [
    {
      id: 1,
      image_name: "https://placehold.co/600x800?text=Lab+1",
      title: "Clean Room",
      description: "ISO 5 Clean room setup.",
    },
    {
      id: 2,
      image_name: "https://placehold.co/600x400?text=Lab+2",
      title: "Server Rack",
      description: "High performance compute nodes.",
    },
    {
      id: 3,
      image_name: "https://placehold.co/600x600?text=Lab+3",
      title: "Meeting Area",
      description: "Collaborative space.",
    },
    {
      id: 4,
      image_name: "https://placehold.co/600x500?text=Lab+4",
      title: "Soldering Station",
      description: "Electronics assembly.",
    },
  ],
};

// --- 2. Helper Functions --

// Simulate Fetch
const fetchData = async (endpoint) => {
  return new Promise((resolve) => setTimeout(() => resolve(MOCK_API[endpoint]), 300));
};

const initHeroSlider = async () => {
  const wrapper = document.getElementById("hero-slides-wrapper");
  const dotsContainer = document.getElementById("hero-dots");

  if (!wrapper || !dotsContainer) {
    console.warn("Hero Slider DOM elements not found.");
    return;
  }

  // --- 1. SHOW SKELETON LOADING ---
  // We inject this immediately while waiting for the fetch
  wrapper.innerHTML = `
    <div class="hero-slide active" style="z-index: 1;">
        <div class="slide-bg bg-secondary opacity-25"></div>
        
        <div class="container hero-content-wrapper">
            <div class="glass-card placeholder-glow" style="opacity: 1; transform: translateY(0);">
                <span class="placeholder col-4 mb-3"></span>
                
                <h1 class="placeholder col-10 mb-2" style="height: 3rem;"></h1>
                <h1 class="placeholder col-7 mb-4" style="height: 3rem;"></h1>
                
                <a class="btn btn-secondary disabled placeholder col-3 mt-4 rounded-pill" aria-disabled="true"></a>
            </div>
        </div>
    </div>
  `;

  const prevBtn = document.querySelector(".slider-nav.prev");
  const nextBtn = document.querySelector(".slider-nav.next");

  let sliderData = [];
  let currentSlide = 0;
  let autoPlayInterval;
  const AUTO_PLAY_DELAY = 6000;

  try {
    const response = await fetch("http://inlet-lab.test/api/hero-slider");

    if (!response.ok) throw new Error(`HTTP Error: ${response.status}`);

    const result = await response.json();

    if (result.success && Array.isArray(result.data)) {
      sliderData = result.data
        .filter((item) => item.is_active == true || item.is_active == 1 || item.is_active === "1")
        .sort((a, b) => parseInt(a.sort_order) - parseInt(b.sort_order));
    } else {
      throw new Error(result.message || "Invalid API Response structure");
    }
  } catch (error) {
    console.error("Hero Slider API Fetch Failed:", error);
    // Replace Skeleton with Error Message
    wrapper.innerHTML = `<div class="d-flex align-items-center justify-content-center h-100 w-100 bg-light text-muted">Failed to load slides.</div>`;
    return;
  }

  if (sliderData.length === 0) {
    // Replace Skeleton with Empty Message
    wrapper.innerHTML = `<div class="d-flex align-items-center justify-content-center h-100 w-100 bg-light text-muted">No active slides found.</div>`;
    return;
  }

  // --- Render Function (This will overwrite the Skeleton) ---
  const renderSlider = (data) => {
    let slidesHTML = "";
    let dotsHTML = "";

    data.forEach((slide, index) => {
      const isActive = index === 0 ? "active" : "";

      // Build Button HTML
      const btnHTML =
        slide.button_text && slide.button_text.trim() !== ""
          ? `<a href="${slide.button_url}" class="hero-btn mt-4">${slide.button_text}</a>`
          : "";

      // Render Slide
      slidesHTML += `
                <div class="hero-slide ${isActive}" data-index="${index}">
                    <div class="slide-overlay"></div>
                    <img src="${slide.image_name}" class="slide-bg" alt="${slide.title}" loading="${
        index === 0 ? "eager" : "lazy"
      }">
                    <div class="container hero-content-wrapper">
                        <div class="glass-card">
                            <span class="hero-subtitle">${slide.subtitle}</span>
                            <h1 class="hero-title">${slide.title}</h1>
                            ${btnHTML}
                        </div>
                    </div>
                </div>
            `;

      // Render Dot
      dotsHTML += `<div class="dot ${isActive}" data-index="${index}"></div>`;
    });

    // This overwrites the skeleton HTML
    wrapper.innerHTML = slidesHTML;
    dotsContainer.innerHTML = dotsHTML;
  };

  // 4. Navigation Logic
  const goToSlide = (index) => {
    const slides = document.querySelectorAll(".hero-slide");
    const dots = document.querySelectorAll(".dot");
    const total = slides.length;

    if (total === 0) return;

    if (index >= total) index = 0;
    if (index < 0) index = total - 1;

    currentSlide = index;

    slides.forEach((el) => el.classList.remove("active"));
    dots.forEach((el) => el.classList.remove("active"));

    if (slides[currentSlide]) slides[currentSlide].classList.add("active");
    if (dots[currentSlide]) dots[currentSlide].classList.add("active");
  };

  const nextSlide = () => goToSlide(currentSlide + 1);
  const prevSlide = () => goToSlide(currentSlide - 1);

  // 5. Autoplay Logic
  const startAutoPlay = () => {
    clearInterval(autoPlayInterval);
    autoPlayInterval = setInterval(nextSlide, AUTO_PLAY_DELAY);
  };

  const resetTimer = () => {
    clearInterval(autoPlayInterval);
    startAutoPlay();
  };

  // 6. Execute Render & Bind Events
  renderSlider(sliderData);
  startAutoPlay();

  if (nextBtn)
    nextBtn.addEventListener("click", () => {
      nextSlide();
      resetTimer();
    });
  if (prevBtn)
    prevBtn.addEventListener("click", () => {
      prevSlide();
      resetTimer();
    });

  dotsContainer.addEventListener("click", (e) => {
    if (e.target.classList.contains("dot")) {
      const index = parseInt(e.target.dataset.index);
      goToSlide(index);
      resetTimer();
    }
  });

  // Mobile Swipe
  let touchStartX = 0;
  wrapper.addEventListener("touchstart", (e) => (touchStartX = e.changedTouches[0].screenX));
  wrapper.addEventListener("touchend", (e) => {
    const touchEndX = e.changedTouches[0].screenX;
    if (touchStartX - touchEndX > 50) {
      nextSlide();
      resetTimer();
    }
    if (touchEndX - touchStartX > 50) {
      prevSlide();
      resetTimer();
    }
  });
};

const initAbout = async () => {
  const contentContainer = document.getElementById("about-content");
  const gridContainer = document.getElementById("about-grid");

  // Safety check
  if (!contentContainer) return;

  // --- 1. INJECT SKELETON LOADING ---
  // Tampilkan kerangka sebelum data dimuat
  contentContainer.innerHTML = `
        <div class="placeholder-glow">
            <span class="placeholder col-6 mb-4 display-6"></span>
            
            <div class="mb-5">
                <span class="placeholder col-12 mb-1"></span>
                <span class="placeholder col-12 mb-1"></span>
                <span class="placeholder col-12 mb-1"></span>
                <span class="placeholder col-9"></span>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="vm-card h-100 p-4">
                        <span class="placeholder col-2 mb-3 bg-primary opacity-25" style="height:40px; width:40px; border-radius:8px; display:block;"></span>
                        <span class="placeholder col-6 mb-2 fw-bold"></span>
                        <span class="placeholder col-10 mb-1"></span>
                        <span class="placeholder col-8"></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="vm-card h-100 p-4">
                        <span class="placeholder col-2 mb-3 bg-primary opacity-25" style="height:40px; width:40px; border-radius:8px; display:block;"></span>
                        <span class="placeholder col-6 mb-2 fw-bold"></span>
                        <span class="placeholder col-10 mb-1"></span>
                        <span class="placeholder col-8"></span>
                    </div>
                </div>
            </div>
        </div>
    `;

  if (gridContainer) {
    // Kita butuh 3 item skeleton agar sesuai dengan layout CSS Grid (1 besar kiri, 2 kecil kanan)
    gridContainer.innerHTML = `
        <div class="about-img-item placeholder-glow">
            <span class="placeholder w-100 h-100 bg-secondary opacity-25"></span>
        </div>
        <div class="about-img-item placeholder-glow">
            <span class="placeholder w-100 h-100 bg-secondary opacity-25"></span>
        </div>
        <div class="about-img-item placeholder-glow">
            <span class="placeholder w-100 h-100 bg-secondary opacity-25"></span>
        </div>
    `;
  }

  try {
    // 2. Fetch Real API
    const response = await fetch("http://inlet-lab.test/api/about-us");

    if (!response.ok) throw new Error(`HTTP Error: ${response.status}`);

    const result = await response.json();

    // 3. Validate Data
    if (result.success && Array.isArray(result.data) && result.data.length > 0) {
      const data = result.data[0];

      // 4. Render Actual Text Content (Overwrites Skeleton)
      contentContainer.innerHTML = `
            <h2 class="display-6 about-title">${data.title}</h2>
            <p class="text-muted lead mb-5 text-justify" style="line-height: 1.8;">
                ${data.description}
            </p>
            
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="vm-card h-100 text-justify">
                        <div class="vm-icon"><i class="bi bi-eye"></i></div>
                        <h5 class="fw-bold font-heading">Our Vision</h5>
                        <p class="small text-muted mb-0">${data.vision}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="vm-card h-100 text-justify">
                        <div class="vm-icon"><i class="bi bi-bullseye"></i></div>
                        <h5 class="fw-bold font-heading">Our Mission</h5>
                        <p class="small text-muted mb-0" style="white-space: pre-line;">${data.mission}</p>
                    </div>
                </div>
            </div>
        `;

      // 5. Render Actual Images (Overwrites Skeleton)
      if (gridContainer && Array.isArray(data.aboutusimages)) {
        const images = data.aboutusimages.slice(0, 3);

        gridContainer.innerHTML = images
          .map(
            (img, index) => `
            <div class="about-img-item">
                <img src="${img.url}" 
                     alt="${img.alt}" 
                     loading="lazy" 
                     width="${index === 0 ? "600" : "400"}" 
                     height="${index === 0 ? "800" : "400"}">
            </div>
        `
          )
          .join("");
      }
    } else {
      throw new Error(result.message || "Data About Us tidak ditemukan");
    }
  } catch (error) {
    console.error("About Us API Fetch Failed:", error);
    contentContainer.innerHTML = `<div class="text-center text-muted p-5">Failed to load About Us content.</div>`;
    if (gridContainer) gridContainer.innerHTML = "";
  }
};

// --- 3. Render Functions ---
const initResearch = async () => {
  const container = document.getElementById("research-container");

  if (!container) return;

  const skeletonCard = `
    <div class="col-md-6 col-lg-4">
        <article class="research-card d-flex flex-column align-items-center text-center p-4">
            <div class="placeholder-glow mb-3">
                <span class="placeholder bg-secondary rounded-circle opacity-25" style="width: 50px; height: 50px; display: inline-block;"></span>
            </div>
            <h3 class="h5 fw-bold mb-2 font-heading placeholder-glow w-100">
                <span class="placeholder col-6"></span>
            </h3>
            <div class="w-100 placeholder-glow">
                <span class="placeholder col-10"></span>
                <span class="placeholder col-8"></span>
            </div>
        </article>
    </div>
  `;
  container.innerHTML = skeletonCard.repeat(3);

  try {
    const response = await fetch("http://inlet-lab.test/api/research");

    if (!response.ok) throw new Error(`HTTP Error: ${response.status}`);

    const result = await response.json();

    console.log(result);

    if (result.success && Array.isArray(result.data)) {
      container.innerHTML = result.data
        .map((item) => {
          const desc = item.description ? item.description : "";

          return `
            <div class="col-md-6 col-lg-4">
                <article class="research-card d-flex flex-column align-items-center text-center" tabindex="0">
                    <i class="${item.icon_name} research-icon" aria-hidden="true"></i>
                    <h3 class="h5 fw-bold mb-2 font-heading">${item.title}</h3>
                    <p class="text-muted small line-clamp-2">${desc}</p>
                </article>
            </div>
        `;
        })
        .join("");
    } else {
      throw new Error("Invalid Data Structure");
    }
  } catch (error) {
    console.error("Research API Fetch Failed:", error);
    container.innerHTML = `<div class="col-12 text-center text-muted">Failed to load research areas.</div>`;
  }
};

const initTeamCarousel = async () => {
  const container = document.getElementById("team-track");

  // Safety Check
  if (!container) return;

  // --- 1. INJECT SKELETON LOADING ---
  // Kita buat 4 skeleton item untuk memenuhi lebar layar desktop
  const skeletonItem = `
    <div class="team-carousel-item">
        <div class="team-card p-4">
            <div class="placeholder-glow d-flex justify-content-center mb-3">
                <span class="placeholder rounded-circle bg-secondary opacity-25" style="width: 120px; height: 120px;"></span>
            </div>
            
            <h4 class="team-name placeholder-glow text-center mb-2">
                <span class="placeholder col-8"></span>
            </h4>
            
            <div class="team-role placeholder-glow text-center mb-3">
                <span class="placeholder col-5"></span>
            </div>
            
            <div class="team-social placeholder-glow d-flex justify-content-center gap-2">
                <span class="placeholder col-2 rounded-circle" style="width:24px; height:24px;"></span>
                <span class="placeholder col-2 rounded-circle" style="width:24px; height:24px;"></span>
            </div>
        </div>
    </div>
  `;
  
  // Render 4 skeleton items
  container.innerHTML = skeletonItem.repeat(4);

  try {
    // --- 2. FETCH REAL API ---
    const response = await fetch("http://inlet-lab.test/api/team");

    if (!response.ok) throw new Error(`HTTP Error: ${response.status}`);

    const result = await response.json();

    if (result.success && Array.isArray(result.data) && result.data.length > 0) {
      const data = result.data;

      // Helper function untuk HTML Card
      const createCardHTML = (member) => `
        <div class="team-carousel-item">
            <div class="team-card">
                <img src="${member.image_name}" alt="${member.full_name}" loading="lazy">
                <h4 class="team-name">${member.full_name}</h4>
                <div class="team-role">${member.lab_position}</div>
                <div class="team-social">
                    ${member.social
                      .map(
                        (s) => `
                        <a href="${s.url}" target="_blank" rel="noopener" aria-label="${s.type}">
                            <i class="bi bi-${s.type}"></i>
                        </a>
                    `
                      )
                      .join("")}
                </div>
            </div>
        </div>
      `;

      // --- 3. LOGIC DUPLIKASI (Infinite Scroll) ---
      // Kita duplikasi data 3x agar animasi CSS scroll tidak putus
      const duplicateCount = 3;
      let finalItems = [];

      for (let i = 0; i < duplicateCount; i++) {
        finalItems = finalItems.concat(data);
      }

      // Render Final HTML
      container.innerHTML = finalItems.map((member) => createCardHTML(member)).join("");
      
    } else {
      throw new Error(result.message || "Data Team tidak ditemukan");
    }

  } catch (error) {
    console.error("Team API Fetch Failed:", error);
    container.innerHTML = `<div class="w-100 text-center text-muted p-5">Failed to load team members.</div>`;
    // Stop animasi jika error agar tidak aneh
    container.style.animation = "none";
    container.style.display = "block";
  }
};

const initFacilities = async () => {
  const container = document.getElementById("facilities-container");

  // Safety Check
  if (!container) return;

  // --- 1. INJECT SKELETON LOADING ---
  // Membuat 2 item skeleton (kiri dan kanan)
  const skeletonItem = `
    <div class="col-12 col-lg-6">
        <div class="facility-item d-flex align-items-center gap-3 p-2">
            <span class="placeholder bg-secondary opacity-25 rounded" style="width: 80px; height: 80px; flex-shrink: 0;"></span>
            
            <div class="flex-grow-1 placeholder-glow">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <span class="placeholder col-6 mb-1 fw-bold"></span>
                    <span class="placeholder col-3 rounded-pill" style="height: 20px;"></span>
                </div>
                <span class="placeholder col-12 mb-1"></span>
                <span class="placeholder col-8"></span>
            </div>
        </div>
    </div>
  `;
  container.innerHTML = skeletonItem.repeat(2);

  try {
    // --- 2. FETCH REAL API ---
    // Pastikan route di routes.php sudah ada: Router::get('/api/facilities', [HomeController::class, 'getFacilities']);
    const response = await fetch("http://inlet-lab.test/api/facilities");

    if (!response.ok) throw new Error(`HTTP Error: ${response.status}`);

    const result = await response.json();

    if (result.success && Array.isArray(result.data)) {
      
      // --- 3. RENDER DATA ---
      container.innerHTML = result.data
        .map((item) => {
          // Logic Badge Color: Cek jika kondisi 'Operational', 'good', atau 'Good' -> Hijau, selain itu Kuning
          const isOperational = ["Operational", "good", "Good"].includes(item.condition);
          const statusClass = isOperational 
            ? "bg-success-subtle text-success" 
            : "bg-warning-subtle text-warning";

          // Gunakan default placeholder jika image null (meskipun controller sudah handle, ini double safety)
          const imgUrl = item.image_name || "https://placehold.co/120x120?text=No+Img";

          return `
            <div class="col-12 col-lg-6">
                <div class="facility-item d-flex align-items-center gap-3">
                    <img src="${imgUrl}" alt="${item.name}" class="facility-img bg-light object-fit-cover" style="width: 80px; height: 80px; border-radius: 8px;" loading="lazy">
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <h4 class="h6 fw-bold mb-0 font-heading">${item.name}</h4>
                            <span class="badge ${statusClass} rounded-pill fw-normal" style="font-size:0.7rem">
                                ${item.condition}
                            </span>
                        </div>
                        <p class="small text-muted mb-0 line-clamp-2">${item.description}</p>
                    </div>
                </div>
            </div>
            `;
        })
        .join("");
    } else {
      container.innerHTML = `<div class="col-12 text-center text-muted">No facilities found.</div>`;
    }
  } catch (error) {
    console.error("Facilities API Fetch Failed:", error);
    container.innerHTML = `<div class="col-12 text-center text-muted">Failed to load facilities.</div>`;
  }
};

const initProjects = async () => {
  const filterContainer = document.getElementById("project-filters");
  const grid = document.getElementById("projects-grid");

  // Safety Check
  if (!filterContainer || !grid) return;

  // --- 1. INJECT SKELETON LOADING ---
  // Tampilkan 3 skeleton card di grid
  const skeletonCard = `
    <div class="col-md-6 col-lg-4">
        <div class="project-card h-100 d-flex flex-column border-0 shadow-sm">
            <div class="project-img-wrapper placeholder-glow">
                <span class="placeholder w-100 h-100 bg-secondary opacity-25"></span>
            </div>
            <div class="p-4 flex-grow-1 placeholder-glow">
                <div class="mb-2">
                    <span class="placeholder col-3 rounded-pill"></span>
                    <span class="placeholder col-3 rounded-pill"></span>
                </div>
                <h4 class="h5 fw-bold font-heading mb-2">
                    <span class="placeholder col-8"></span>
                </h4>
                <p class="text-muted small mb-0">
                    <span class="placeholder col-12"></span>
                    <span class="placeholder col-10"></span>
                </p>
            </div>
        </div>
    </div>
  `;
  grid.innerHTML = skeletonCard.repeat(3);

  try {
    // --- 2. FETCH REAL API ---
    const response = await fetch("http://inlet-lab.test/api/projects");

    if (!response.ok) throw new Error(`HTTP Error: ${response.status}`);

    const result = await response.json();

    if (result.success && result.data) {
      // API Controller mengembalikan { categories: [], items: [] }
      const { categories, items } = result.data;

      // --- 3. RENDER FILTERS ---
      filterContainer.innerHTML = `
            <button class="filter-btn active" data-filter="all">All</button>
            ${categories
              .map((c) => `<button class="filter-btn" data-filter="${c.id}">${c.name}</button>`)
              .join("")}
        `;

      // --- 4. RENDER ITEMS FUNCTION ---
      const renderItems = (projectList) => {
        if (projectList.length === 0) {
          grid.innerHTML = `<div class="col-12 text-center py-5 text-muted">No projects found.</div>`;
          return;
        }

        grid.innerHTML = projectList
          .map((p) => {
            // Map category IDs to Badges
            const categoryBadges = p.category_ids
              .map((cid) => {
                const cat = categories.find((c) => c.id === cid);
                return cat
                  ? `<span class="badge bg-light text-dark border me-1">${cat.name}</span>`
                  : "";
              })
              .join("");

            // Use Placeholder if image is missing/broken URL
            const imgUrl = p.image_url && p.image_url !== "" 
                ? p.image_url 
                : "https://placehold.co/600x400/png?text=Project";

            return `
                <div class="col-md-6 col-lg-4 project-item">
                    <div class="project-card h-100 d-flex flex-column">
                        <div class="project-img-wrapper">
                            <img src="${imgUrl}" alt="${p.name}" loading="lazy">
                        </div>
                        <div class="p-4 flex-grow-1">
                            <div class="mb-2">
                                 ${categoryBadges}
                            </div>
                            <h4 class="h5 fw-bold font-heading">${p.name}</h4>
                            <p class="text-muted small mb-0 line-clamp-2">${p.description}</p>
                        </div>
                    </div>
                </div>
            `;
          })
          .join("");
      };

      // Initial Render
      renderItems(items);

      // --- 5. FILTER LOGIC ---
      filterContainer.addEventListener("click", (e) => {
        if (!e.target.classList.contains("filter-btn")) return;

        // UI Update (Active State)
        filterContainer.querySelectorAll(".filter-btn").forEach((b) => b.classList.remove("active"));
        e.target.classList.add("active");

        const filterVal = e.target.dataset.filter;
        
        let filtered = items;
        if (filterVal !== "all") {
            filtered = items.filter((item) => item.category_ids.includes(parseInt(filterVal)));
        }

        grid.style.opacity = "0";
        setTimeout(() => {
          renderItems(filtered);
          grid.style.opacity = "1";
        }, 200);
      });

    } else {
      throw new Error("Invalid API Data");
    }
  } catch (error) {
    console.error("Projects API Fetch Failed:", error);
    grid.innerHTML = `<div class="col-12 text-center text-muted py-5">Failed to load projects.</div>`;
  }
};

const initNews = async () => {
    const track = document.getElementById("news-track");
    const dotsContainer = document.getElementById("news-indicators");
    const prevBtn = document.getElementById("news-prev");
    const nextBtn = document.getElementById("news-next");

    if (!track) return;

    // --- 1. SKELETON LOADING ---
    const skeletonHTML = `
        <div class="news-card active" style="z-index: 20; opacity: 1; visibility: visible;">
            <div class="news-card-img placeholder-glow">
                <span class="placeholder w-100 h-100 bg-secondary opacity-25"></span>
            </div>
            <div class="news-card-body placeholder-glow">
                <span class="placeholder col-4 mb-2"></span>
                <span class="placeholder col-10 mb-3" style="height: 2rem;"></span>
                <span class="placeholder col-12"></span>
                <span class="placeholder col-8"></span>
            </div>
        </div>
    `;
    track.innerHTML = skeletonHTML;

    try {
        // --- 2. FETCH API ---
        const response = await fetch("http://inlet-lab.test/api/news");
        if (!response.ok) throw new Error("Network Error");
        const result = await response.json();

        if (result.success && Array.isArray(result.data)) {
            let newsData = result.data;

            if (newsData.length === 0) {
                track.innerHTML = `<div class="text-white text-center w-100 pt-5">No news available.</div>`;
                return;
            }

            // --- 3. RENDER ITEMS ---
            track.innerHTML = ""; // Clear skeleton
            dotsContainer.innerHTML = "";

            // Render Card Elements
            newsData.forEach((n, index) => {
                const imgUrl = n.image_name || "https://placehold.co/400x300?text=News";
                const date = new Date(n.publish_date).toLocaleDateString('en-US', { day: 'numeric', month: 'short', year: 'numeric' });

                const card = document.createElement("div");
                card.className = "news-card"; // Base class (hidden by default via CSS)
                card.dataset.index = index;
                card.innerHTML = `
                    <div class="news-card-img">
                        <img src="${imgUrl}" alt="${n.title}" loading="lazy">
                    </div>
                    <div class="news-card-body">
                        <div class="news-date">${date}</div>
                        <h3 class="news-title">${n.title}</h3>
                        <div class="news-desc">${n.excerpt}</div>
                        <a href="/news/${n.slug}" class="btn btn-sm btn-outline-light rounded-pill px-4 mt-2 align-self-start">Read More</a>
                    </div>
                `;
                track.appendChild(card);

                // Render Dot
                const dot = document.createElement("div");
                dot.className = "news-dot";
                dot.addEventListener("click", () => updateCarousel(index));
                dotsContainer.appendChild(dot);
            });

            // --- 4. CAROUSEL STATE LOGIC ---
            const items = document.querySelectorAll(".news-card");
            const dots = document.querySelectorAll(".news-dot");
            const totalItems = items.length;
            let currentIndex = 0;

            const updateCarousel = (newIndex) => {
                // Handle Infinite Loop limits
                if (newIndex < 0) newIndex = totalItems - 1;
                if (newIndex >= totalItems) newIndex = 0;
                
                currentIndex = newIndex;

                // Calculate Indices
                // Gunakan modulo aritmatika untuk circular index
                const prevIndex = (currentIndex - 1 + totalItems) % totalItems;
                const nextIndex = (currentIndex + 1) % totalItems;

                // Reset Class Names untuk semua item
                items.forEach(item => {
                    item.className = "news-card"; // Reset ke default (hidden)
                });

                dots.forEach(dot => dot.classList.remove("active"));

                // Apply Classes sesuai spesifikasi
                // 1. Active (Tengah, Terbesar, Z-Index Tertinggi)
                items[currentIndex].classList.add("active");
                
                // 2. Prev (Kiri, Kecil, Di bawah)
                // Hanya tampilkan jika total item > 1
                if (totalItems > 1) items[prevIndex].classList.add("prev");

                // 3. Next (Kanan, Kecil, Di bawah)
                // Hanya tampilkan jika total item > 2 (agar tidak tumpang tindih jika cuma 2 item)
                if (totalItems > 2) items[nextIndex].classList.add("next");

                // Update Dot Active
                if(dots[currentIndex]) dots[currentIndex].classList.add("active");
            };

            // --- 5. EVENT LISTENERS ---
            nextBtn.addEventListener("click", () => updateCarousel(currentIndex + 1));
            prevBtn.addEventListener("click", () => updateCarousel(currentIndex - 1));

            // Mobile Swipe Logic
            let touchStartX = 0;
            track.addEventListener("touchstart", e => touchStartX = e.touches[0].clientX);
            track.addEventListener("touchend", e => {
                const touchEndX = e.changedTouches[0].clientX;
                if (touchStartX - touchEndX > 50) updateCarousel(currentIndex + 1); // Swipe Left -> Next
                if (touchEndX - touchStartX > 50) updateCarousel(currentIndex - 1); // Swipe Right -> Prev
            });

            // Initialize First State
            updateCarousel(0);

        } else {
            throw new Error("Invalid API Data");
        }
    } catch (error) {
        console.error("News API Error:", error);
        track.innerHTML = `<div class="text-white text-center w-100 pt-5">Failed to load news.</div>`;
    }
};

const initPartners = async () => {
  const track = document.getElementById("partners-track");

  // Safety Check
  if (!track) return;

  // --- 1. INJECT SKELETON LOADING ---
  // Kita buat 5 placeholder logo untuk mengisi track saat loading
  // Class 'placeholder' dari Bootstrap akan membuatnya berkedip (glow)
  const skeletonItem = `
    <div class="placeholder-glow">
        <span class="placeholder bg-secondary opacity-25 rounded" style="width: 150px; height: 50px; display: block;"></span>
    </div>
  `;
  // Ulangi 5 kali
  track.innerHTML = skeletonItem.repeat(5);

  try {
    // --- 2. FETCH REAL API ---
    const response = await fetch("http://inlet-lab.test/api/partners");

    if (!response.ok) throw new Error(`HTTP Error: ${response.status}`);

    const result = await response.json();

    if (result.success && Array.isArray(result.data) && result.data.length > 0) {
      const data = result.data;

      // --- 3. LOGIC INFINITE SCROLL ---
      // Duplicate data to create seamless infinite scroll (3x duplikasi)
      const items = [...data, ...data, ...data];

      // --- 4. RENDER DATA ---
      track.innerHTML = items
        .map((p) => {
          // Jika ada URL, bungkus dengan anchor tag, jika tidak gambar saja
          const imgHtml = `
            <img src="${p.partner_logo}" 
                 alt="${p.name}" 
                 class="partner-logo" 
                 title="${p.name}" 
                 loading="lazy" 
                 style="height: 50px; width: auto; object-fit: contain;">
          `;

          return p.url 
            ? `<a href="${p.url}" target="_blank" rel="noopener" class="d-inline-block">${imgHtml}</a>` 
            : imgHtml;
        })
        .join("");
        
    } else {
      // Jika tidak ada partner, kosongkan atau tampilkan pesan kecil
      track.innerHTML = `<span class="text-muted small">No partners yet.</span>`;
      // Hentikan animasi scroll jika kosong agar tidak aneh
      track.style.animation = "none";
    }
  } catch (error) {
    console.error("Partners API Fetch Failed:", error);
    track.innerHTML = ""; // Bersihkan skeleton jika error
    track.style.animation = "none";
  }
};

const initGallery = async () => {
  const grid = document.getElementById("gallery-grid");

  // Safety Check
  if (!grid) return;

  // --- 1. INJECT SKELETON LOADING ---
  // Membuat 6 item skeleton untuk mengisi grid (2 baris x 3 kolom di desktop)
  const skeletonItem = `
    <div class="col-6 col-md-4">
        <div class="gallery-item placeholder-glow" style="border-radius: 8px; overflow: hidden;">
            <span class="placeholder col-12 bg-secondary opacity-25" style="height: 250px; display: block;"></span>
        </div>
    </div>
  `;
  grid.innerHTML = skeletonItem.repeat(6);

  try {
    // --- 2. FETCH REAL API ---
    // Pastikan route ini benar dan server berjalan
    const response = await fetch("http://inlet-lab.test/api/gallery");

    if (!response.ok) {
        throw new Error(`HTTP Error: ${response.status} ${response.statusText}`);
    }

    const result = await response.json();

    if (result.success && Array.isArray(result.data)) {
      const data = result.data;

      // Jika data kosong
      if (data.length === 0) {
        grid.innerHTML = `<div class="col-12 text-center text-muted py-5">No gallery items found.</div>`;
        return;
      }

      // --- 3. RENDER DATA (Support Photo & Video) ---
      grid.innerHTML = data
        .map((item) => {
          // Ikon Play Overlay jika Video
          const videoOverlay = item.type === 'Video' 
            ? `<div class="position-absolute top-50 start-50 translate-middle text-white d-flex align-items-center justify-content-center" 
                    style="background:rgba(0,0,0,0.5); border-radius:50%; width:50px; height:50px; pointer-events:none; z-index: 2;">
                    <i class="bi bi-play-fill fs-2"></i>
               </div>` 
            : '';

          // Fallback image jika null (biasanya video youtube thumbnail sudah dihandle backend, tapi ini jaga-jaga)
          const imgSrc = item.image_name || 'https://placehold.co/600x400?text=No+Preview';

          return `
            <div class="col-6 col-md-4">
                <div class="gallery-item position-relative" role="button" tabindex="0" 
                     data-src="${imgSrc}" 
                     data-title="${item.title || ''}" 
                     data-desc="${item.description || ''}"
                     data-type="${item.type}"
                     data-video-url="${item.url || ''}">
                    
                    <img src="${imgSrc}" alt="${item.title}" class="rounded shadow-sm w-100 object-fit-cover" style="height: 250px;" loading="lazy">
                    ${videoOverlay}
                </div>
            </div>
        `;
        })
        .join("");

      // --- 4. LIGHTBOX LOGIC ---
      const lightbox = document.getElementById("lightbox");
      
      // Pastikan elemen lightbox ada sebelum dimanipulasi
      if (lightbox) {
          const lbImg = document.getElementById("lightbox-img");
          const lbTitle = document.getElementById("lightbox-title");
          const lbDesc = document.getElementById("lightbox-desc");
          const closeBtn = document.getElementById("lightbox-close");

          // Helper: Reset State (Hapus video lama)
          const resetLightbox = () => {
            const existingIframe = document.getElementById("lightbox-video-frame");
            if (existingIframe) existingIframe.remove();
            if (lbImg) lbImg.style.display = 'block'; // Tampilkan kembali holder gambar
          };

          const openLightbox = (target) => {
            resetLightbox(); // Bersihkan konten sebelumnya
            
            const type = target.dataset.type;
            const videoUrl = target.dataset.videoUrl;

            // Set Text
            if(lbTitle) lbTitle.textContent = target.dataset.title;
            if(lbDesc) lbDesc.textContent = target.dataset.desc;

            if (type === 'Video' && videoUrl) {
                // --- Handle Video ---
                if(lbImg) lbImg.style.display = 'none'; // Sembunyikan gambar utama
                
                // Convert Youtube URL ke format Embed
                let embedUrl = videoUrl;
                if (videoUrl.includes('watch?v=')) {
                    embedUrl = videoUrl.replace('watch?v=', 'embed/');
                    embedUrl = embedUrl.split('&')[0]; // Bersihkan parameter lain
                } else if (videoUrl.includes('youtu.be/')) {
                    embedUrl = videoUrl.replace('youtu.be/', 'youtube.com/embed/');
                }

                // Buat Iframe
                const iframe = document.createElement('iframe');
                iframe.id = 'lightbox-video-frame';
                iframe.src = embedUrl + "?autoplay=1";
                iframe.width = "100%";
                iframe.height = "400px";
                iframe.allow = "autoplay; encrypted-media; picture-in-picture";
                iframe.allowFullscreen = true;
                iframe.className = "rounded shadow-lg mb-3 bg-black"; // Tambah bg-black agar rapi
                
                // Masukkan iframe sebelum caption
                if(lbImg) lbImg.insertAdjacentElement('afterend', iframe);

            } else {
                // --- Handle Photo ---
                if(lbImg) {
                    lbImg.src = target.dataset.src;
                    lbImg.style.display = 'block';
                }
            }

            // Show Modal
            lightbox.classList.add("active");
            lightbox.setAttribute("aria-hidden", "false");
            if(closeBtn) closeBtn.focus();
            document.body.style.overflow = "hidden";
          };

          const closeLightbox = () => {
            lightbox.classList.remove("active");
            lightbox.setAttribute("aria-hidden", "true");
            document.body.style.overflow = "";
            resetLightbox(); // Stop video playback
          };

          // Event Delegation (Klik Item Grid)
          grid.onclick = (e) => {
            const item = e.target.closest(".gallery-item");
            if (item) openLightbox(item);
          };

          // Accessibility: Enter Key
          grid.onkeydown = (e) => {
            if (e.key === "Enter") {
              const item = e.target.closest(".gallery-item");
              if (item) openLightbox(item);
            }
          };

          // Controls
          if(closeBtn) closeBtn.onclick = closeLightbox;

          // Close on Escape
          document.onkeydown = (e) => {
            if (e.key === "Escape" && lightbox.classList.contains("active")) closeLightbox();
          };
          
          // Close on Click Outside
          lightbox.onclick = (e) => {
            if (e.target === lightbox) closeLightbox();
          };
      }

    } else {
      throw new Error(result.message || "Invalid API Data Structure");
    }
  } catch (error) {
    console.error("Gallery API Fetch Failed:", error);
    grid.innerHTML = `
        <div class="col-12 text-center text-muted py-5">
            <i class="bi bi-exclamation-triangle fs-1 text-warning mb-2 d-block"></i>
            <p>Failed to load gallery.</p>
            <small class="text-secondary">${error.message}</small>
        </div>`;
  }
};

const initMaps = async () => {
  const section = document.getElementById("maps-section");
  
  // Elements to update
  const emailEl = section.querySelector(".bi-envelope").closest(".d-flex").querySelector("span.fw-semibold");
  const phoneEl = section.querySelector(".bi-telephone").closest(".d-flex").querySelector("span.fw-semibold");
  const mapIframe = section.querySelector("iframe.map-iframe");
  const addressEl = section.querySelector(".floating-map-card p.small"); // Address in floating card
  
  // Safety Check
  if (!section) return;

  // --- 1. INJECT SKELETON LOADING ---
  // Simpan text asli untuk fallback
  const originalEmail = emailEl.innerText;
  const originalPhone = phoneEl.innerText;
  
  // Replace text dengan skeleton
  emailEl.innerHTML = `<span class="placeholder col-8 bg-secondary opacity-25"></span>`;
  phoneEl.innerHTML = `<span class="placeholder col-6 bg-secondary opacity-25"></span>`;
  if(addressEl) addressEl.innerHTML = `<span class="placeholder col-10 bg-secondary opacity-25"></span>`;
  
  // Map Skeleton overlay (karena iframe tidak bisa di-placeholder langsung dengan mudah)
  const mapWrapper = section.querySelector(".map-wrapper");
  const mapSkeleton = document.createElement("div");
  mapSkeleton.className = "position-absolute top-0 start-0 w-100 h-100 bg-light placeholder-glow d-flex align-items-center justify-content-center z-2";
  mapSkeleton.id = "map-skeleton";
  mapSkeleton.innerHTML = `<div class="spinner-border text-primary opacity-25" role="status"></div>`;
  mapWrapper.appendChild(mapSkeleton);

  try {
    // --- 2. FETCH REAL API ---
    // Pastikan route: Router::get('/api/site-settings', [HomeController::class, 'getSiteSettings']);
    const response = await fetch("http://inlet-lab.test/api/site-settings");

    if (!response.ok) throw new Error(`HTTP Error: ${response.status}`);

    const result = await response.json();

    if (result.success && result.data) {
      const data = result.data;

      // --- 3. UPDATE CONTENT ---
      
      // Update Email
      emailEl.innerText = data.email || originalEmail;
      
      // Update Phone
      phoneEl.innerText = data.phone || originalPhone;
      
      // Update Map Iframe Source
      if (mapIframe && data.map_src) {
        mapIframe.src = data.map_src;
        // Hapus skeleton saat iframe selesai load
        mapIframe.onload = () => {
            if(mapSkeleton) mapSkeleton.remove();
        };
      } else {
         if(mapSkeleton) mapSkeleton.remove(); // Remove anyway if no map
      }

      // Update Address in Floating Card
      if (addressEl) {
        addressEl.innerText = data.address || "Building A4, 2nd Floor, Science Park St.";
      }

      // Optional: Update 'Get Directions' link if you want it dynamic based on address
      const directionBtn = section.querySelector("a.btn-outline-primary");
      if(directionBtn && data.address) {
          directionBtn.href = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(data.address)}`;
      }

    } else {
      throw new Error("Data Settings tidak ditemukan");
    }
  } catch (error) {
    console.error("Site Settings API Fetch Failed:", error);
    
    // Restore Original / Remove Skeletons on Error
    emailEl.innerText = originalEmail;
    phoneEl.innerText = originalPhone;
    if(addressEl) addressEl.innerText = "Building A4, 2nd Floor, Science Park St.";
    if(mapSkeleton) mapSkeleton.remove();
  }
};

// --- 4. Init All ---
document.addEventListener("DOMContentLoaded", () => {
  initHeroSlider();
  initAbout();
  initResearch();
  initTeamCarousel();
  initFacilities();
  initProjects();
  initNews();
  initPartners();
  initGallery();
  initMaps();

  const navbar = document.querySelector(".navbar");
  const navLinks = document.querySelectorAll(".nav-link");
  const navbarCollapse = document.querySelector(".navbar-collapse");

  // --- 1. Sticky Navbar Logic (Sesuai CSS Asli) ---
  const handleScroll = () => {
    if (window.scrollY > 50) {
      navbar.classList.add("scrolled");
    } else {
      navbar.classList.remove("scrolled");
    }
  };
  window.addEventListener("scroll", handleScroll);

  // --- 2. Smooth Scroll & Mobile Menu Close ---
  navLinks.forEach((link) => {
    link.addEventListener("click", (e) => {
      e.preventDefault();

      // Ambil target ID
      const targetId = link.getAttribute("href");
      const targetSection = document.querySelector(targetId);

      if (targetSection) {
        // Smooth scroll ke section
        window.scrollTo({
          top: targetSection.offsetTop - 80, // Offset untuk navbar fixed
          behavior: "smooth",
        });

        // Tutup menu mobile jika terbuka (Bootstrap specific)
        if (navbarCollapse.classList.contains("show")) {
          const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse);
          if (bsCollapse) bsCollapse.hide();
        }
      }
    });
  });

  // --- 3. Scroll Spy (Active State Highlighter) ---
  const highlightMenu = () => {
    let scrollY = window.scrollY;

    // Loop semua section yang ada di menu
    navLinks.forEach((link) => {
      const sectionId = link.getAttribute("href");
      const section = document.querySelector(sectionId);

      if (section) {
        const sectionTop = section.offsetTop - 100; // Offset deteksi
        const sectionHeight = section.offsetHeight;

        if (scrollY >= sectionTop && scrollY < sectionTop + sectionHeight) {
          // Hapus class active dari semua link
          navLinks.forEach((n) => n.classList.remove("active"));
          // Tambah class active ke link saat ini
          link.classList.add("active");
        }
      }
    });
  };

  // Jalankan highlight saat scroll
  window.addEventListener("scroll", highlightMenu);

  const mapWrapper = document.querySelector(".map-wrapper");
  const floatCard = document.querySelector(".floating-map-card");

  if (mapWrapper && floatCard) {
    mapWrapper.addEventListener("mousemove", (e) => {
      const x = (window.innerWidth - e.pageX * 2) / 100;
      const y = (window.innerHeight - e.pageY * 2) / 100;

      floatCard.style.transform = `translateX(${x}px) translateY(${y}px)`;
    });

    mapWrapper.addEventListener("mouseleave", () => {
      floatCard.style.transform = "translateX(0) translateY(0)";
    });
  }
});
