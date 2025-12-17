const initFooter = async () => {
    const footer = document.getElementById("main-footer");
    if (!footer) return;

    const logoContainer = footer.querySelector(".footer-logo");
    const contactList = document.getElementById("footer-contact-list");
    const socialList = document.getElementById("footer-socials");
    const copyrightEl = document.getElementById("footer-copyright");

    try {
        const response = await fetch("/api/site-settings");
        if (!response.ok) throw new Error("Failed to fetch footer data");
        const result = await response.json();

        if (result.success && result.data) {
            const data = result.data;
            const year = new Date().getFullYear();
            const logoSrc = data.logo || "assets/logo/logo.png";
            const siteName = data.site_name || "InLET Lab";

            logoContainer.innerHTML = `
                <img src="${logoSrc}" alt="${siteName}" class="logo-footer" style="max-height: 50px;">
                <p class="footer-description text-white-50 text-justify">
                    Laboratorium riset dan inovasi teknologi pembelajaran yang berdedikasi menghadirkan solusi digital terdepan untuk mendukung transformasi pendidikan dan pengembangan akademik.
                </p>
            `;

            contactList.innerHTML = `
                <li>
                    <i class="bi bi-envelope me-2 text-primary"></i>
                    <a href="mailto:${data.email}" class="text-decoration-none text-white-50 hover-white">${data.email}</a>
                </li>
                <li>
                    <i class="bi bi-telephone me-2 text-primary"></i>
                    <a href="tel:${data.phone.replace(/[^0-9+]/g, '')}" class="text-decoration-none text-white-50 hover-white">${data.phone}</a>
                </li>
                <li>
                    <i class="bi bi-geo-alt me-2 text-primary"></i>
                    <span class="text-white-50">${data.address}</span>
                </li>
            `;

            let socialHTML = '';
            const socialData = data.social_media;

            if (socialData && typeof socialData === 'object') {
                const activeSocials = Object.entries(socialData).filter(([key, url]) => url && url.trim() !== "");

                if (activeSocials.length > 0) {
                    socialHTML = activeSocials.map(([platform, url]) => {
                        let finalUrl = url;
                        if (!url.startsWith("http")) {
                            finalUrl = "https://" + url;
                        }

                        return `
                            <a href="${finalUrl}" class="text-white-50 hover-white text-decoration-none" target="_blank" aria-label="${platform}">
                                <i class="bi bi-${platform.toLowerCase()} fs-5"></i>
                            </a>
                        `;
                    }).join('');
                }
            }

            if (socialHTML === '') {
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

            socialList.classList.remove("placeholder-glow");

            copyrightEl.innerHTML = `&copy; ${year} <strong>${siteName}</strong>. All rights reserved.`;
            copyrightEl.classList.remove("placeholder-glow");
        }
    } catch (error) {
        console.error("Footer Init Error:", error);
        
        if(logoContainer) {
             logoContainer.innerHTML += `
                <p class="footer-description text-white-50">
                    Platform terbaik untuk solusi riset dan teknologi pembelajaran dengan layanan terpercaya dan inovatif.
                </p>
            `;
        }
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
