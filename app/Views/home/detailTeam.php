<?php ob_start() ?>
<style>
    :root {
        --primary-glass: rgba(37, 99, 235, 0.85);
        --bg-blob-1: #e0c3fc;
        --bg-blob-2: #8ec5fc;
    }

    /* --- GLOBAL ANIMATIONS --- */
    @keyframes floatUp {
        0% { opacity: 0; transform: translateY(40px) scale(0.98); }
        100% { opacity: 1; transform: translateY(0) scale(1); }
    }

    @keyframes blobMove {
        0% { transform: translate(0, 0) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
        100% { transform: translate(0, 0) scale(1); }
    }

    /* --- PAGE STRUCTURE --- */
    #team-detail-page {
        position: relative;
        width: 100%;
        min-height: 100vh;
        padding-top: 140px; /* Space for Navbar */
        padding-bottom: 100px;
        background-color: #f8fafc;
        overflow-x: hidden;
    }

    /* --- DECORATIVE BLOBS --- */
    .blob-bg {
        position: absolute;
        filter: blur(80px);
        z-index: 0;
        opacity: 0.6;
        animation: blobMove 10s infinite alternate ease-in-out;
    }
    .blob-1 { top: 10%; left: -10%; width: 500px; height: 500px; background: var(--bg-blob-1); border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%; }
    .blob-2 { bottom: 10%; right: -10%; width: 600px; height: 600px; background: var(--bg-blob-2); border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%; }

    /* --- MAIN CONTAINER --- */
    .container-modern {
        position: relative;
        z-index: 1;
        width: 90%;
        max-width: 1280px;
        margin: 0 auto;
    }

    /* --- GLASS CARD MAIN --- */
    .glass-panel {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255, 255, 255, 0.8);
        border-radius: 32px;
        box-shadow: 
            0 4px 6px -1px rgba(0, 0, 0, 0.02),
            0 20px 40px -4px rgba(0, 0, 0, 0.05);
        opacity: 0; /* Initial state for animation */
        animation: floatUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    /* --- PROFILE IMAGE --- */
    .profile-wrapper {
        position: relative;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(37, 99, 235, 0.15);
        transition: all 0.4s ease;
        aspect-ratio: 1/1;
    }
    
    .profile-wrapper:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 50px rgba(37, 99, 235, 0.25);
    }

    .profile-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.7s ease;
    }

    .profile-wrapper:hover .profile-img {
        transform: scale(1.05);
    }

    /* --- TYPOGRAPHY & TAGS --- */
    .text-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        font-weight: 700;
        color: #94a3b8;
        margin-bottom: 0.5rem;
        display: block;
    }

    .info-card {
        background: rgba(255, 255, 255, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.6);
        border-radius: 20px;
        padding: 1.5rem;
        transition: all 0.3s ease;
        height: 100%;
    }

    .info-card:hover {
        background: rgba(255, 255, 255, 0.9);
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        border-color: #fff;
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px dashed rgba(0,0,0,0.1);
    }

    .section-header i {
        font-size: 1.25rem;
        color: var(--primary-color);
        background: rgba(37, 99, 235, 0.1);
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
    }

    .section-header h5 {
        font-weight: 700;
        margin: 0;
        color: #1e293b;
    }

    /* --- LISTS --- */
    .custom-list {
        list-style: none;
        padding: 0;
    }

    .custom-list li {
        position: relative;
        padding-left: 24px;
        margin-bottom: 12px;
        color: #475569;
    }

    .custom-list li::before {
        content: '';
        position: absolute;
        left: 0;
        top: 8px;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
    }

    /* --- SOCIAL & CONTACT --- */
    .social-link {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        background: #fff;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        border: 1px solid rgba(0,0,0,0.05);
        text-decoration: none;
    }

    .social-link:hover {
        background: var(--primary-color);
        color: #fff;
        transform: translateY(-5px) rotate(8deg);
        box-shadow: 0 10px 20px rgba(37, 99, 235, 0.3);
    }

    .contact-row {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 12px;
        background: #fff;
        border-radius: 16px;
        margin-bottom: 10px;
        transition: 0.3s;
    }
    
    .contact-row:hover {
        background: #f1f5f9;
    }

    /* --- RESPONSIVE FIXES --- */
    @media (max-width: 991px) {
        #team-detail-page { padding-top: 100px; }
        .sidebar-sticky { position: relative !important; top: 0 !important; margin-bottom: 40px; text-align: center; }
        .sidebar-sticky .d-flex.justify-content-start { justify-content: center !important; }
        .contact-row { text-align: left; }
    }
</style>
<?php $pageStyle = ob_get_clean(); ?>

