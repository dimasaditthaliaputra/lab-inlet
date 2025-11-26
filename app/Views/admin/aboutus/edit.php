<div class="page-heading">
    <h3>Detail About Us</h3>
</div>

<div class="page-content">
    <section class="row">
        <div class="col-12">
            <div class="card border">
                <div class="card-body">

                    <?php if (isset($_SESSION['error_message'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <?= $_SESSION['error_message']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION['error_message']); ?>
                    <?php endif; ?>

                    <form action="<?= base_url('admin/aboutus/' . $about['id']) ?>" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="_method" value="PUT">

                        <div class="mb-3">
                            <label>Title</label>
                            <input class="form-control" type="text" name="title" value="<?= $about['title'] ?>">
                        </div>

                        <div class="mb-3">
                            <label>Description</label>
                            <input class="form-control" type="text" name="description" value="<?= $about['description'] ?>">
                        </div>

                        <div class="mb-3">
                            <label>Vision</label>
                            <input class="form-control" type="text" name="vision" value="<?= $about['vision'] ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mission</label>
                            <textarea class="form-control" name="mission" rows="5"><?= $about['mission'] ?></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Images (Max 3)</label>

                            <div class="row g-3">
                                <?php
                                for ($i = 0; $i < 3; $i++):
                                    $img = isset($about['images'][$i]) ? $about['images'][$i] : null;
                                    $uniqueId = $i;
                                ?>
                                    <div class="col-12 col-md-4">
                                        <div class="card h-100 border">

                                            <?php if ($img): ?>
                                                <div class="position-relative">
                                                    <img id="preview-exist-<?= $uniqueId ?>" src="<?= $img['image_name'] ?>" class="card-img-top"
                                                        style="width: 100%; aspect-ratio: 5/4; object-fit: cover;">
                                                    
                                                    <div class="badge bg-primary position-absolute top-0 start-0 m-2">Slot <?= $i + 1 ?></div>
                                                </div>
                                                <div class="card-body p-3">
                                                    <label class="form-label small fw-bold text-muted">Ganti Gambar Ini</label>
                                                    <input class="form-control form-control-sm" type="file"
                                                        name="update_images[<?= $img['id'] ?>]"
                                                        accept="image/*"
                                                        onchange="previewExisting(this, 'preview-exist-<?= $uniqueId ?>')">
                                                </div>

                                            <?php else: ?>
                                                <div class="card-body p-0 position-relative bg-light" style="width: 100%; aspect-ratio: 2/3;">

                                                    <div id="placeholder-box-<?= $uniqueId ?>" class="d-flex flex-column justify-content-center align-items-center h-100 w-100">
                                                        <i class="bi bi-image text-muted mb-2" style="font-size: 2rem;"></i>
                                                        <span class="small text-muted">Slot <?= $i + 1 ?> Kosong</span>
                                                    </div>

                                                    <img id="preview-new-<?= $uniqueId ?>" src="#" class="d-none position-absolute top-0 start-0"
                                                        style="width: 100%; height: 100%; object-fit: cover;">
                                                </div>

                                                <div class="card-footer p-3 bg-white border-top-0">
                                                    <label class="form-label small fw-bold text-success">Upload Baru</label>
                                                    <input class="form-control form-control-sm" type="file"
                                                        name="new_images[]"
                                                        accept="image/*"
                                                        onchange="previewNew(this, 'preview-new-<?= $uniqueId ?>', 'placeholder-box-<?= $uniqueId ?>')">
                                                </div>
                                            <?php endif; ?>

                                        </div>
                                    </div>
                                <?php endfor; ?>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="<?= base_url('admin/aboutus') ?>" class="btn btn-secondary">Back</a>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </section>
</div>

<?php
ob_start();
?>
<script>
    function previewExisting(input, imgId) {
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(imgId).src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    }

    function previewNew(input, imgId, placeholderId) {
        const file = input.files[0];
        const imgPreview = document.getElementById(imgId);
        const placeholder = document.getElementById(placeholderId);

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imgPreview.src = e.target.result;
                imgPreview.classList.remove('d-none');
                placeholder.classList.add('d-none');
            }
            reader.readAsDataURL(file);
        } else {
            imgPreview.src = '#';
            imgPreview.classList.add('d-none');
            placeholder.classList.remove('d-none');
        }
    }
</script>
<?php
$pageScripts = ob_get_clean();
?>