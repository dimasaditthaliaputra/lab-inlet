const initFooter = async () => {
    const footer = document.getElementById("main-footer");
    if (!footer) return;

    const logoContainer = footer.querySelector(".footer-logo");
    const descEl = footer.querySelector(".footer-description");
    const contactList = document.getElementById("footer-contact-list");
    const socialList = document.getElementById("footer-socials");
    const copyrightEl = document.getElementById("footer-copyright");

    try {
        const response = await fetch("http://inlet-lab.test/api/site-settings");
        if (!response.ok) throw new Error("Failed to fetch footer data");
        const result = await response.json();

        if (result.success && result.data) {
            const data = result.data;
            const year = new Date().getFullYear();

            const logoSrc = data.logo || "assets/logo/logo.png"; 
            const siteName = data.site_name || "InLET Lab";
            
            logoContainer.innerHTML = `
                <img src="${logoSrc}" alt="${siteName}" class="logo-footer">
                <h5 class="text-white mt-3 mb-2 fw-bold">${siteName}</h5>
            `;
            
            descEl.innerHTML = `Platform terbaik untuk solusi riset dan teknologi pembelajaran dengan layanan terpercaya dan inovatif.`;

            contactList.innerHTML = `
                <li>
                    <i class="bi bi-envelope me-2 text-primary"></i>
                    <a href="mailto:${data.email}">${data.email}</a>
                </li>
                <li>
                    <i class="bi bi-telephone me-2 text-primary"></i>
                    <a href="tel:${data.phone.replace(/[^0-9+]/g, '')}">${data.phone}</a>
                </li>
                <li>
                    <i class="bi bi-geo-alt me-2 text-primary"></i>
                    <span>${data.address}</span>
                </li>
            `;

            let socialHTML = '';
            if (Array.isArray(data.social_media) && data.social_media.length > 0) {
                 
            } else {
                const defaultSocials = [
                    { icon: 'youtube', url: '#' },
                    { icon: 'instagram', url: '#' },
                    { icon: 'linkedin', url: '#' }
                ];
                
                socialHTML = defaultSocials.map(s => `
                    <a href="${s.url}" class="text-white-50 hover-white text-decoration-none" target="_blank">
                        <i class="bi bi-${s.icon} fs-5"></i>
                    </a>
                `).join('');
            }
            socialList.innerHTML = socialHTML;

            copyrightEl.innerHTML = `&copy; ${year} <strong>${siteName}</strong>. All rights reserved.`;

        }
    } catch (error) {
        console.error("Footer Init Error:", error);
        descEl.innerText = "InLET Lab - Innovating Learning Technology.";
    }
};

document.addEventListener("DOMContentLoaded", () => {
  initFooter();

  const navbar = document.querySelector(".navbar");
  const navLinks = document.querySelectorAll(".nav-link");
  const navbarCollapse = document.querySelector(".navbar-collapse");

  const handleScroll = () => {
    if (window.scrollY > 50) {
      navbar.classList.add("scrolled");
    } else {
      navbar.classList.remove("scrolled");
    }
  };
  window.addEventListener("scroll", handleScroll);

  navLinks.forEach((link) => {
    link.addEventListener("click", (e) => {
      const targetId = link.getAttribute("href");
      
      if (targetId.startsWith("#")) {
          e.preventDefault();
          
          const targetSection = document.querySelector(targetId);

          if (targetSection) {
              window.scrollTo({
                  top: targetSection.offsetTop - 80, 
                  behavior: "smooth",
              });

              if (navbarCollapse.classList.contains("show")) {
                  const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse);
                  if (bsCollapse) bsCollapse.hide();
              }
          }
      }
    });
  });

  const highlightMenu = () => {
    let scrollY = window.scrollY;

    navLinks.forEach((n) => n.classList.remove("active"));
    
    navLinks.forEach((link) => {
        const sectionId = link.getAttribute("href");
        let section;
        
        if (!sectionId.startsWith("#")) {
            if (sectionId === '<?= base_url() ?>' || sectionId === '/') {
                const aboutSection = document.querySelector("#about-us");
                if (aboutSection && scrollY < aboutSection.offsetTop - 80) {
                     link.classList.add("active");
                }
            }
            return; 
        }

        section = document.querySelector(sectionId); 

        if (section) {
            const sectionTop = section.offsetTop - 100;
            const sectionHeight = section.offsetHeight;

            if (scrollY >= sectionTop && scrollY < sectionTop + sectionHeight) {
                link.classList.add("active");
            }
        }
    });
  };

  window.addEventListener("scroll", highlightMenu);
});