<section id="team-detail-page">
    <div class="blob-bg blob-1"></div>
    <div class="blob-bg blob-2"></div>

    <div class="container-modern">
        
        <div id="loading" class="text-center py-5 d-none">
            <div class="spinner-grow text-primary" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 text-muted fw-bold animate-pulse">Fetching Profile...</p>
        </div>

        <div id="error-state" class="glass-panel p-5 text-center d-none mx-auto" style="max-width: 600px;">
            <i class="bi bi-emoji-frown text-muted display-1 mb-4 d-block"></i>
            <h3 class="fw-bold text-dark">Member Not Found</h3>
            <p class="text-muted mb-4">We couldn't retrieve the data for this team member.</p>
            <a href="/" class="btn btn-primary rounded-pill px-5 py-3 fw-bold">Return Home</a>
        </div>

        <div id="member-detail" class="glass-panel p-4 p-md-5 d-none">
            <div class="row g-5">
                
                <div class="col-lg-4">
                    <div class="sidebar-sticky" style="position: sticky; top: 120px;">
                        
                        <div class="profile-wrapper mb-4 mx-auto mx-lg-0" style="max-width: 320px;">
                            <img src="" id="img-profile" alt="Profile" class="profile-img">
                        </div>

                        <div class="mb-4">
                            <h1 class="fw-bolder text-dark mb-2" id="text-name" style="font-size: 2.2rem; line-height: 1.1;"></h1>
                            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 bg-primary bg-opacity-10 text-primary rounded-pill fw-bold small mt-1">
                                <span id="text-role"></span>
                            </div>
                        </div>

                        <div class="d-flex justify-content-start gap-3 mb-4 flex-wrap" id="social-container"></div>

                        <div class="mt-4">
                            <h6 class="text-label mb-3">CONTACT INFO</h6>
                            
                            <div class="contact-row">
                                <div class="bg-light p-2 rounded-circle text-primary">
                                    <i class="bi bi-envelope fs-5"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <span class="d-block text-xs text-muted text-uppercase fw-bold" style="font-size: 0.65rem;">Email Address</span>
                                    <span class="fw-medium text-dark text-break" id="text-email"></span>
                                </div>
                            </div>

                            <div class="contact-row">
                                <div class="bg-light p-2 rounded-circle text-primary">
                                    <i class="bi bi-building fs-5"></i>
                                </div>
                                <div>
                                    <span class="d-block text-xs text-muted text-uppercase fw-bold" style="font-size: 0.65rem;">Office</span>
                                    <span class="fw-medium text-dark" id="text-office"></span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-lg-8">
                    
                    <div class="row g-3 mb-5">
                        <div class="col-md-4 col-sm-6">
                            <div class="info-card d-flex flex-column justify-content-center">
                                <span class="text-label text-primary mb-1">NIP (Employee ID)</span>
                                <span class="fw-bold fs-5 text-dark" id="text-nip">-</span>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="info-card d-flex flex-column justify-content-center">
                                <span class="text-label text-primary mb-1">NIDN (Lecturer ID)</span>
                                <span class="fw-bold fs-5 text-dark" id="text-nidn">-</span>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="info-card d-flex flex-column justify-content-center">
                                <span class="text-label text-primary mb-1">Academic Position</span>
                                <span class="fw-bold fs-5 text-dark" id="text-academic">-</span>
                            </div>
                        </div>
                    </div>

                    <div class="row g-5">
                        
                        <div class="col-12">
                            <div class="section-header">
                                <i class="bi bi-mortarboard-fill"></i>
                                <h5>Education History</h5>
                            </div>
                            <ul class="custom-list" id="list-education"></ul>
                        </div>

                        <div class="col-12">
                            <div class="section-header">
                                <i class="bi bi-stars"></i>
                                <h5>Areas of Expertise</h5>
                            </div>
                            <div class="d-flex flex-wrap gap-2" id="list-expertise"></div>
                        </div>

                        <div class="col-12">
                            <div class="section-header">
                                <i class="bi bi-patch-check-fill"></i>
                                <h5>Certifications</h5>
                            </div>
                            <div class="row g-3" id="list-certification"></div>
                        </div>

                        <div class="col-12">
                            <div class="section-header">
                                <i class="bi bi-book-half"></i>
                                <h5>Courses Taught</h5>
                            </div>
                            <div class="bg-white bg-opacity-50 p-4 rounded-4 border border-white">
                                <div class="row" id="list-courses">
                                    </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<?php ob_start() ?>
