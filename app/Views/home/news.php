<?php ob_start() ?>
<style>
    :root {
        --glass-bg: rgba(255, 255, 255, 0.7);
        --glass-border: 1px solid rgba(255, 255, 255, 0.5);
        --primary-accent: #2563eb;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --bg-blob-1: #e0c3fc;
        --bg-blob-2: #8ec5fc;
    }

    /* --- GLOBAL ANIMATIONS --- */
    @keyframes floatUp {
        0% {
            opacity: 0;
            transform: translateY(40px) scale(0.98);
        }

        100% {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    @keyframes blobMove {
        0% {
            transform: translate(0, 0) scale(1);
        }

        33% {
            transform: translate(30px, -50px) scale(1.1);
        }

        66% {
            transform: translate(-20px, 20px) scale(0.9);
        }

        100% {
            transform: translate(0, 0) scale(1);
        }
    }

    #news-page {
        padding-top: 140px;
        padding-bottom: 100px;
        background: radial-gradient(circle at top left, #f1f5f9, #fff);
        min-height: 100vh;
        position: relative;
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

    .blob-1 {
        top: 10%;
        left: -10%;
        width: 500px;
        height: 500px;
        background: var(--bg-blob-1);
        border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%;
    }

    .blob-2 {
        bottom: 10%;
        right: -10%;
        width: 600px;
        height: 600px;
        background: var(--bg-blob-2);
        border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
    }

    /* --- DECORATION --- */
    .bg-shape {
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        z-index: 0;
        opacity: 0.5;
    }

    .shape-1 {
        top: -10%;
        right: -5%;
        width: 500px;
        height: 500px;
        background: rgba(37, 99, 235, 0.1);
    }

    .shape-2 {
        bottom: 10%;
        left: -10%;
        width: 600px;
        height: 600px;
        background: rgba(14, 165, 233, 0.05);
    }

    /* --- NEWS GRID --- */
    .news-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 2rem;
        position: relative;
        z-index: 2;
    }

    /* --- NEWS CARD --- */
    .news-card-section {
        background: var(--glass-bg);
        backdrop-filter: blur(12px);
        border: var(--glass-border);
        border-radius: 24px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        display: flex;
        flex-direction: column;
        height: 100%;
        cursor: pointer;
        position: relative;
    }

    .news-card-section:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
        border-color: rgba(255, 255, 255, 0.8);
    }

    .card-img-wrapper {
        position: relative;
        aspect-ratio: 4/3;
        overflow: hidden;
    }

    .card-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .news-card-section:hover .card-img {
        transform: scale(1.05);
    }

    .card-date-badge {
        position: absolute;
        top: 1rem;
        left: 1rem;
        background: rgba(255, 255, 255, 0.9);
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--primary-accent);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(4px);
    }

    .card-content {
        padding: 1.5rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--text-dark);
        margin-bottom: 0.75rem;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .card-preview {
        font-size: 0.9rem;
        color: var(--text-muted);
        line-height: 1.6;
        margin-bottom: 1.5rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .card-footer {
        margin-top: auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        padding-top: 1rem;
    }

    .read-more {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--primary-accent);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: gap 0.3s;
    }

    .news-card-section:hover .read-more {
        gap: 0.75rem;
    }

    /* --- FEATURED CARD (First Item) --- */
    @media (min-width: 992px) {
        .news-card-section.featured {
            grid-column: span 2;
            flex-direction: row;
        }

        .news-card-section.featured .card-img-wrapper {
            width: 50%;
            aspect-ratio: auto;
        }

        .news-card-section.featured .card-content {
            width: 50%;
            justify-content: center;
            padding: 3rem;
        }

        .news-card-section.featured .card-title {
            font-size: 2rem;
            -webkit-line-clamp: 3;
        }

        .news-card-section.featured .card-preview {
            font-size: 1rem;
            -webkit-line-clamp: 4;
        }
    }

    /* --- PAGINATION --- */
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin-top: 4rem;
        position: relative;
        z-index: 2;
    }

    .page-btn {
        background: white;
        border: 1px solid rgba(0, 0, 0, 0.1);
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        color: var(--text-dark);
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .page-btn:hover:not(:disabled) {
        background: var(--primary-accent);
        color: white;
        border-color: var(--primary-accent);
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(37, 99, 235, 0.2);
    }

    .page-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* --- LOADING SKELETON --- */
    .skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
        border-radius: 8px;
    }

    @keyframes loading {
        0% {
            background-position: 200% 0;
        }

        100% {
            background-position: -200% 0;
        }
    }
</style>
<?php $pageStyle = ob_get_clean(); ?>

