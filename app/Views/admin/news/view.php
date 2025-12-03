<?php
$news = $data['news'] ?? null;
?>

<?php
ob_start();
?>
<style>
    .news-content {
        line-height: 1.8;
        font-size: 1rem;
    }
    
    .news-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 15px 0;
    }
    
    .news-content p {
        margin-bottom: 15px;
    }
    
    .img-fluid.rounded {
        border: 3px solid #f0f0f0;
        transition: transform 0.3s ease;
    }
    
    .img-fluid.rounded:hover {
        transform: scale(1.02);
    }
    
    .card {
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
</style>
<?php
$pageStyle = ob_get_clean();
echo $pageStyle;
?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>News Detail</h3>
                <p class="text-subtitle text-muted">View news information.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/news') ?>">News</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="row">
        <div class="col-12">
            <div class="card border">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">News Information</h4>
                        <div>
                            <?php if ($news->is_publish): ?>
                                <span class="badge bg-success">
                                    <i class="fas fa-check"></i> Published
                                </span>
                            <?php else: ?>
                                <span class="badge bg-secondary">
                                    <i class="fas fa-eye"></i> Draft
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php if ($news): ?>
                        <div class="row">
                            <!-- News Title -->
                            <div class="col-12 mb-4">
                                <h3 class="text-primary mb-3"><?= htmlspecialchars($news->title) ?></h3>
                                <hr>
                            </div>

                            <!-- News Image -->
                            <div class="col-12 mb-4">
                                <div class="text-center">
                                    <?php if (!empty($news->image_name)): ?>
                                        <img src="<?= base_url('uploads/news/' . $news->image_name) ?>" 
                                             alt="<?= htmlspecialchars($news->title) ?>" 
                                             class="img-fluid rounded shadow-sm" 
                                             style="max-height: 500px; width: auto; cursor: pointer;"
                                             data-bs-toggle="modal" 
                                             data-bs-target="#imageModal">
                                    <?php else: ?>
                                        <div class="alert alert-light" role="alert">
                                            <i class="fas fa-image fa-3x text-muted"></i>
                                            <p class="mt-2 mb-0">No image available</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- News Content -->
                            <div class="col-12 mb-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">
                                            <i class="fas fa-file-alt text-primary"></i> Content
                                        </h5>
                                        <div class="news-content">
                                            <?= $news->content ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- News Metadata -->
                            <div class="col-12 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">
                                            <i class="fas fa-info-circle text-info"></i> Information
                                        </h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <table class="table table-borderless table-sm">
                                                    <tr>
                                                        <td width="40%" class="fw-bold">
                                                            <i class="fas fa-user text-primary"></i> Created By
                                                        </td>
                                                        <td>: <?= htmlspecialchars($news->created_by ?? '-') ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold">
                                                            <i class="fas fa-calendar-alt text-success"></i> Publish Date
                                                        </td>
                                                        <td>
                                                            : <?= $news->publish_date ? date('d F Y, H:i', strtotime($news->publish_date)) : '<span class="text-muted">Not published yet</span>' ?>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <table class="table table-borderless table-sm">
                                                    <tr>
                                                        <td width="40%" class="fw-bold">
                                                            <i class="fas fa-toggle-on text-warning"></i> Status
                                                        </td>
                                                        <td>
                                                            : <?php if ($news->is_publish): ?>
                                                                <span class="badge bg-success">Published</span>
                                                            <?php else: ?>
                                                                <span class="badge bg-secondary">Draft</span>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="<?= base_url('admin/news') ?>" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Back to List
                                    </a>
                                    <div>
                                        <?php if (!$news->is_publish): ?>
                                            <button type="button" class="btn btn-info me-2" id="btnPublishDetail" data-id="<?= $news->id ?>">
                                                <i class="fas fa-globe"></i> Publish Now
                                            </button>
                                        <?php endif; ?>
                                        <a href="<?= base_url('admin/news/' . $news->id . '/edit') ?>" class="btn btn-warning">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning" role="alert">
                            <i class="fas fa-exclamation-triangle"></i> News not found.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal for Image Preview -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Image Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <?php if (!empty($news->image_name)): ?>
                        <img src="<?= base_url('uploads/news/' . $news->image_name) ?>" 
                             class="img-fluid" 
                             alt="<?= htmlspecialchars($news->title) ?>">
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
ob_start();
?>
<script>
    var audio = new Audio("<?php echo base_url('assets/audio/success.wav'); ?>");

    $(document).ready(function() {
        // Publish button handler
        $('#btnPublishDetail').click(function() {
            let newsId = $(this).data('id');
            let url = '<?= base_url('admin/news/publish') ?>/' + newsId;

            Swal.fire({
                title: "Are you sure to publish this news?",
                text: "This news will be published and visible to public.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: '#0000FF',
                confirmButtonText: 'Yes, publish it!',
                cancelButtonText: "No, cancel!",
                didOpen: () => {
                    const confirmButton = Swal.getConfirmButton();
                    if (confirmButton) {
                        confirmButton.style.setProperty('background-color', 'var(--var-primary)', 'important');
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'PUT',
                        dataType: 'JSON',
                        success: function(res) {
                            if (res.success) {
                                audio.play();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: res.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            let errorMessage;

                            switch (xhr.status) {
                                case 404:
                                    errorMessage = 'Error: News not found (404).';
                                    break;
                                case 0:
                                    errorMessage = 'Server timeout.';
                                    break;
                                default:
                                    errorMessage = (xhr.responseJSON && xhr.responseJSON.message) 
                                        ? xhr.responseJSON.message 
                                        : 'An error occurred. Please try again.';
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: errorMessage
                            });
                        }
                    });
                }
            });
        });
    });
</script>
<?php
$pageScripts = ob_get_clean();
?>