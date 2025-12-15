<?php ob_start() ?>
<style>
    :root {
        --glass-bg: rgba(30, 41, 59, 0.6); /* Dark Glass */
        --glass-border: 1px solid rgba(255, 255, 255, 0.1); /* Lighter border for contrast */
        --primary-accent: #3b82f6; /* Blue 500 */
        --dark-overlay: rgba(15, 23, 42, 0.6);
        --tech-black: #0f172a; /* Slate 900 */
    }

    /* --- PAGE LAYOUT --- */
    #gallery-page {
        position: relative;
        padding-top: 140px;
        padding-bottom: 100px;
        background-color: var(--tech-black);
        overflow-x: hidden;
        min-height: 100vh;
    }
    
    /* Change default text colors for dark bg */
    #gallery-page .display-5,
    #gallery-page #section-photos h3,
    #gallery-page #section-videos h3,
    #gallery-page #gallery-empty h3 {
        color: #f1f5f9; /* Slate 100 */
    }
    #gallery-page .text-muted {
        color: #94a3b8 !important; /* Slate 400 */
    }

    .section-head {
        margin-bottom: 3rem;
        position: relative;
        z-index: 2;
    }

    .section-label {
        font-size: 0.75rem;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--primary-accent);
        font-weight: 700;
        margin-bottom: 0.5rem;
        display: block;
    }

    /* --- MASONRY LAYOUT (PHOTOS) --- */
    .masonry-grid {
        column-count: 3;
        column-gap: 1.5rem;
    }

    .masonry-item {
        break-inside: avoid;
        margin-bottom: 1.5rem;
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        cursor: pointer;
        transform: translateZ(0); /* Hardware accel */
        transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .masonry-item img {
        width: 100%;
        height: auto;
        display: block;
        transition: transform 0.6s ease;
    }

    /* Hover Effect Photo */
    .masonry-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, transparent 70%);
        opacity: 0;
        transition: opacity 0.3s ease;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 1.5rem;
    }

    .masonry-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
        z-index: 2;
    }

    .masonry-item:hover img {
        transform: scale(1.05);
    }

    .masonry-item:hover .masonry-overlay {
        opacity: 1;
    }

    /* --- GRID LAYOUT (VIDEOS) --- */
    .video-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 2rem;
    }

    .video-card {
        background: var(--glass-bg);
        backdrop-filter: blur(12px);
        border: var(--glass-border);
        border-radius: 24px;
        overflow: hidden;
        transition: all 0.4s ease;
        position: relative;
        group: video;
    }

    .video-thumb-wrapper {
        position: relative;
        aspect-ratio: 16/9;
        overflow: hidden;
    }

    .video-thumb {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .play-btn {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0.8);
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(4px);
        border: 1px solid rgba(255,255,255,0.5);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        opacity: 0.9;
    }

    .video-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(59, 130, 246, 0.1);
        border: 1px solid rgba(59, 130, 246, 0.4);
    }

    .video-card:hover .video-thumb {
        transform: scale(1.05);
        filter: brightness(0.8);
    }

    .video-card:hover .play-btn {
        transform: translate(-50%, -50%) scale(1.1);
        background: var(--primary-accent);
        border-color: var(--primary-accent);
    }

    .video-info {
        padding: 1.5rem;
    }
    
    #gallery-empty.glass-card {
        background: var(--glass-bg);
        backdrop-filter: blur(12px);
        border: var(--glass-border);
        border-radius: 24px;
        padding: 4rem;
    }

    /* --- MODAL CUSTOMIZATION --- */
    .modal-content.glass-modal {
        background: rgba(20, 20, 20, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255,255,255,0.1);
        color: white;
    }
    
    .btn-close-white {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    /* --- DECORATION --- */
    .bg-shape {
        position: absolute;
        border-radius: 50%;
        filter: blur(120px);
        pointer-events: none;
        z-index: 0;
    }
    .shape-1 { width: 500px; height: 500px; background: rgba(59, 130, 246, 0.15); top: -150px; right: -150px; opacity: 0.7; }
    .shape-2 { width: 400px; height: 400px; background: rgba(219, 39, 119, 0.12); bottom: 5%; left: -150px; opacity: 0.8; }
    .shape-3 { width: 450px; height: 450px; background: rgba(20, 184, 166, 0.15); top: 30%; left: 40%; opacity: 0.6; }

    /* --- PAGINATION --- */
    .pagination-controls {
        display: inline-flex;
        align-items: center;
        background: rgba(30, 41, 59, 0.4);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 50px; /* pill shape */
        padding: 0.5rem;
        gap: 0.5rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }

    .pagination-controls .btn-page {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid transparent;
        color: #cbd5e1; /* slate-300 */
        width: 40px;
        height: 40px;
        border-radius: 50%;
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .pagination-controls .btn-page:hover:not(:disabled) {
        background: var(--primary-accent);
        border-color: rgba(255,255,255,0.3);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 10px rgba(59, 130, 246, 0.3);
    }

    .pagination-controls .btn-page:disabled {
        opacity: 0.4;
        cursor: not-allowed;
        background: rgba(255, 255, 255, 0.05);
    }

    .pagination-controls .page-info {
        color: #e2e8f0; /* slate-200 */
        font-size: 0.9rem;
        font-weight: 500;
        padding: 0 0.5rem;
        min-width: 110px;
        text-align: center;
    }

    /* --- RESPONSIVE --- */
    @media (max-width: 992px) {
        .masonry-grid { column-count: 2; }
    }
    @media (max-width: 576px) {
        .masonry-grid { column-count: 1; }
        .video-grid { grid-template-columns: 1fr; }
        #gallery-page { padding-top: 100px; }
    }
</style>
<?php $pageStyle = ob_get_clean(); ?>

<section id="gallery-page">
    
    <div class="bg-shape shape-1"></div>
    <div class="bg-shape shape-2"></div>
    <div class="bg-shape shape-3"></div>

    <div class="container position-relative z-2">
        
        <div class="row mb-5 justify-content-center text-center fade-in-up">
            <div class="col-lg-8">
                <span class="section-label">Our Documentation</span>
                <h1 class="display-5 fw-bold mb-3">Laboratory Gallery</h1>
                <p class="text-muted lead">Capturing moments of innovation, research activities, and technological breakthroughs at InLET Lab.</p>
            </div>
        </div>

        <div id="gallery-loading" class="text-center py-5">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-3 text-muted">Loading gallery...</p>
        </div>

        <div id="gallery-error" class="text-center py-5 d-none">
            <i class="bi bi-exclamation-triangle text-warning display-4"></i>
            <p class="mt-3 text-muted">Failed to load content. Please refresh.</p>
        </div>

        <div id="gallery-content" class="d-none">
            
            <div id="section-photos" class="d-none">
                <div class="section-head d-flex align-items-center justify-content-between mb-4 fade-in-up">
                    <h3 class="fw-bold m-0"><i class="bi bi-camera-fill text-primary me-2"></i>Photos</h3>
                </div>
                <div class="masonry-grid mb-5 pb-5 border-bottom fade-in-up" id="photo-container"></div>
                <div id="photo-pagination" class="d-flex justify-content-center align-items-center gap-2 mt-4"></div>
            </div>

            <div id="section-videos" class="d-none">
                <div class="section-head d-flex align-items-center justify-content-between mb-4 fade-in-up" style="animation-delay: 0.2s;">
                    <h3 class="fw-bold m-0"><i class="bi bi-play-circle-fill text-danger me-2"></i>Videos</h3>
                </div>
                <div class="video-grid fade-in-up" style="animation-delay: 0.3s;" id="video-container"></div>
                <div id="video-pagination" class="d-flex justify-content-center align-items-center gap-2 mt-4"></div>
            </div>

            <div id="gallery-empty" class="text-center py-5 glass-card d-none">
                <i class="bi bi-images text-muted display-1 mb-3"></i>
                <h3 class="text-muted">No gallery items found yet.</h3>
            </div>

        </div>

    </div>
</section>

<div class="modal fade" id="galleryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content glass-modal border-0 rounded-4 overflow-hidden">
            <div class="modal-header border-bottom border-secondary border-opacity-25">
                <h5 class="modal-title fw-bold" id="galleryModalTitle"></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 bg-black d-flex justify-content-center align-items-center" style="min-height: 500px; max-height: 80vh;">
                <div id="galleryModalContent" class="w-100 h-100 d-flex justify-content-center align-items-center"></div>
            </div>
            <div class="modal-footer border-top border-secondary border-opacity-25 justify-content-start">
                <p id="galleryModalDesc" class="text-white-50 mb-0 small"></p>
            </div>
        </div>
    </div>
</div>

<?php ob_start() ?>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const loadingEl = document.getElementById('gallery-loading');
        const contentEl = document.getElementById('gallery-content');
        const errorEl = document.getElementById('gallery-error');
        const emptyEl = document.getElementById('gallery-empty');

        const photoSection = document.getElementById('section-photos');
        const videoSection = document.getElementById('section-videos');
        
        const photoContainer = document.getElementById('photo-container');
        const photoPaginationContainer = document.getElementById('photo-pagination');
        
        const videoContainer = document.getElementById('video-container');
        const videoPaginationContainer = document.getElementById('video-pagination');

        let galleryState = {
            photos: { currentPage: 1, totalPages: 1 },
            videos: { currentPage: 1, totalPages: 1 }
        };

        const escapeHtml = (unsafe) => {
            return unsafe ? unsafe
                 .replace(/&/g, "&amp;")
                 .replace(/</g, "&lt;")
                 .replace(/>/g, "&gt;")
                 .replace(/"/g, "&quot;")
                 .replace(/'/g, "&#039;") : "";
        };

        const renderPagination = (container, type, { currentPage, totalPages }) => {
            container.innerHTML = '';
            if (totalPages <= 1) return;

            const paginationHtml = `
                <div class="pagination-controls">
                    <button class="btn-page" ${currentPage === 1 ? 'disabled' : ''} 
                            onclick="loadGallerySection('${type}', ${currentPage - 1})">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <span class="page-info">Page ${currentPage} of ${totalPages}</span>
                    <button class="btn-page" ${currentPage === totalPages ? 'disabled' : ''} 
                            onclick="loadGallerySection('${type}', ${currentPage + 1})">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            `;
            
            container.innerHTML = paginationHtml;
        };

        const renderPhotos = (data) => {
            const { items, pagination } = data;
            galleryState.photos = pagination;

            if (items && items.length > 0) {
                photoSection.classList.remove('d-none');
                photoContainer.innerHTML = items.map(photo => `
                    <div class="masonry-item" 
                         onclick="openGalleryModal('photo', '${photo.src}', '${escapeHtml(photo.title)}', '${escapeHtml(photo.description)}')">
                        <img src="${photo.src}" alt="${escapeHtml(photo.title)}" loading="lazy">
                        <div class="masonry-overlay">
                            <span class="badge bg-white text-dark mb-2 align-self-start shadow-sm">${photo.date}</span>
                            <h5 class="text-white fw-bold mb-1 fs-6">${escapeHtml(photo.title)}</h5>
                            ${photo.description ? `<p class="text-white-50 small mb-0 text-truncate">${escapeHtml(photo.description)}</p>` : ''}
                        </div>
                    </div>
                `).join('');
            } else {
                 photoContainer.innerHTML = '<p class="text-muted text-center">No photos found in this section.</p>';
            }
            renderPagination(photoPaginationContainer, 'photos', pagination);
        };

        const renderVideos = (data) => {
            const { items, pagination } = data;
            galleryState.videos = pagination;

            if (items && items.length > 0) {
                videoSection.classList.remove('d-none');
                videoContainer.innerHTML = items.map(video => `
                    <div class="video-card"
                         onclick="openGalleryModal('video', '${video.video_url}', '${escapeHtml(video.title)}', '${escapeHtml(video.description)}')">
                        <div class="video-thumb-wrapper">
                            <img src="${video.thumbnail}" class="video-thumb" alt="${escapeHtml(video.title)}" loading="lazy">
                            <div class="play-btn"><i class="bi bi-play-fill ms-1"></i></div>
                        </div>
                        <div class="video-info">
                            <h5 class="fw-bold mb-2 fs-5 text-light">${escapeHtml(video.title)}</h5>
                            <p class="text-muted small mb-0 line-clamp-2">Click to watch video.</p>
                        </div>
                    </div>
                `).join('');
            } else {
                videoContainer.innerHTML = '<p class="text-muted text-center">No videos found in this section.</p>';
            }
            renderPagination(videoPaginationContainer, 'videos', pagination);
        };

        const loadGallerySection = async (type, page = 1) => {
            let url = '';
            const container = type === 'photos' ? photoContainer : videoContainer;
            container.innerHTML = `<div class="w-100 text-center"><div class="spinner-border text-primary" role="status"></div></div>`;

            if (type === 'photos') {
                galleryState.photos.currentPage = page;
                url = `/api/gallery-content?photos_page=${page}`;
            } else { // videos
                galleryState.videos.currentPage = page;
                url = `/api/gallery-content?videos_page=${page}`;
            }

            try {
                const response = await fetch(url);
                if (!response.ok) throw new Error('Network error');
                
                const result = await response.json();
                if (result.success) {
                    if (type === 'photos') {
                        renderPhotos(result.data.photos);
                    } else {
                        renderVideos(result.data.videos);
                    }
                } else {
                    throw new Error(result.message);
                }
            } catch (error) {
                console.error(`Gallery Error (${type}):`, error);
                container.innerHTML = `<div class="w-100 text-center text-warning"><i class="bi bi-exclamation-triangle"></i> Failed to load content.</div>`;
            }
        };

        // Make loadGallerySection globally accessible for the onclick attributes
        window.loadGallerySection = loadGallerySection;

        const initialLoad = async () => {
             try {
                const response = await fetch(`/api/gallery-content?photos_page=1&videos_page=1`);
                if (!response.ok) throw new Error('Network error');
                
                const result = await response.json();

                if (result.success) {
                    const { photos, videos } = result.data;
                    let hasContent = (photos.items && photos.items.length > 0) || (videos.items && videos.items.length > 0);

                    renderPhotos(photos);
                    renderVideos(videos);

                    if (!hasContent) {
                        emptyEl.classList.remove('d-none');
                        photoSection.classList.add('d-none');
                        videoSection.classList.add('d-none');
                    } else {
                         contentEl.classList.remove('d-none');
                    }

                } else {
                    throw new Error(result.message);
                }
            } catch (error) {
                console.error('Gallery Error:', error);
                errorEl.classList.remove('d-none');
            } finally {
                loadingEl.classList.add('d-none');
            }
        };

        initialLoad();
    });

    // --- LOGIC MODAL & YOUTUBE PARSER ---
    const galleryModal = new bootstrap.Modal(document.getElementById('galleryModal'));
    const modalTitle = document.getElementById('galleryModalTitle');
    const modalContent = document.getElementById('galleryModalContent');
    const modalDesc = document.getElementById('galleryModalDesc');

    window.openGalleryModal = (type, src, title, desc) => {
        modalContent.innerHTML = '';
        modalTitle.textContent = title;
        modalDesc.textContent = desc;

        if (type === 'photo') {
            const img = document.createElement('img');
            img.src = src;
            img.className = 'img-fluid';
            img.style.maxHeight = '80vh';
            img.style.objectFit = 'contain';
            modalContent.appendChild(img);
        } else if (type === 'video') {
            let embedUrl = '';
            const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
            const match = src.match(regExp);

            if (match && match[2].length === 11) {
                embedUrl = `https://www.youtube.com/embed/${match[2]}?autoplay=1`;
                
                const iframe = document.createElement('iframe');
                iframe.src = embedUrl;
                iframe.className = 'w-100 h-100';
                iframe.style.minHeight = '500px';
                iframe.allow = "autoplay; encrypted-media; picture-in-picture";
                iframe.allowFullscreen = true;
                iframe.setAttribute('frameborder', '0');
                modalContent.appendChild(iframe);
            } else {
                modalContent.innerHTML = `<div class="text-white text-center"><i class="bi bi-link-45deg fs-1 text-warning"></i><br>Invalid Video URL</div>`;
            }
        }
        galleryModal.show();
    };

    document.getElementById('galleryModal').addEventListener('hidden.bs.modal', () => {
        modalContent.innerHTML = '';
    });
</script>
<?php $pageScripts = ob_get_clean(); ?>