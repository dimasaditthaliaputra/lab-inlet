<?php ob_start() ?>
<style>
    /* Styling khusus detail page */
    #project-detail-page {
        padding-top: 120px;
        padding-bottom: 80px;
        min-height: 100vh;
    }

    .hero-image-wrapper {
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        background: #f8f9fa;
    }

    .hero-image {
        width: 100%;
        height: auto;
        object-fit: cover;
        display: block;
    }

    .project-meta-card {
        position: sticky;
        top: 100px;
        background: white;
        border: 1px solid #eee;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
    }

    .loading-overlay {
        position: fixed;
        inset: 0;
        background: white;
        z-index: 9999;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }
</style>
<?php $pageStyle = ob_get_clean(); ?>

<section id="project-detail-page">

    <div id="detail-loader" class="loading-overlay">
        <div class="spinner-border text-primary" role="status"></div>
        <p class="mt-3 text-muted">Loading Project...</p>
    </div>

    <div id="detail-error" class="container text-center py-5 d-none">
        <i class="bi bi-exclamation-circle text-danger display-1"></i>
        <h3 class="mt-3">Project Not Found</h3>
        <a href="/#projects" class="btn btn-outline-primary mt-3">Back to Projects</a>
    </div>

    <div id="detail-content" class="container d-none fade-in-up">
        <div class="mb-4">
            <a href="/#projects" class="text-decoration-none text-muted fw-bold small">
                <i class="bi bi-arrow-left me-1"></i> Back to Projects
            </a>
        </div>

        <div class="row g-5">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3" id="p-title"></h1>

                <div id="p-categories" class="d-flex gap-2 mb-4"></div>

                <div class="hero-image-wrapper mb-5">
                    <img src="" id="p-image" class="hero-image" alt="Project Image">
                </div>

                <div class="project-description lead text-secondary" id="p-desc" style="line-height: 1.8;">
                </div>
            </div>

            <div class="col-lg-4">
                <div class="project-meta-card">
                    <h5 class="fw-bold mb-4">Project Info</h5>
                    <ul class="list-unstyled d-flex flex-column gap-3 mb-0">
                        <li class="d-flex justify-content-between border-bottom pb-2">
                            <span class="text-muted small fw-bold text-uppercase">Status</span>
                            <span class="fw-semibold text-success">Completed</span>
                        </li>
                        <li class="d-flex justify-content-between border-bottom pb-2">
                            <span class="text-muted small fw-bold text-uppercase">Year</span>
                            <span class="fw-semibold text-dark">2024</span>
                        </li>
                    </ul>
                    <div class="mt-4">
                        <a href="https://wa.me/<?php echo $phone; ?>"
                            target="_blank"
                            class="btn btn-primary w-100 rounded-pill fw-bold">
                            Contact Team
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php ob_start() ?>
<script>
    document.addEventListener('DOMContentLoaded', async () => {
        const loader = document.getElementById('detail-loader');
        const content = document.getElementById('detail-content');
        const errorMsg = document.getElementById('detail-error');

        // Ambil Slug dari URL
        const slug = window.location.pathname.split('/').pop();

        try {
            const res = await fetch(`/api/project-detail/${slug}`);
            const result = await res.json();

            if (result.success) {
                const data = result.data;

                // Populate Data
                document.getElementById('p-title').textContent = data.title;
                document.getElementById('p-desc').innerHTML = data.description ? data.description.replace(/\n/g, '<br>') : '';
                document.getElementById('p-image').src = data.image_url;

                // Categories
                const catContainer = document.getElementById('p-categories');
                if (data.categories) {
                    catContainer.innerHTML = data.categories.map(c =>
                        `<span class="badge bg-light text-primary border px-3 py-2 rounded-pill">${c}</span>`
                    ).join('');
                }

                loader.classList.add('d-none');
                content.classList.remove('d-none');
            } else {
                throw new Error('Project not found');
            }
        } catch (err) {
            console.error(err);
            loader.classList.add('d-none');
            errorMsg.classList.remove('d-none');
        }
    });
</script>
<?php $pageScripts = ob_get_clean(); ?>