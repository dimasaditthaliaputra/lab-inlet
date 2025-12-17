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
    const response = await fetch(`/api/hero-slider`);

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
    
    wrapper.innerHTML = `<div class="d-flex align-items-center justify-content-center h-100 w-100 bg-light text-muted">Failed to load slides.</div>`;
    return;
  }

  if (sliderData.length === 0) {
    
    wrapper.innerHTML = `<div class="d-flex align-items-center justify-content-center h-100 w-100 bg-light text-muted">No active slides found.</div>`;
    return;
  }

  
  const renderSlider = (data) => {
    let slidesHTML = "";
    let dotsHTML = "";

    data.forEach((slide, index) => {
      const isActive = index === 0 ? "active" : "";

      
      const btnHTML =
        slide.button_text && slide.button_text.trim() !== ""
          ? `<a href="${slide.button_url}" class="hero-btn mt-4">${slide.button_text}</a>`
          : "";

      
      slidesHTML += `
                <div class="hero-slide ${isActive}" data-index="${index}">
                    <div class="slide-overlay"></div>
                    <img src="${slide.image_name}" class="slide-bg" alt="${slide.title}" loading="${index === 0 ? "eager" : "lazy"
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

      
      dotsHTML += `<div class="dot ${isActive}" data-index="${index}"></div>`;
    });

    
    wrapper.innerHTML = slidesHTML;
    dotsContainer.innerHTML = dotsHTML;
  };

  
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

  
  const startAutoPlay = () => {
    clearInterval(autoPlayInterval);
    autoPlayInterval = setInterval(nextSlide, AUTO_PLAY_DELAY);
  };

  const resetTimer = () => {
    clearInterval(autoPlayInterval);
    startAutoPlay();
  };

  
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

  
  if (!contentContainer) return;

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
    
    const response = await fetch(`/api/about-us`);

    if (!response.ok) throw new Error(`HTTP Error: ${response.status}`);

    const result = await response.json();

    
    if (result.success && Array.isArray(result.data) && result.data.length > 0) {
      const data = result.data[0];

      
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

const additionalStyles = `
  .research-card {
    position: relative;
    overflow: hidden;
    transform-style: preserve-3d; 
    z-index: 1;
  }

  
  .research-card::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100px; 
    height: 100%;
    background: linear-gradient(
      to right,
      rgba(255, 255, 255, 0) 0%,
      rgba(255, 255, 255, 0.1) 1%,
      rgba(255, 255, 255, 0.6) 50%, 
      rgba(255, 255, 255, 0.1) 99%,
      rgba(255, 255, 255, 0) 100%
    );
    opacity: 0;
    transform: skewX(-25deg) translateX(-200%); 
    transition: opacity 0.3s;
    pointer-events: none;
    z-index: 10; 
  }

  
  .research-card:hover::after {
    opacity: 1;
    animation: mirror-sweep 1s ease-in-out forwards;
  }

  
  @keyframes mirror-sweep {
    0% {
      transform: skewX(-25deg) translateX(-200%);
    }
    100% {
      transform: skewX(-25deg) translateX(500%); 
    }
  }

  
  .research-card > * {
    position: relative;
    z-index: 20;
  }
`;

const styleSheet = document.createElement("style");
styleSheet.textContent = additionalStyles;
document.head.appendChild(styleSheet);

const addTiltEffect = () => {
  const cards = document.querySelectorAll('.research-card');

  cards.forEach(card => {
    card.addEventListener('mousemove', (e) => {
      const rect = card.getBoundingClientRect();
      const x = e.clientX - rect.left;
      const y = e.clientY - rect.top;

      const centerX = rect.width / 2;
      const centerY = rect.height / 2;

      const rotateX = (y - centerY) / centerY * -10;
      const rotateY = (x - centerX) / centerX * 10;

      card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.02, 1.02, 1.02)`;
    });

    card.addEventListener('mouseleave', () => {
      card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) scale3d(1, 1, 1)';
    });
  });
};

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
    const response = await fetch(`/api/research`);

    if (!response.ok) throw new Error(`HTTP Error: ${response.status}`);

    const result = await response.json();

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

      addTiltEffect();
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
  if (!container) return;

  
  const skeletonItem = `
        <div class="team-carousel-item">
            <div class="team-card">
                <div class="d-flex justify-content-center mb-4">
                    <div class="placeholder-glow" style="width: 110px; height: 110px;">
                        <span class="placeholder col-12 rounded-circle bg-secondary opacity-25 w-100 h-100 d-block"></span>
                    </div>
                </div>
                
                <h4 class="team-name placeholder-glow d-flex justify-content-center mb-2">
                    <span class="placeholder col-8 rounded" style="height: 20px;"></span>
                </h4>
                <div class="team-role placeholder-glow d-flex justify-content-center mb-3">
                    <span class="placeholder col-5 rounded" style="height: 15px;"></span>
                </div>
                
                <div class="team-divider opacity-25 mx-auto"></div>
                
                <div class="team-social placeholder-glow justify-content-center gap-2 mt-3">
                    <span class="placeholder rounded-circle bg-secondary opacity-10" style="width:36px; height:36px;"></span>
                    <span class="placeholder rounded-circle bg-secondary opacity-10" style="width:36px; height:36px;"></span>
                </div>
            </div>
        </div>
    `;

  container.innerHTML = skeletonItem.repeat(4);

  
  const createCard = (member) => {
    const imgUrl = member.image_name || "https://placehold.co/150x150?text=Member";
    
    const detailUrl = member.slug ? `/team?name=${member.slug}` : '#';

    return `
            <div class="team-carousel-item">
                <div class="team-card">
                    <div class="img-wrapper">
                        <a href="${detailUrl}" style="cursor: pointer; display: block; width: 100%; height: 100%;">
                            <img src="${imgUrl}" alt="${member.full_name}" loading="lazy">
                        </a>
                    </div>
                    
                    <h3 class="team-name">
                        <a href="${detailUrl}" style="text-decoration: none; color: inherit;">
                            ${member.full_name}
                        </a>
                    </h3>
                    
                    <p class="team-role">${member.lab_position || "Team Member"}</p>
                    
                    <div class="team-divider"></div>
                    
                    <div class="team-social">
                        ${(member.social || []).map(s => `
                            <a href="${s.url}" target="_blank" aria-label="${s.type}">
                                <i class="${s.icon_name}"></i>
                            </a>
                        `).join('')}
                    </div>
                </div>
            </div>
        `;
  };

  const renderInfiniteLoop = (data) => {
    let finalHTML = "";
    const loops = 4;
    for (let i = 0; i < loops; i++) {
      finalHTML += data.map(member => createCard(member)).join("");
    }
    container.innerHTML = finalHTML;
    container.style.animation = "";
  };

  try {
    
    const response = await fetch(`/api/team`);

    if (!response.ok) throw new Error(`Status: ${response.status}`);

    const result = await response.json();
    const teamData = result.data || result;

    if (Array.isArray(teamData) && teamData.length > 0) {
      renderInfiniteLoop(teamData);
    } else {
      throw new Error("No data available");
    }

  } catch (error) {
    console.warn("Using fallback team data due to error:", error);

    
    
    const fallbackData = [
      {
        full_name: "Dr. Sarah Lin",
        slug: "dr-sarah-lin",
        lab_position: "Lab Director",
        image_name: "https://placehold.co/150x150/png?text=SL",
        social: [{ type: "linkedin", icon_name: "bi bi-linkedin", url: "#" }]
      },
      {
        full_name: "James Doe",
        slug: "james-doe",
        lab_position: "Lead Researcher",
        image_name: "https://placehold.co/150x150/png?text=JD",
        social: [{ type: "twitter", icon_name: "bi bi-twitter-x", url: "#" }]
      },
      {
        full_name: "Anita Roy",
        slug: "anita-roy",
        lab_position: "Data Scientist",
        image_name: "https://placehold.co/150x150/png?text=AR",
        social: [{ type: "github", icon_name: "bi bi-github", url: "#" }]
      },
      {
        full_name: "Michael Chen",
        slug: "michael-chen",
        lab_position: "Engineer",
        image_name: "https://placehold.co/150x150/png?text=MC",
        social: [{ type: "linkedin", icon_name: "bi bi-linkedin", url: "#" }]
      }
    ];

    renderInfiniteLoop(fallbackData);
  }
};

