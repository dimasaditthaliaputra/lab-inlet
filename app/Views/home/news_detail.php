<?php ob_start() ?>
<style>
    :root {
        --text-body: #334155;
        --text-head: #0f172a;
        --bg-article: #ffffff;
    }

    #news-detail-page {
        padding-top: 120px;
        padding-bottom: 80px;
        background-color: #f8fafc;
        min-height: 100vh;
    }

    /* --- HERO HEADER --- */
    .article-header {
        position: relative;
        margin-bottom: -80px;
        z-index: 2;
    }

    .article-meta {
        background: white;
        padding: 2rem;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.05);
        max-width: 900px;
        margin: 0 auto;
    }

    .hero-img-wrapper {
        width: 100%;
        height: 500px;
        border-radius: 24px;
        overflow: hidden;
        position: relative;
        z-index: 1;
        margin-bottom: 2rem;
    }

    .hero-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* --- CONTENT --- */
    .article-content {
        max-width: 800px;
        margin: 120px auto 0;
        font-family: 'Inter', sans-serif; /* Ensure readable font */
        color: var(--text-body);
        line-height: 1.8;
        font-size: 1.1rem;
    }

    /* Typography Styling for Dynamic Content */
    .article-content h1, 
    .article-content h2, 
    .article-content h3 {
        color: var(--text-head);
        font-weight: 800;
        margin-top: 2.5rem;
        margin-bottom: 1rem;
    }
    
    .article-content p {
        margin-bottom: 1.5rem;
    }

    .article-content img {
        max-width: 100%;
        height: auto;
        border-radius: 12px;
        margin: 2rem 0;
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
    }

    .article-content blockquote {
        border-left: 4px solid #2563eb;
        padding-left: 1.5rem;
        font-style: italic;
        color: #475569;
        margin: 2rem 0;
        font-size: 1.2rem;
    }

    .article-content ul, .article-content ol {
        margin-bottom: 1.5rem;
        padding-left: 1.5rem;
    }

    /* --- NAVIGATION --- */
    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        color: #64748b;
        font-weight: 600;
        margin-bottom: 2rem;
        transition: color 0.3s;
    }

    .back-btn:hover {
        color: #2563eb;
    }

    @media (max-width: 768px) {
        .hero-img-wrapper { height: 300px; }
        .article-meta { padding: 1.5rem; margin: 0 1rem; }
        .article-content { padding: 0 1.5rem; font-size: 1rem; }
    }
</style>
<?php $pageStyle = ob_get_clean(); ?>

<section id="news-detail-page">
    <div class="container">
        
        <a href="/news" class="back-btn fade-in-up">
            <i class="bi bi-arrow-left"></i> Back to News
        </a>

        <div class="hero-img-wrapper shadow-lg fade-in-up">
            <img src="<?= $news->image_url ?>" alt="<?= htmlspecialchars($news->title) ?>" class="hero-img">
        </div>

        <div class="article-header fade-in-up" style="animation-delay: 0.1s;">
            <div class="article-meta">
                <div class="d-flex align-items-center gap-3 mb-3 text-muted small fw-bold text-uppercase">
                    <span><i class="bi bi-calendar-event me-1"></i> <?= $news->formatted_date ?></span>
                    <span>&bull;</span>
                    <span><i class="bi bi-person me-1"></i> <?= $news->created_by ?? 'Editorial Team' ?></span>
                </div>
                <h1 class="display-6 fw-bold text-dark mb-0"><?= htmlspecialchars($news->title) ?></h1>
            </div>
        </div>

        <article class="article-content fade-in-up" style="animation-delay: 0.2s;">
            <?= $news->content ?> 
        </article>

        <div class="article-content mt-5 pt-5 border-top">
            <div class="d-flex justify-content-between">
                <a href="/news" class="btn btn-outline-primary rounded-pill px-4">
                    <i class="bi bi-grid me-2"></i> More News
                </a>
            </div>
        </div>

    </div>
</section>

<?php ob_start() ?>
<script>
    // Simple Entrance Animation
    document.addEventListener("DOMContentLoaded", () => {
        const elements = document.querySelectorAll('.fade-in-up');
        elements.forEach((el, index) => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = `all 0.6s cubic-bezier(0.16, 1, 0.3, 1) ${index * 0.1}s`;
            
            setTimeout(() => {
                el.style.opacity = '1';
                el.style.transform = 'translateY(0)';
            }, 100);
        });
    });
</script>
<?php $pageScripts = ob_get_clean(); ?>