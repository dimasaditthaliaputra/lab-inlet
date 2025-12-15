<?php ob_start() ?>
<style>
    :root {
        --text-main: #1e293b;
        --text-sub: #64748b;
        --primary-color: #2563eb;
    }

    #product-detail-page {
        padding-top: 100px;
        background: #fff;
        min-height: 100vh;
        overflow-x: hidden;
    }

    /* --- HERO --- */
    .hero-section {
        padding: 4rem 0 6rem;
        position: relative;
    }
    .hero-blob {
        position: absolute;
        top: -50%; right: -20%;
        width: 800px; height: 800px;
        background: radial-gradient(circle, rgba(37,99,235,0.08) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
        z-index: 0; pointer-events: none;
    }
    .hero-title { font-size: 3.5rem; font-weight: 800; color: var(--text-main); line-height: 1.1; }
    .hero-img-box {
        border-radius: 30px;
        overflow: hidden;
        box-shadow: 0 25px 80px -10px rgba(0, 0, 0, 0.15);
        transform: rotate(-1deg);
        background: #fff;
        transition: transform 0.5s ease;
    }
    .hero-img-box:hover { transform: rotate(0deg) scale(1.01); }
    .hero-img-full { width: 100%; height: auto; display: block; }

    /* --- CONTENT --- */
    .content-section { background: #f8fafc; padding: 6rem 0; }
    .spec-list li {
        padding: 12px 0; border-bottom: 1px dashed #e2e8f0;
        display: flex; justify-content: space-between; color: var(--text-sub);
    }
    .desc-text { font-size: 1.1rem; line-height: 1.8; color: var(--text-sub); }

    /* Features List Style */
    .feature-item {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
        padding: 12px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
    }
    .feature-icon {
        color: var(--primary-color);
        background: rgba(37, 99, 235, 0.1);
        padding: 8px;
        border-radius: 50%;
    }

    /* Loading State */
    .loading-overlay {
        position: fixed; inset: 0; background: white; z-index: 9999;
        display: flex; justify-content: center; align-items: center;
        flex-direction: column;
    }
</style>
<?php $pageStyle = ob_get_clean(); ?>

<div id="product-detail-page">
    
    <div id="page-loader" class="loading-overlay">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status"></div>
        <p class="mt-3 text-muted fw-bold">Loading Product...</p>
    </div>

    <div id="error-state" class="d-none container text-center py-5" style="margin-top: 100px;">
        <i class="bi bi-exclamation-circle display-1 text-danger mb-3"></i>
        <h3>Product Not Found</h3>
        <a href="/products" class="btn btn-outline-primary mt-3">Back to Products</a>
    </div>

    <div id="main-content" class="d-none">
        
        <section class="hero-section">
            <div class="hero-blob"></div>
            <div class="container position-relative z-1">
                <div class="row align-items-center">
                    <div class="col-lg-6 mb-5 mb-lg-0">
                        <a href="/products" class="text-decoration-none fw-bold text-muted small mb-3 d-inline-block">
                            <i class="bi bi-arrow-left"></i> Back to Products
                        </a>
                        <span class="d-block text-primary fw-bold text-uppercase small mt-2">Product Detail</span>
                        <h1 class="hero-title mb-3" id="detail-title"></h1>
                        <p class="lead text-muted mb-4">Discover innovation designed for future education.</p>
                        
                        <div class="d-flex gap-3">
                            <a href="#" id="cta-link" target="_blank" class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow-sm">
                                Visit Product <i class="bi bi-box-arrow-up-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-6 ps-lg-5">
                        <div class="hero-img-box">
                            <img src="" id="detail-img" class="hero-img-full" alt="Product Image">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="content-section">
            <div class="container">
                <div class="row gx-5">
                    <div class="col-lg-8 mb-5 mb-lg-0">
                        <h3 class="fw-bold mb-4 text-dark">About the Product</h3>
                        <div class="desc-text mb-5" id="detail-desc"></div>

                        <div id="features-wrapper" class="d-none">
                            <h4 class="fw-bold mb-3 text-dark">Key Features</h4>
                            <div class="row" id="features-list"></div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="bg-white p-4 rounded-4 shadow-sm sticky-top" style="top: 120px;">
                            <h5 class="fw-bold mb-4">Specifications</h5>
                            <ul class="spec-list" id="detail-specs">
                                </ul>
                            
                            <div class="mt-4 p-3 bg-light rounded-3 text-center">
                                <small class="text-muted d-block mb-1">Release Date</small>
                                <strong class="text-dark" id="detail-date"></strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
</div>

<?php ob_start() ?>
<script>
    document.addEventListener('DOMContentLoaded', async () => {
        const loader = document.getElementById('page-loader');
        const content = document.getElementById('main-content');
        const errorState = document.getElementById('error-state');
        
        // Elements
        const titleEl = document.getElementById('detail-title');
        const imgEl = document.getElementById('detail-img');
        const descEl = document.getElementById('detail-desc');
        const ctaLink = document.getElementById('cta-link');
        const dateEl = document.getElementById('detail-date');
        
        const specsEl = document.getElementById('detail-specs');
        const featuresWrapper = document.getElementById('features-wrapper');
        const featuresList = document.getElementById('features-list');

        const pathSegments = window.location.pathname.split('/');
        const productId = pathSegments[pathSegments.length - 1];

        if (!productId) {
            loader.classList.add('d-none');
            errorState.classList.remove('d-none');
            return;
        }

        try {
            const res = await fetch(`/api/product-detail/${productId}`);
            const result = await res.json();

            if (result.success) {
                const data = result.data;

                // 1. Basic Info
                titleEl.textContent = data.name;
                descEl.innerHTML = data.description ? data.description.replace(/\n/g, '<br>') : 'No description available.';
                imgEl.src = data.image_url;
                imgEl.onerror = function() { this.src = 'https://placehold.co/800x600?text=No+Image'; };
                dateEl.textContent = data.date;

                // 2. CTA Link
                if (data.product_link) {
                    ctaLink.href = data.product_link;
                } else {
                    ctaLink.classList.add('disabled');
                    ctaLink.textContent = 'Coming Soon';
                }

                // 3. Specifications (JSON Object)
                if (data.specs && Object.keys(data.specs).length > 0) {
                    let specsHtml = '';
                    for (const [key, value] of Object.entries(data.specs)) {
                        specsHtml += `<li><span>${key}</span><strong>${value}</strong></li>`;
                    }
                    specsEl.innerHTML = specsHtml;
                } else {
                    specsEl.innerHTML = `<li class="text-center text-muted small">No specifications listed.</li>`;
                }

                // 4. Features (JSON Array)
                if (data.features && Array.isArray(data.features) && data.features.length > 0) {
                    featuresWrapper.classList.remove('d-none');
                    featuresList.innerHTML = data.features.map(feat => `
                        <div class="col-md-6">
                            <div class="feature-item">
                                <i class="bi bi-check-circle-fill feature-icon"></i>
                                <span class="fw-medium text-dark">${feat}</span>
                            </div>
                        </div>
                    `).join('');
                }

                // Show Content
                loader.classList.add('d-none');
                content.classList.remove('d-none');
                
                content.style.opacity = 0;
                setTimeout(() => {
                    content.style.transition = 'opacity 0.5s ease';
                    content.style.opacity = 1;
                }, 50);

            } else {
                throw new Error('API returned failure');
            }

        } catch (err) {
            console.error(err);
            loader.classList.add('d-none');
            errorState.classList.remove('d-none');
        }
    });
</script>
<?php $pageScripts = ob_get_clean(); ?>