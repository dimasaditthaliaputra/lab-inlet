<?php ob_start(); ?>
<link rel="stylesheet" href="<?= asset('assets/mazer/extensions/summernote/summernote-lite.min.css') ?>">

<style>
    .note-editor .dropdown-toggle::after {
        display: none;
    }
</style>
<?php
$pageStyle = ob_get_clean();
?>

<?php
$news = $data ?? null;
$isEdit = !empty($news);

$formAction = $isEdit ? base_url('admin/news/' . $news->id) : base_url('admin/news/store');
?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3><?php echo e($title ?? 'News'); ?></h3>
                <p class="text-subtitle text-muted">Form to create news.</p>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="row">
        <div class="col-12">
            <div class="card border">
                <div class="card-header">
                    <h4 class="card-title"><?= $isEdit ? 'Edit News' : 'Create News' ?></h4>
                </div>
                <div class="card-body">
                    <form id="formData" enctype="multipart/form-data">
                        <?php if ($isEdit): ?>
                            <input type="hidden" name="id" value="<?= $news->id ?>">
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="title" class="form-label required">Title</label>
                                    <input type="text" class="form-control" name="title" id="title"
                                        placeholder="Title"
                                        value="<?= $isEdit ? $news->title : '' ?>">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="image" class="form-label <?= $isEdit ? '' : 'required' ?>">Image</label>

                                    <input type="file" class="form-control" name="image" id="image" placeholder="Image" accept="image/*">

                                    <?php if ($isEdit): ?>
                                        <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar.</small>
                                    <?php endif; ?>

                                    <div class="mt-2">
                                        <?php
                                        // Logic URL Gambar (Sesuaikan path 'uploads/news/' dengan folder asli Anda)
                                        $imageUrl = $isEdit && !empty($news->image_name)
                                            ? base_url('uploads/news/' . $news->image_name)
                                            : '';
                                        $displayStyle = ($isEdit && !empty($news->image_name)) ? '' : 'display: none;';
                                        ?>
                                        <img id="img-preview" src="<?= $imageUrl ?>"
                                            alt="Image Preview" class="img-thumbnail"
                                            style="max-width: 200px; max-height: 200px; <?= $displayStyle ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="content" class="form-label required">Content</label>
                                    <textarea class="form-control" name="content" id="content" placeholder="Content"><?= $isEdit ? $news->content : '' ?></textarea>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="<?= base_url('admin/news') ?>" class="btn btn-secondary">Back</a>

                                    <div class="d-flex gap-2">
                                        <button type="submit" name="status" value="draft" class="btn btn-light-secondary me-1" id="btnDraft">
                                            <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                                            <i class="fa fa-save"></i> Save Draft
                                        </button>

                                        <button type="submit" name="status" value="published" class="btn btn-primary" id="btnPublish">
                                            <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                                            <i class="fas fa-paper-plane"></i> Publish
                                        </button>
                                    </div>
                                </div>
                            </div>
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
<script src="<?= asset('assets/mazer/extensions/summernote/summernote-lite.min.js') ?>"></script>

<script>
    var audio = new Audio("<?php echo base_url('assets/audio/success.wav'); ?>");

    $(document).ready(function() {
        $('#content').summernote({
            height: 300,
            tabsize: 2,
            placeholder: 'Type something...',
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'picture']],
                ['view', ['fullscreen', 'help']]
            ],
            fontSizes: ['8', '10', '12', '14', '16', '18', '24', '36']
        });

        $('#image').change(function(e) {
            var file = e.target.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#img-preview').attr('src', e.target.result).show();
                }
                reader.readAsDataURL(file);
            } else {
                $('#img-preview').hide().attr('src', '');
            }
        });

        $('#formData').submit(function(e) {
            e.preventDefault();

            var btnClicked = $(e.originalEvent.submitter);
            var statusValue = btnClicked.val();

            var spinner = btnClicked.find('.spinner-border');
            var icon = btnClicked.find('i');

            $('button[type="submit"]').prop('disabled', true);
            icon.addClass('d-none');
            spinner.removeClass('d-none');

            var form = this;
            var formData = new FormData(form);

            formData.append('status', statusValue);

            let actionUrl = '<?= $formAction ?>';

            $.ajax({
                url: actionUrl,
                type: 'POST',
                data: formData,
                dataType: 'JSON',
                processData: false,
                contentType: false,

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
                            window.location.href = '<?php echo base_url('admin/news'); ?>';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: res.message
                        });
                    }
                },
                error: function(xhr, status, error) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;

                        $('.text-danger').remove();

                        $.each(errors, function(key, value) {
                            let inputEl = $(`[name="${key}"]`);

                            if (key === 'content') {
                                inputEl = inputEl.next('.note-editor');
                            }

                            if (key === 'image') {
                                let previewDiv = inputEl.next('.mt-2');

                                if (previewDiv.length) {
                                    inputEl = previewDiv;
                                }
                            }

                            if (inputEl.length) {
                                inputEl.after(`
                                    <small class="text-danger d-block mt-1" style="font-size: 16px;">
                                        ${value}
                                    </small>
                                `);
                            }
                        });

                        Swal.fire({
                            icon: 'warning',
                            title: 'Validasi Gagal',
                            text: 'Mohon periksa kembali inputan anda.'
                        });

                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Server Error',
                            text: 'Terjadi kesalahan pada server.'
                        });
                        console.error(error);
                    }
                },
                complete: function() {
                    $('button[type="submit"]').prop('disabled', false);
                    icon.removeClass('d-none');
                    spinner.addClass('d-none');
                }
            });
        });
    });
</script>
<?php
$pageScripts = ob_get_clean();
?>