const initFacilities = async () => {
  const container = document.getElementById("facilities-container");

  if (!container) return;

  
  const skeletonItem = `
    <div class="col-lg-6">
        <div class="facility-card placeholder-glow">
            <div class="facility-img-wrapper">
                <span class="placeholder w-100 h-100 bg-secondary opacity-25 d-block"></span>
            </div>
            <div class="flex-grow-1 w-100">
                <span class="placeholder col-6 mb-2 fw-bold"></span>
                <span class="placeholder col-12 mb-1 bg-secondary opacity-50"></span>
                <span class="placeholder col-8 bg-secondary opacity-50"></span>
            </div>
        </div>
    </div>
  `;
  container.innerHTML = skeletonItem.repeat(4);

  try {
    const response = await fetch(`/api/facilities`);
    if (!response.ok) throw new Error(`HTTP Error: ${response.status}`);
    const result = await response.json();

    if (result.success && Array.isArray(result.data)) {

      
      container.innerHTML = result.data.map((item) => {
        const isOperational = ["Operational", "good", "Good"].includes(item.condition);
        const badgeClass = isOperational ? "badge-soft-success" : "badge-soft-warning";
        const imgUrl = item.image_name || "https://placehold.co/400x300?text=No+Img";

        
        
        const safeDesc = item.description.replace(/"/g, '&quot;');
        const safeName = item.name.replace(/"/g, '&quot;');

        return `
            <div class="col-lg-6">
                <div class="facility-card" 
                     onclick="openFacilityModal('${imgUrl}', '${safeName}', '${safeDesc}', '${item.condition}', '${badgeClass}')">
                    
                    <div class="facility-img-wrapper">
                        <img src="${imgUrl}" alt="${item.name}" class="facility-img" loading="lazy">
                    </div>
                    
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h4 class="facility-title">${item.name}</h4>
                            <span class="badge ${badgeClass} rounded-pill border border-0">
                                ${item.condition}
                            </span>
                        </div>
                        <p class="facility-desc mb-0 line-clamp-2">${item.description}</p>
                    </div>
                </div>
            </div>
            `;
      }).join("");

    } else {
      container.innerHTML = `<div class="col-12 text-center text-muted">No facilities found.</div>`;
    }
  } catch (error) {
    console.error("Facilities API Error:", error);
    container.innerHTML = `<div class="col-12 text-center text-muted">Failed to load facilities.</div>`;
  }
};

window.openFacilityModal = (img, title, desc, condition, badgeClass) => {
  const modalImg = document.getElementById('modalFacilityImg');
  const modalTitle = document.getElementById('modalFacilityTitle');
  const modalDesc = document.getElementById('modalFacilityDesc');
  const modalBadge = document.getElementById('modalFacilityBadge');

  modalImg.src = img;
  modalTitle.textContent = title;
  modalDesc.textContent = desc;

  
  modalBadge.className = `badge ${badgeClass} rounded-pill fs-6`;
  modalBadge.textContent = condition;

  
  const myModal = new bootstrap.Modal(document.getElementById('facilityModal'));
  myModal.show();
};

const initProjects = async () => {
  const filterContainer = document.getElementById("project-filters");
  const grid = document.getElementById("projects-grid");
  const paginationContainer = document.getElementById("project-pagination");
  const prevBtn = document.getElementById("proj-prev");
  const nextBtn = document.getElementById("proj-next");
  const pageInfo = document.getElementById("proj-page-info");

  
  const ITEMS_PER_PAGE = 6;
  let currentPage = 1;
  let currentFilterId = 'all';
  let allProjects = []; 
  let categories = [];

  if (!grid || !filterContainer) return;

  
  const renderSkeleton = () => {
    const skeletonHTML = `
            <div class="col-md-6 col-lg-4">
                <div class="project-card skeleton-card h-100 border-0 rounded-4 overflow-hidden">
                    <div class="ratio ratio-4x3 bg-light placeholder-glow"></div>
                    <div class="p-4">
                        <span class="placeholder col-4 mb-2"></span>
                        <h5 class="placeholder col-8 mb-2"></h5>
                        <p class="placeholder col-12"></p>
                    </div>
                </div>
            </div>
        `.repeat(3);
    grid.innerHTML = skeletonHTML;
  };

  renderSkeleton();

  try {
    
    const response = await fetch(`/api/projects`);
    if (!response.ok) throw new Error("API Error");
    const result = await response.json();

    if (result.success && result.data) {
      allProjects = result.data.items;
      categories = result.data.categories;

      
      filterContainer.innerHTML = `
                <button class="filter-btn active" data-id="all">All Projects</button>
                ${categories.map(c => `
                    <button class="filter-btn" data-id="${c.id}">${c.name}</button>
                `).join('')}
            `;

      
      filterContainer.addEventListener('click', (e) => {
        if (e.target.classList.contains('filter-btn')) {
          
          document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
          e.target.classList.add('active');

          
          currentFilterId = e.target.dataset.id;
          currentPage = 1; 
          updateDisplay();
        }
      });

      
      prevBtn.addEventListener('click', () => {
        if (currentPage > 1) {
          currentPage--;
          updateDisplay();
        }
      });

      nextBtn.addEventListener('click', () => {
        const filtered = getFilteredProjects();
        const totalPages = Math.ceil(filtered.length / ITEMS_PER_PAGE);
        if (currentPage < totalPages) {
          currentPage++;
          updateDisplay();
        }
      });

      
      updateDisplay();
      paginationContainer.classList.remove('d-none');

    }
  } catch (error) {
    console.error(error);
    grid.innerHTML = `<div class="col-12 text-center text-muted">Failed to load projects.</div>`;
  }

  
  function getFilteredProjects() {
    if (currentFilterId === 'all') return allProjects;
    
    
    const targetId = parseInt(currentFilterId);
    return allProjects.filter(p => p.category_ids.includes(targetId));
  }

  
  function updateDisplay() {
    const filtered = getFilteredProjects();
    const totalItems = filtered.length;
    const totalPages = Math.ceil(totalItems / ITEMS_PER_PAGE);

    
    if (totalItems === 0) {
      paginationContainer.classList.add('d-none');
      grid.innerHTML = `<div class="col-12 text-center text-muted py-5">No projects found in this category.</div>`;
      return;
    } else {
      paginationContainer.classList.remove('d-none');
    }

    prevBtn.disabled = currentPage === 1;
    nextBtn.disabled = currentPage === totalPages;
    pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;

    
    const start = (currentPage - 1) * ITEMS_PER_PAGE;
    const end = start + ITEMS_PER_PAGE;
    const pageItems = filtered.slice(start, end);

    
    
    
    

    grid.innerHTML = pageItems.map(item => {
      const slug = item.name.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '');
      const imgUrl = item.image_url && item.image_url !== ""
        ? item.image_url
        : "https://placehold.co/600x400/f8f9fa/adb5bd?text=Project";

      
      const itemCats = item.category_ids.map(id => {
        const cat = categories.find(c => c.id === id);
        return cat ? `<span class="badge bg-white text-dark border shadow-sm">${cat.name}</span>` : '';
      }).join(' ');

      return `
                <div class="col-md-6 col-lg-4 fade-in">
                    <a href="/projects/${slug}" class="project-card d-block text-decoration-none h-100">
                        <div class="project-img-box">
                            <img src="${imgUrl}" alt="${item.name}" loading="lazy">
                            <div class="project-overlay">
                                <span class="btn btn-light rounded-circle"><i class="bi bi-arrow-up-right"></i></span>
                            </div>
                        </div>
                        <div class="project-body mt-3">
                            <div class="mb-2 d-flex gap-1 flex-wrap">
                                ${itemCats}
                            </div>
                            <h4 class="project-title text-dark fw-bold mb-2">${item.name}</h4>
                            <p class="text-muted small line-clamp-2">${item.description}</p>
                        </div>
                    </a>
                </div>
            `;
    }).join('');
  }
};

const initNews = async () => {
  const track = document.getElementById("news-track");
  const dotsContainer = document.getElementById("news-indicators");
  const prevBtn = document.getElementById("news-prev");
  const nextBtn = document.getElementById("news-next");

  if (!track) return;

  
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
    
    const response = await fetch(`/api/news`);
    if (!response.ok) throw new Error("Network Error");
    const result = await response.json();

    if (result.success && Array.isArray(result.data)) {
      let newsData = result.data;

      if (newsData.length === 0) {
        track.innerHTML = `<div class="text-white text-center w-100 pt-5">No news available.</div>`;
        return;
      }

      
      track.innerHTML = ""; 
      dotsContainer.innerHTML = "";

      
      newsData.forEach((n, index) => {
        const imgUrl = n.image_name || "https://placehold.co/400x300?text=News";
        const date = new Date(n.publish_date).toLocaleDateString('en-US', { day: 'numeric', month: 'short', year: 'numeric' });

        const card = document.createElement("div");
        card.className = "news-card"; 
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

        
        const dot = document.createElement("div");
        dot.className = "news-dot";
        dot.addEventListener("click", () => updateCarousel(index));
        dotsContainer.appendChild(dot);
      });

      
      const items = document.querySelectorAll(".news-card");
      const dots = document.querySelectorAll(".news-dot");
      const totalItems = items.length;
      let currentIndex = 0;

      const updateCarousel = (newIndex) => {
        
        if (newIndex < 0) newIndex = totalItems - 1;
        if (newIndex >= totalItems) newIndex = 0;

        currentIndex = newIndex;

        
        
        const prevIndex = (currentIndex - 1 + totalItems) % totalItems;
        const nextIndex = (currentIndex + 1) % totalItems;

        
        items.forEach(item => {
          item.className = "news-card"; 
        });

        dots.forEach(dot => dot.classList.remove("active"));

        
        
        items[currentIndex].classList.add("active");

        
        
        if (totalItems > 1) items[prevIndex].classList.add("prev");

        
        
        if (totalItems > 2) items[nextIndex].classList.add("next");

        
        if (dots[currentIndex]) dots[currentIndex].classList.add("active");
      };

      
      nextBtn.addEventListener("click", () => updateCarousel(currentIndex + 1));
      prevBtn.addEventListener("click", () => updateCarousel(currentIndex - 1));

      
      let touchStartX = 0;
      track.addEventListener("touchstart", e => touchStartX = e.touches[0].clientX);
      track.addEventListener("touchend", e => {
        const touchEndX = e.changedTouches[0].clientX;
        if (touchStartX - touchEndX > 50) updateCarousel(currentIndex + 1); 
        if (touchEndX - touchStartX > 50) updateCarousel(currentIndex - 1); 
      });

      
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

  if (!track) return;

  const skeletonItem = `
    <div class="placeholder-glow">
        <span class="placeholder bg-secondary opacity-25 rounded" style="width: 150px; height: 50px; display: block;"></span>
    </div>
  `;
  
  track.innerHTML = skeletonItem.repeat(5);

  try {
    
    const response = await fetch(`/api/partners`);

    if (!response.ok) throw new Error(`HTTP Error: ${response.status}`);

    const result = await response.json();

    if (result.success && Array.isArray(result.data) && result.data.length > 0) {
      const data = result.data;

      
      
      const items = [...data, ...data, ...data];

      
      track.innerHTML = items
        .map((p) => {
          
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
      
      track.innerHTML = `<span class="text-muted small">No partners yet.</span>`;
      
      track.style.animation = "none";
    }
  } catch (error) {
    console.error("Partners API Fetch Failed:", error);
    track.innerHTML = ""; 
    track.style.animation = "none";
  }
};

const initGallery = async () => {
  const grid = document.getElementById("gallery-grid");

  
  if (!grid) return;

  
  
  const skeletonItem = `
    <div class="col-6 col-md-4">
        <div class="gallery-item placeholder-glow" style="border-radius: 8px; overflow: hidden;">
            <span class="placeholder col-12 bg-secondary opacity-25" style="height: 250px; display: block;"></span>
        </div>
    </div>
  `;
  grid.innerHTML = skeletonItem.repeat(6);

  try {
    
    
    const response = await fetch(`/api/gallery`);

    if (!response.ok) {
      throw new Error(`HTTP Error: ${response.status} ${response.statusText}`);
    }

    const result = await response.json();

    if (result.success && Array.isArray(result.data)) {
      const data = result.data;

      
      if (data.length === 0) {
        grid.innerHTML = `<div class="col-12 text-center text-muted py-5">No gallery items found.</div>`;
        return;
      }

      
      grid.innerHTML = data
        .map((item) => {
          
          const videoOverlay = item.type === 'Video'
            ? `<div class="position-absolute top-50 start-50 translate-middle text-white d-flex align-items-center justify-content-center" 
                    style="background:rgba(0,0,0,0.5); border-radius:50%; width:50px; height:50px; pointer-events:none; z-index: 2;">
                    <i class="bi bi-play-fill fs-2"></i>
               </div>`
            : '';

          
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

      
      const lightbox = document.getElementById("lightbox");

      
      if (lightbox) {
        const lbImg = document.getElementById("lightbox-img");
        const lbTitle = document.getElementById("lightbox-title");
        const lbDesc = document.getElementById("lightbox-desc");
        const closeBtn = document.getElementById("lightbox-close");

        
        const resetLightbox = () => {
          const existingIframe = document.getElementById("lightbox-video-frame");
          if (existingIframe) existingIframe.remove();
          if (lbImg) lbImg.style.display = 'block'; 
        };

        const openLightbox = (target) => {
          resetLightbox(); 

          const type = target.dataset.type;
          const videoUrl = target.dataset.videoUrl;

          
          if (lbTitle) lbTitle.textContent = target.dataset.title;
          if (lbDesc) lbDesc.textContent = target.dataset.desc;

          if (type === 'Video' && videoUrl) {
            
            if (lbImg) lbImg.style.display = 'none'; 

            
            let embedUrl = videoUrl;
            if (videoUrl.includes('watch?v=')) {
              embedUrl = videoUrl.replace('watch?v=', 'embed/');
              embedUrl = embedUrl.split('&')[0]; 
            } else if (videoUrl.includes('youtu.be/')) {
              embedUrl = videoUrl.replace('youtu.be/', 'youtube.com/embed/');
            }

            
            const iframe = document.createElement('iframe');
            iframe.id = 'lightbox-video-frame';
            iframe.src = embedUrl + "?autoplay=1";
            iframe.width = "100%";
            iframe.height = "400px";
            iframe.allow = "autoplay; encrypted-media; picture-in-picture";
            iframe.allowFullscreen = true;
            iframe.className = "rounded shadow-lg mb-3 bg-black"; 

            
            if (lbImg) lbImg.insertAdjacentElement('afterend', iframe);

          } else {
            
            if (lbImg) {
              lbImg.src = target.dataset.src;
              lbImg.style.display = 'block';
            }
          }

          
          lightbox.classList.add("active");
          lightbox.setAttribute("aria-hidden", "false");
          if (closeBtn) closeBtn.focus();
          document.body.style.overflow = "hidden";
        };

        const closeLightbox = () => {
          lightbox.classList.remove("active");
          lightbox.setAttribute("aria-hidden", "true");
          document.body.style.overflow = "";
          resetLightbox(); 
        };

        
        grid.onclick = (e) => {
          const item = e.target.closest(".gallery-item");
          if (item) openLightbox(item);
        };

        
        grid.onkeydown = (e) => {
          if (e.key === "Enter") {
            const item = e.target.closest(".gallery-item");
            if (item) openLightbox(item);
          }
        };

        
        if (closeBtn) closeBtn.onclick = closeLightbox;

        
        document.onkeydown = (e) => {
          if (e.key === "Escape" && lightbox.classList.contains("active")) closeLightbox();
        };

        
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

const initProducts = async () => {
  const grid = document.getElementById("product-grid");
  if (!grid) return;

  const skeletonItem = `
    <div class="tech-tile placeholder-glow" style="cursor: default; transform: none; background: #111;">
        <div class="tile-img-wrapper">
            <span class="placeholder w-100 h-100 bg-secondary opacity-10"></span>
        </div>
        <div class="tile-content" style="transform: none;">
            <span class="placeholder col-3 mb-3 bg-secondary opacity-25 rounded-pill"></span>
            <span class="placeholder col-8 mb-2 bg-secondary opacity-25 d-block"></span>
            <span class="placeholder col-12 bg-secondary opacity-10"></span>
            <span class="placeholder col-10 bg-secondary opacity-10"></span>
        </div>
    </div>
  `;
  grid.innerHTML = skeletonItem.repeat(2);

  try {
    const response = await fetch(`/api/products`);

    if (!response.ok) throw new Error(`HTTP Error: ${response.status}`);

    const result = await response.json();

    let products = [];
    if (result.success) {
      if (result.data && Array.isArray(result.data.items)) {
        products = result.data.items;
      } else if (Array.isArray(result.data)) {
        products = result.data;
      }
    }

    if (products.length > 0) {
      grid.innerHTML = products.map(item => {
        let dateStr = "Coming Soon";
        if (item.release_date || item.created_at) {
          const d = new Date(item.release_date || item.created_at);
          dateStr = d.toLocaleDateString("en-US", { month: 'short', year: 'numeric' });
        }

        const imgName = item.image_url || item.image_name || "";
        const imgSrc = imgName.startsWith('http') ? imgName : `uploads/products/${imgName}`;
        const finalImg = (imgName === "") ? "https://placehold.co/600x400/111/333?text=Product" : imgSrc;

        return `
            <div class="tech-tile" data-tilt>
                <div class="tile-img-wrapper">
                    <img src="${finalImg}" alt="${item.product_name || item.name}" loading="lazy">
                </div>
                
                <div class="tile-shine"></div>
                <div class="tile-overlay"></div>

                <div class="tile-content">
                    <span class="product-date">${dateStr}</span>
                    <h3 class="product-title">${item.product_name || item.name}</h3>
                    <p class="product-desc">${item.description}</p>
                </div>
            </div>
        `;
      }).join('');

      if (window.matchMedia("(hover: hover)").matches) {
        const tiles = document.querySelectorAll('.tech-tile');

        tiles.forEach(tile => {
          const shine = tile.querySelector('.tile-shine');

          tile.addEventListener('mousemove', (e) => {
            const rect = tile.getBoundingClientRect();
            const width = rect.width;
            const height = rect.height;

            const mouseX = e.clientX - rect.left;
            const mouseY = e.clientY - rect.top;

            const xPct = mouseX / width - 0.5;
            const yPct = mouseY / height - 0.5;

            const rotateX = yPct * -15;
            const rotateY = xPct * 15;

            tile.style.transition = 'none';
            tile.style.transform = `rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.02, 1.02, 1.02)`;

            if (shine) {
              shine.style.setProperty('--shine-x', `${mouseX}px`);
              shine.style.setProperty('--shine-y', `${mouseY}px`);
            }
          });

          tile.addEventListener('mouseleave', () => {
            tile.style.transition = 'transform 0.5s cubic-bezier(0.23, 1, 0.32, 1)';
            tile.style.transform = 'rotateX(0) rotateY(0) scale3d(1, 1, 1)';
          });
        });
      }

    } else {
      grid.innerHTML = `<div class="col-12 text-center text-white-50 py-5">No products found.</div>`;
    }

  } catch (error) {
    console.error("Products API Fetch Failed:", error);
    grid.innerHTML = `<div class="col-12 text-center text-danger py-5">Failed to load products.</div>`;
  }
};

const initMaps = async () => {
  const section = document.getElementById("maps-section");

  
  const emailEl = section.querySelector(".bi-envelope").closest(".d-flex").querySelector("span.fw-semibold");
  const phoneEl = section.querySelector(".bi-telephone").closest(".d-flex").querySelector("span.fw-semibold");
  const mapIframe = section.querySelector("iframe.map-iframe");
  const addressEl = section.querySelector(".floating-map-card p.small");

  
  if (!section) return;

  
  const originalEmail = emailEl.innerText;
  const originalPhone = phoneEl.innerText;

  
  emailEl.innerHTML = `<span class="placeholder col-8 bg-secondary opacity-25"></span>`;
  phoneEl.innerHTML = `<span class="placeholder col-6 bg-secondary opacity-25"></span>`;
  if (addressEl) addressEl.innerHTML = `<span class="placeholder col-10 bg-secondary opacity-25"></span>`;

  
  const mapWrapper = section.querySelector(".map-wrapper");
  const mapSkeleton = document.createElement("div");
  mapSkeleton.className = "position-absolute top-0 start-0 w-100 h-100 bg-light placeholder-glow d-flex align-items-center justify-content-center z-2";
  mapSkeleton.id = "map-skeleton";
  mapSkeleton.innerHTML = `<div class="spinner-border text-primary opacity-25" role="status"></div>`;
  mapWrapper.appendChild(mapSkeleton);

  try {
    
    const response = await fetch(`/api/site-settings`);

    if (!response.ok) throw new Error(`HTTP Error: ${response.status}`);

    const result = await response.json();

    if (result.success && result.data) {
      const data = result.data;

      
      emailEl.innerText = data.email || originalEmail;

      phoneEl.innerText = data.phone || originalPhone;

      if (mapIframe && data.map_src) {
        mapIframe.src = data.map_src;
        mapIframe.onload = () => {
          if (mapSkeleton) mapSkeleton.remove();
        };
      } else {
        if (mapSkeleton) mapSkeleton.remove();
      }

      if (addressEl) {
        addressEl.innerText = data.address || "Building A4, 2nd Floor, Science Park St.";
      }

      const directionBtn = section.querySelector("a.btn-outline-primary");
      if (directionBtn && data.map_src) {
        let destinationQuery = "";

        
        
        
        const matchAddress = data.map_src.match(/!2s([^!]+)/);

        if (matchAddress && matchAddress[1]) {
          
          destinationQuery = matchAddress[1];
        }
        
        else {
          const matchLat = data.map_src.match(/!3d([-0-9.]+)/);
          const matchLng = data.map_src.match(/!2d([-0-9.]+)/);

          if (matchLat && matchLng) {
            destinationQuery = `${matchLat[1]},${matchLng[1]}`;
          }
        }

        
        if (!destinationQuery && data.address) {
          destinationQuery = encodeURIComponent(data.address);
        }

        
        if (destinationQuery) {
          
          directionBtn.href = `https://www.google.com/maps/search/?api=1&query=${destinationQuery}`;
          directionBtn.target = "_blank"; 
        }
      }

    } else {
      throw new Error("Data Settings tidak ditemukan");
    }
  } catch (error) {
    console.error("Site Settings API Fetch Failed:", error);

    
    emailEl.innerText = originalEmail;
    phoneEl.innerText = originalPhone;
    if (addressEl) addressEl.innerText = "Building A4, 2nd Floor, Science Park St.";
    if (mapSkeleton) mapSkeleton.remove();
  }
};


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
  initProducts();
  initMaps();

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