<script>
    document.addEventListener('DOMContentLoaded', async () => {
        const urlParams = new URLSearchParams(window.location.search);
        const memberSlug = urlParams.get('name');

        const loading = document.getElementById('loading');
        const content = document.getElementById('member-detail');
        const errorState = document.getElementById('error-state');

        // Helper: Format Null Data
        const safeText = (text) => (text && text !== 'null' && text !== '') ? text : '-';

        // Helper: Create Tag
        const createTag = (text) => `
            <span class="badge bg-white text-primary border border-primary border-opacity-10 px-3 py-2 rounded-pill fw-medium shadow-sm">
                ${text}
            </span>`;

        loading.classList.remove('d-none');

        if (!memberSlug) {
            loading.classList.add('d-none');
            errorState.classList.remove('d-none');
            return;
        }

        try {
            const response = await fetch(`http://inlet-lab.test/api/team/${memberSlug}`);

            if (!response.ok) throw new Error("API Error");

            const result = await response.json();
            const data = result.data;

            // 1. Populate Basic Info
            const imgEl = document.getElementById('img-profile');
            imgEl.src = data.image_name;
            imgEl.onerror = function() {
                // Fallback image with initials
                this.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(data.full_name)}&background=2563eb&color=fff&size=512`;
            };

            document.getElementById('text-name').textContent = data.full_name;
            document.getElementById('text-role').textContent = `${data.lab_position || 'Member'} • ${data.study_program || ''}`;
            
            // Email handling
            const emailEl = document.getElementById('text-email');
            if(data.email) {
                emailEl.innerHTML = `<a href="mailto:${data.email}" class="text-decoration-none text-dark hover-underline">${data.email}</a>`;
            } else {
                emailEl.textContent = '-';
            }

            document.getElementById('text-office').textContent = safeText(data.office_address);
            document.getElementById('text-nip').textContent = safeText(data.nip);
            document.getElementById('text-nidn').textContent = safeText(data.nidn);
            document.getElementById('text-academic').textContent = safeText(data.academic_position);

            // 2. Social Media
            const socialContainer = document.getElementById('social-container');
            socialContainer.innerHTML = '';
            if (data.social_medias?.length > 0) {
                data.social_medias.forEach(s => {
                    const iconClass = s.icon_name.startsWith('bi ') ? s.icon_name : `bi bi-${s.icon_name}`;
                    socialContainer.innerHTML += `
                        <a href="${s.url}" target="_blank" class="social-link" title="${s.type}">
                            <i class="${iconClass}"></i>
                        </a>`;
                });
            }

            // 3. Education
            const eduList = data.education || {};
            const eduEl = document.getElementById('list-education');
            const entries = Object.entries(eduList);
            if (entries.length > 0) {
                entries.forEach(([degree, details]) => {
                    eduEl.innerHTML += `
                        <li class="mb-3">
                            <div class="fw-bold text-dark fs-6">${degree || ''} ${details.major || ''}</div>
                            <div class="text-muted small">${details.university || ''} ${details.year ? `&bull; ${details.year}` : ''}</div>
                        </li>`;
                });
            } else {
                eduEl.innerHTML = '<li class="text-muted fst-italic">No education data available</li>';
            }

            // 4. Expertise
            const expList = data.expertise || [];
            const expEl = document.getElementById('list-expertise');
            if (expList.length > 0) {
                expList.forEach(ex => expEl.innerHTML += createTag(ex));
            } else {
                expEl.innerHTML = '<span class="text-muted small">No expertise listed</span>';
            }

            // 5. Certifications (Grid Layout)
            const certList = data.certifications || [];
            const certEl = document.getElementById('list-certification');
            if (certList.length > 0) {
                certList.forEach(c => {
                    certEl.innerHTML += `
                        <div class="col-md-6">
                            <div class="d-flex align-items-start gap-3 p-3 bg-white rounded-3 border h-100 shadow-sm">
                                <i class="bi bi-award-fill text-warning fs-4 mt-1"></i>
                                <div>
                                    <div class="fw-bold text-dark lh-sm mb-1">${c.name}</div>
                                    <div class="text-muted small">${c.year}</div>
                                </div>
                            </div>
                        </div>`;
                });
            } else {
                certEl.innerHTML = '<div class="col-12 text-muted fst-italic">No certifications listed</div>';
            }

            // 6. Courses
            const courses = data.courses_taught || {};
            const courseEl = document.getElementById('list-courses');
            const odd = courses.odd || courses.ganjil || [];
            const even = courses.even || courses.genap || [];

            if (odd.length > 0 || even.length > 0) {
                const renderList = (items) => items.length ? items.map(c => `<li>${c}</li>`).join('') : '<li class="text-muted italic">-</li>';
                
                courseEl.innerHTML = `
                    <div class="col-md-6 mb-4 mb-md-0 border-end border-secondary border-opacity-10">
                        <h6 class="text-primary text-uppercase text-xs fw-bold mb-3 tracking-wider">Odd Semester</h6>
                        <ul class="custom-list mb-0 small">${renderList(odd)}</ul>
                    </div>
                    <div class="col-md-6 ps-md-4">
                        <h6 class="text-primary text-uppercase text-xs fw-bold mb-3 tracking-wider">Even Semester</h6>
                        <ul class="custom-list mb-0 small">${renderList(even)}</ul>
                    </div>
                `;
            } else {
                courseEl.innerHTML = '<div class="col-12 text-center text-muted">No courses data available</div>';
            }

            // Show Content
            loading.classList.add('d-none');
            content.classList.remove('d-none');

        } catch (error) {
            console.error(error);
            loading.classList.add('d-none');
            errorState.classList.remove('d-none');
        }
    });
</script>
<?php $pageScripts = ob_get_clean(); ?>