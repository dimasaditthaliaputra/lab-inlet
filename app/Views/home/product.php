<?php ob_start() ?>
<style>
    :root {
        --blob-color-1: rgba(37, 99, 235, 0.1);
        --blob-color-2: rgba(147, 51, 234, 0.08);
        --text-head: #0f172a;
        --text-body: #475569;
        --primary-btn: #2563eb;
    }

    #product-page {
        padding-top: 140px;
        padding-bottom: 100px;
        background-color: #ffffff;
        min-height: 100vh;
        position: relative;
        overflow-x: hidden;
    }

    /* --- DECORATION --- */
    .blob-decoration {
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        z-index: 0;
        animation: blobFloat 12s infinite alternate ease-in-out;
    }
    .blob-1 { top: 5%; left: -5%; width: 600px; height: 600px; background: var(--blob-color-1); }
    .blob-2 { bottom: 10%; right: -5%; width: 700px; height: 700px; background: var(--blob-color-2); animation-delay: -5s; }

    @keyframes blobFloat {
        0% { transform: translate(0, 0) scale(1); }
        100% { transform: translate(30px, -30px) scale(1.05); }
    }

    /* --- SHOWCASE ITEM --- */
    .product-showcase {
        position: relative;
        z-index: 2;
        padding: 4rem 0;
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.6s ease-out;
    }
    
    .product-showcase.visible {
        opacity: 1;
        transform: translateY(0);
    }

    .product-image-wrapper {
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0,0,0,0.08);
        transform: perspective(1000px) rotateY(0deg);
        transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
        background: #fff;
        aspect-ratio: 16/10;
        display: block;
    }

    .product-showcase:hover .product-image-wrapper {
        transform: perspective(1000px) rotateY(2deg) translateY(-10px);
        box-shadow: 0 30px 60px rgba(37, 99, 235, 0.15);
    }

    .product-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-content {
        padding: 2rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        height: 100%;
    }

    .product-title {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--text-head);
        margin-bottom: 1rem;
        line-height: 1.2;
    }

    .cta-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: var(--primary-btn);
        color: white;
        padding: 14px 32px;
        border-radius: 50px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 10px 20px rgba(37, 99, 235, 0.2);
    }

    .cta-btn:hover {
        background: #1d4ed8;
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(37, 99, 235, 0.3);
    }

    .skeleton {
        background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
        border-radius: 12px;
    }
    @keyframes loading { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }

    @media (max-width: 991px) {
        .product-showcase { padding: 2rem 0; text-align: center; }
        .product-showcase.row-reverse { flex-direction: column !important; }
        .product-title { font-size: 2rem; }
        .product-content { align-items: center; }
    }
</style>
<?php $pageStyle = ob_get_clean(); ?>

<section id="product-page">
    <div class="blob-decoration blob-1"></div>
    <div class="blob-decoration blob-2"></div>

    <div class="container position-relative z-2">
        
        <div class="row justify-content-center text-center mb-5 pb-4">
            <div class="col-lg-8">
                <span class="d-inline-block py-2 px-3 bg-primary bg-opacity-10 text-primary rounded-pill fw-bold text-uppercase fs-7 mb-3">Our Innovations</span>
                <h1 class="display-4 fw-bold mb-3" style="color: #0f172a;">Tools for Modern Education</h1>
                <p class="lead text-muted">Explore our suite of educational technologies designed to enhance learning experiences.</p>
            </div>
        </div>

        <div id="product-list-container">
            </div>

        <div class="d-flex justify-content-center gap-3 mt-5 pt-4 d-none" id="pagination-controls">
            <button id="prev-btn" class="btn btn-outline-secondary rounded-pill px-4" disabled>
                <i class="bi bi-arrow-left"></i> Previous
            </button>
            <button id="next-btn" class="btn btn-outline-secondary rounded-pill px-4" disabled>
                Next <i class="bi bi-arrow-right"></i>
            </button>
        </div>

    </div>
</section>

<?php ob_start() ?>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        let currentPage = 1;
        const container = document.getElementById('product-list-container');
        const paginationControls = document.getElementById('pagination-controls');
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');

        const renderSkeletons = () => {
            container.innerHTML = Array(3).fill(0).map(() => `
                <div class="row align-items-center mb-5 product-showcase visible">
                    <div class="col-lg-7"><div class="skeleton" style="height: 350px; width: 100%;"></div></div>
                    <div class="col-lg-5 mt-4 mt-lg-0">
                        <div class="skeleton mb-3" style="height: 30px; width: 40%;"></div>
                        <div class="skeleton mb-4" style="height: 100px; width: 90%;"></div>
                        <div class="skeleton" style="height: 50px; width: 150px; border-radius: 50px;"></div>
                    </div>
                </div>
            `).join('');
        };

        const fetchProducts = async (page) => {
            renderSkeletons();
            try {
                const res = await fetch(`/api/product-list?page=${page}`);
                const result = await res.json();

                if (result.success) {
                    renderItems(result.data.items);
                    updateControls(result.data.pagination);
                    paginationControls.classList.remove('d-none');
                } else {
                    container.innerHTML = `<div class="text-center py-5"><h4 class="text-muted">Unable to load products.</h4></div>`;
                }
            } catch (err) {
                console.error(err);
                container.innerHTML = `<div class="text-center py-5"><h4 class="text-muted">Connection error.</h4></div>`;
            }
        };

        const renderItems = (items) => {
            if (items.length === 0) {
                container.innerHTML = `<div class="text-center py-5"><h4 class="text-muted">No products found.</h4></div>`;
                return;
            }

            container.innerHTML = items.map((item, index) => {
                const isEven = index % 2 === 0;
                const orderClass = isEven ? '' : 'flex-row-reverse';

                return `
                <div class="row align-items-center product-showcase visible ${orderClass} mb-5">
                    <div class="col-lg-7 mb-4 mb-lg-0">
                        <a href="/products/${item.id}" class="d-block product-image-wrapper">
                            <img src="${item.image_url}" alt="${item.name}" class="product-img" loading="lazy">
                        </a>
                    </div>
                    <div class="col-lg-5">
                        <div class="product-content">
                            <div class="d-flex align-items-center gap-2 text-muted fw-bold small mb-2">
                                <i class="bi bi-calendar-check text-primary"></i> Released: ${item.date}
                            </div>
                            <h2 class="product-title">${item.name}</h2>
                            <p class="text-muted fs-5 mb-4">${item.description}</p>
                            <a href="/products/${item.id}" class="cta-btn">
                                View Details <i class="bi bi-arrow-right-short fs-4"></i>
                            </a>
                        </div>
                    </div>
                </div>
                `;
            }).join('');
        };

        const updateControls = (meta) => {
            currentPage = meta.currentPage;
            prevBtn.disabled = !meta.hasPrev;
            nextBtn.disabled = !meta.hasNext;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        };

        prevBtn.addEventListener('click', () => !prevBtn.disabled && fetchProducts(currentPage - 1));
        nextBtn.addEventListener('click', () => !nextBtn.disabled && fetchProducts(currentPage + 1));

        fetchProducts(1);
    });
</script>
<?php $pageScripts = ob_get_clean(); ?>