<section id="news-page">
    <div class="blob-bg blob-1"></div>
    <div class="blob-bg blob-2"></div>

    <div class="container position-relative z-2">

        <div class="row mb-5 text-center">
            <div class="col-lg-8 mx-auto">
                <span class="d-block text-primary fw-bold text-uppercase ls-2 small mb-2">Publications & Updates</span>
                <h1 class="display-4 fw-bold text-dark mb-3">Latest News</h1>
                <p class="text-muted lead">Discover the latest research breakthroughs, activities, and announcements from InLET Lab.</p>
            </div>
        </div>

        <div id="news-container" class="news-grid mb-5">
            <?php for ($i = 0; $i < 6; $i++): ?>
                <div class="news-card-section">
                    <div class="card-img-wrapper skeleton"></div>
                    <div class="card-content">
                        <div class="skeleton mb-3" style="height: 24px; width: 80%;"></div>
                        <div class="skeleton mb-2" style="height: 16px; width: 100%;"></div>
                        <div class="skeleton mb-2" style="height: 16px; width: 90%;"></div>
                        <div class="skeleton" style="height: 16px; width: 60%;"></div>
                    </div>
                </div>
            <?php endfor; ?>
        </div>

        <div class="pagination-wrapper">
            <button id="prev-btn" class="page-btn" disabled>
                <i class="bi bi-arrow-left"></i> Previous
            </button>
            <span id="page-info" class="align-self-center text-muted small fw-bold">Page 1</span>
            <button id="next-btn" class="page-btn" disabled>
                Next <i class="bi bi-arrow-right"></i>
            </button>
        </div>

    </div>
</section>

<?php ob_start() ?>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        let currentPage = 1;
        const container = document.getElementById('news-container');
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        const pageInfo = document.getElementById('page-info');

        const fetchNews = async (page) => {
            // Show Skeletons saat loading
            container.innerHTML = Array(6).fill(0).map(() => `
                <div class="news-card-section">
                    <div class="card-img-wrapper skeleton"></div>
                    <div class="card-content">
                        <div class="skeleton mb-3" style="height: 24px; width: 80%;"></div>
                        <div class="skeleton mb-2" style="height: 16px; width: 100%;"></div>
                        <div class="skeleton" style="height: 16px; width: 60%;"></div>
                    </div>
                </div>`).join('');

            try {
                const res = await fetch(`/api/news-list?page=${page}`);

                if (!res.ok) throw new Error(`HTTP Error: ${res.status}`);

                const result = await res.json();

                console.log('API Response:', result);

                if (result.success && result.data) {
                    renderNews(result.data.items || []);

                    if (result.data.pagination) {
                        updatePagination(result.data.pagination);
                    }
                } else {
                    throw new Error(result.message || 'Unknown error');
                }
            } catch (err) {
                console.error('Fetch Error:', err);
                container.innerHTML = `
                    <div class="col-12 text-center py-5" style="grid-column: 1 / -1;">
                        <i class="bi bi-exclamation-circle text-danger fs-1 mb-3"></i>
                        <p class="text-danger mb-0">Failed to load news.</p>
                        <small class="text-muted">${err.message}</small>
                    </div>`;
            }
        };

        const renderNews = (items) => {
            // Cek jika items kosong
            if (!items || items.length === 0) {
                container.innerHTML = `
            <div class="col-12 text-center text-muted py-5" style="grid-column: 1 / -1;">
                <h3>No news available.</h3>
            </div>`;
                return;
            }

            const htmlContent = items.map((item, index) => {
                // Logic Featured Card
                const isFeatured = (index === 0 && currentPage === 1);
                const featuredClass = isFeatured ? 'featured' : '';

                // Handle Image URL
                // Gunakan placeholder default jika image_url null
                let imageSrc = item.image_url;
                if (!imageSrc) {
                    imageSrc = 'https://placehold.co/600x400/e2e8f0/1e293b?text=No+Image';
                }

                // Sanitasi input text untuk mencegah XSS sederhana (opsional tapi baik)
                const title = item.title || 'Untitled';
                const date = item.date || '-';
                const preview = item.preview || '';
                const author = item.author || 'Admin Lab';

                return `
        <a href="/news/${item.id}" class="news-card-section ${featuredClass} text-decoration-none text-reset">
            <div class="card-img-wrapper">
                <img src="${imageSrc}" 
                     class="card-img" 
                     alt="${title}" 
                     loading="lazy" 
                     onerror="this.onerror=null; this.src='https://placehold.co/600x400/ffcccc/red?text=Broken+Img';">
                <div class="card-date-badge">${date}</div>
            </div>
            <div class="card-content">
                <h3 class="card-title text-dark">${title}</h3>
                <div class="card-preview text-muted">${preview}</div>
                <div class="card-footer mt-auto">
                    <span class="text-muted small fw-bold text-uppercase">By ${author}</span>
                    <span class="read-more text-primary">Read Article <i class="bi bi-arrow-right"></i></span>
                </div>
            </div>
        </a>
        `;
            }).join('');

            container.innerHTML = htmlContent;
        };

        const updatePagination = (meta) => {
            currentPage = meta.currentPage;
            pageInfo.textContent = `Page ${meta.currentPage} of ${meta.totalPages}`;

            prevBtn.disabled = !meta.hasPrev;
            nextBtn.disabled = !meta.hasNext;

            // Scroll ke atas section, bukan paling atas window
            const section = document.getElementById('news-page');
            if (section) section.scrollIntoView({
                behavior: 'smooth'
            });
        };

        // Event Listeners
        prevBtn.addEventListener('click', () => {
            if (!prevBtn.disabled) fetchNews(currentPage - 1);
        });

        nextBtn.addEventListener('click', () => {
            if (!nextBtn.disabled) fetchNews(currentPage + 1);
        });

        // Initial Load
        fetchNews(1);
    });
</script>
<?php $pageScripts = ob_get_clean(); ?>