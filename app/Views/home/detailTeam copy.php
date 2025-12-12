<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Member Detail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2563eb;
            --bg-gradient: radial-gradient(circle at 10% 20%, rgb(240, 245, 255) 0%, rgb(255, 255, 255) 90%);
            --glass-bg: rgba(255, 255, 255, 0.7);
            --glass-border: 1px solid rgba(255, 255, 255, 0.5);
            --glass-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.05);
            --text-dark: #1e293b;
            --text-muted: #64748b;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: var(--bg-gradient);
            color: var(--text-dark);
            min-height: 100vh;
        }

        /* --- Animations --- */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in-up {
            animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
        }

        /* --- Glass Card --- */
        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: var(--glass-border);
            border-radius: 24px;
            box-shadow: var(--glass-shadow);
            overflow: hidden;
        }

        /* --- Profile Image --- */
        .profile-img-container {
            position: relative;
            width: 100%;
            padding-bottom: 100%; /* Aspect Ratio 1:1 */
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        .profile-img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .profile-img:hover {
            transform: scale(1.03);
        }

        /* --- Typography & Badges --- */
        .section-title {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 1rem;
            display: inline-block;
            border-bottom: 2px solid rgba(37, 99, 235, 0.2);
            padding-bottom: 4px;
        }

        .info-item {
            margin-bottom: 0.75rem;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .info-icon {
            color: var(--primary-color);
            background: rgba(37, 99, 235, 0.1);
            padding: 8px;
            border-radius: 10px;
            font-size: 1rem;
        }

        .badge-soft {
            background: rgba(37, 99, 235, 0.08);
            color: var(--primary-color);
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 500;
            border: 1px solid rgba(37, 99, 235, 0.1);
        }

        /* --- Social Icons --- */
        .social-btn {
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: white;
            color: var(--text-muted);
            border: 1px solid rgba(0,0,0,0.05);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 1.2rem;
        }

        .social-btn:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.25);
        }

        /* --- Detail List (Education, etc) --- */
        .detail-list {
            list-style: none;
            padding: 0;
        }

        .detail-list li {
            position: relative;
            padding-left: 20px;
            margin-bottom: 0.5rem;
            color: var(--text-muted);
        }

        .detail-list li::before {
            content: "";
            position: absolute;
            left: 0;
            top: 8px;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--primary-color);
            opacity: 0.5;
        }

        /* --- Responsive Tweaks --- */
        @media (max-width: 991px) {
            .profile-sidebar {
                margin-bottom: 2rem;
            }
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center py-5">

    <div class="container fade-in-up" style="max-width: 1100px;">
        <div id="loading" class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        <div id="member-detail" class="glass-card p-4 p-lg-5 d-none">
            <div class="row g-5">
                
                <div class="col-lg-4 profile-sidebar">
                    <div class="profile-img-container mb-4">
                        <img src="" id="img-profile" alt="Profile" class="profile-img">
                    </div>

                    <div class="text-center text-lg-start mb-4">
                        <h1 class="fw-bold mb-1" id="text-name" style="font-size: 2rem; line-height: 1.2;"></h1>
                        <p class="text-primary fw-semibold mb-3" id="text-role" style="letter-spacing: 0.5px;"></p>
                        
                        <div class="d-flex justify-content-center justify-content-lg-start gap-2" id="social-container">
                            </div>
                    </div>

                    <div class="bg-white bg-opacity-50 p-4 rounded-4 border border-white border-opacity-50">
                        <h6 class="section-title">Contact Info</h6>
                        <div class="info-item">
                            <i class="bi bi-envelope info-icon"></i>
                            <div>
                                <small class="d-block text-muted text-uppercase" style="font-size: 0.7rem;">Email</small>
                                <span class="fw-medium text-break" id="text-email"></span>
                            </div>
                        </div>
                        <div class="info-item">
                            <i class="bi bi-geo-alt info-icon"></i>
                            <div>
                                <small class="d-block text-muted text-uppercase" style="font-size: 0.7rem;">Office</small>
                                <span class="fw-medium" id="text-office"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="row g-4 mb-5">
                        <div class="col-md-6">
                            <div class="p-3 rounded-4 bg-white bg-opacity-40 h-100">
                                <small class="text-muted d-block mb-1">NIP / NIDN</small>
                                <div class="d-flex gap-3 fw-bold">
                                    <span id="text-nip"></span>
                                    <span class="text-muted fw-light">|</span>
                                    <span id="text-nidn"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded-4 bg-white bg-opacity-40 h-100">
                                <small class="text-muted d-block mb-1">Academic Position</small>
                                <span class="fw-bold" id="text-academic"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row g-5">
                        <div class="col-md-6">
                            <h5 class="fw-bold mb-3"><i class="bi bi-mortarboard me-2 text-primary"></i> Education</h5>
                            <ul class="detail-list" id="list-education"></ul>
                        </div>

                        <div class="col-md-6">
                            <h5 class="fw-bold mb-3"><i class="bi bi-stars me-2 text-primary"></i> Expertise</h5>
                            <div class="d-flex flex-wrap gap-2" id="list-expertise"></div>
                        </div>

                        <div class="col-12">
                            <h5 class="fw-bold mb-3"><i class="bi bi-patch-check me-2 text-primary"></i> Certifications</h5>
                            <div class="row g-3" id="list-certification"></div>
                        </div>

                        <div class="col-12">
                            <h5 class="fw-bold mb-3"><i class="bi bi-book me-2 text-primary"></i> Courses Taught</h5>
                            <div class="bg-white bg-opacity-40 p-4 rounded-4">
                                <div class="row" id="list-courses">
                                    </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', async () => {
        // 1. Ambil slug dari URL Parameter (?name=dr-sarah-lin)
        const urlParams = new URLSearchParams(window.location.search);
        const memberSlug = urlParams.get('name'); // Sesuai dengan link di carousel

        const loading = document.getElementById('loading');
        const content = document.getElementById('member-detail');
        const container = document.querySelector('.container'); // Container utama

        // Validasi jika tidak ada slug
        if (!memberSlug) {
            loading.innerHTML = `
                <div class="text-center">
                    <i class="bi bi-exclamation-circle text-warning display-1 mb-3"></i>
                    <h3 class="fw-bold">Member Not Found</h3>
                    <p class="text-muted">No member specified. Please go back to homepage.</p>
                    <a href="/" class="btn btn-primary rounded-pill px-4 mt-3">Back Home</a>
                </div>
            `;
            return;
        }

        try {
            // 2. Fetch Data dari API Real
            const response = await fetch(`http://inlet-lab.test/api/team/${memberSlug}`); 
            
            if (!response.ok) {
                if(response.status === 404) throw new Error("Member not found");
                throw new Error("Network response was not ok");
            }

            const result = await response.json();
            const data = result.data; // Data member dari API

            // --- 3. POPULATE DATA KE UI ---
            
            // Header Info
            const imgEl = document.getElementById('img-profile');
            imgEl.src = data.image_name;
            // Add error handler for image
            imgEl.onerror = function() { 
                this.src = `https://placehold.co/400x400/2563eb/ffffff?text=${encodeURIComponent(data.full_name)}`; 
            };

            document.getElementById('text-name').textContent = data.full_name;
            document.getElementById('text-role').textContent = `${data.lab_position || ''} • ${data.study_program || ''}`;
            
            // Contact
            const emailEl = document.getElementById('text-email');
            if(data.email) {
                emailEl.innerHTML = `<a href="mailto:${data.email}" class="text-decoration-none text-dark">${data.email}</a>`;
            } else {
                emailEl.textContent = '-';
            }
            document.getElementById('text-office').textContent = data.office_address || '-';
            
            // Identity
            document.getElementById('text-nip').textContent = data.nip || '-';
            document.getElementById('text-nidn').textContent = data.nidn || '-';
            document.getElementById('text-academic').textContent = data.academic_position || '-';

            // Social Media
            const socialContainer = document.getElementById('social-container');
            socialContainer.innerHTML = ''; // Reset
            if(data.social_medias && data.social_medias.length > 0) {
                data.social_medias.forEach(s => {
                    // Pastikan icon class valid (tambah 'bi bi-' jika belum ada di data)
                    // API controller sebelumnya kirim raw 'linkedin' -> jadi 'bi bi-linkedin'
                    const iconClass = s.icon_name.startsWith('bi ') ? s.icon_name : `bi bi-${s.icon_name}`;
                    
                    socialContainer.innerHTML += `
                        <a href="${s.url}" target="_blank" class="social-btn" title="${s.type}">
                            <i class="${iconClass}"></i>
                        </a>
                    `;
                });
            }

            // Helper: Parse JSON field from API (API already returns array/object, but safety check)
            const getList = (field) => {
                if (Array.isArray(field)) return field;
                if (typeof field === 'object' && field !== null) return field;
                return [];
            };

            // Education
            const eduList = getList(data.education);
            const eduEl = document.getElementById('list-education');
            if(eduList.length > 0) {
                eduList.forEach(e => {
                    eduEl.innerHTML += `
                        <li>
                            <strong>${e.degree || ''} ${e.major || ''}</strong> <br>
                            <span class="small text-muted">${e.univ || ''} (${e.year || ''})</span>
                        </li>
                    `;
                });
            } else {
                eduEl.innerHTML = '<li class="text-muted fst-italic">No education data available</li>';
            }

            // Expertise
            const expList = getList(data.expertise);
            const expEl = document.getElementById('list-expertise');
            if(expList.length > 0) {
                expList.forEach(ex => {
                    expEl.innerHTML += `<span class="badge-soft">${ex}</span>`;
                });
            } else {
                expEl.innerHTML = '<span class="text-muted small fst-italic">No expertise listed</span>';
            }

            // Certifications
            const certList = getList(data.certifications);
            const certEl = document.getElementById('list-certification');
            if(certList.length > 0) {
                certList.forEach(c => {
                    certEl.innerHTML += `
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2 p-2 bg-white bg-opacity-50 rounded-3 border border-light h-100">
                                <i class="bi bi-award text-warning fs-5 flex-shrink-0"></i>
                                <div class="small lh-sm">
                                    <div class="fw-semibold">${c.name}</div>
                                    <div class="text-muted" style="font-size: 0.75rem">${c.year}</div>
                                </div>
                            </div>
                        </div>
                    `;
                });
            } else {
                certEl.innerHTML = '<div class="col-12 text-muted small fst-italic">No certifications listed</div>';
            }

            // Courses
            const courses = getList(data.courses_taught);
            const courseEl = document.getElementById('list-courses');
            const odd = courses.odd || courses.ganjil || []; // Handle variasi naming key
            const even = courses.even || courses.genap || [];

            if(odd.length > 0 || even.length > 0) {
                courseEl.innerHTML = `
                    <div class="col-md-6 border-end border-light">
                        <h6 class="text-primary text-uppercase small fw-bold mb-3">Odd Semester</h6>
                        <ul class="detail-list mb-0">
                            ${odd.length ? odd.map(c => `<li>${c}</li>`).join('') : '<li class="text-muted">-</li>'}
                        </ul>
                    </div>
                    <div class="col-md-6 ps-md-4 mt-3 mt-md-0">
                        <h6 class="text-primary text-uppercase small fw-bold mb-3">Even Semester</h6>
                        <ul class="detail-list mb-0">
                            ${even.length ? even.map(c => `<li>${c}</li>`).join('') : '<li class="text-muted">-</li>'}
                        </ul>
                    </div>
                `;
            } else {
                courseEl.innerHTML = '<div class="col-12 text-muted small fst-italic text-center">No courses taught listed</div>';
            }

            // Show Content with Animation
            loading.classList.add('d-none');
            content.classList.remove('d-none');
            content.classList.add('fade-in-up'); // Trigger animation manually

        } catch (error) {
            console.error("Error fetching detail:", error);
            loading.innerHTML = `
                <div class="text-center text-danger">
                    <i class="bi bi-x-circle display-1 mb-3"></i>
                    <h3 class="fw-bold">Failed to load profile</h3>
                    <p class="text-muted">${error.message || "Please check your connection and try again."}</p>
                    <a href="/" class="btn btn-outline-danger rounded-pill px-4 mt-3">Back Home</a>
                </div>
            `;
        }
    });
</script>
</body>
</html>