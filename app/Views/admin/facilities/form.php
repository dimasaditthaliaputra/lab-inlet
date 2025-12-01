<?php ob_start(); ?>
<link rel="stylesheet" href="<?= asset('assets/mazer/extensions/summernote/summernote-lite.min.css') ?>">

<style>
    .note-editor .dropdown-toggle::after {
        display: none;
    }
</style>
<?php $pageStyle = ob_get_clean(); ?>

<?php
$facility = $data ?? null;
$isEdit = !empty($facility);

$formAction = $isEdit && isset($facility->id)
    ? base_url('admin/facilities/' . $facility->id)
    : base_url('admin/facilities');


?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3><?= $title ?></h3>
                <p class="text-subtitle text-muted">
                    <?= $isEdit ? 'Form to edit facility.' : 'Form to create facility.' ?>
                </p>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="row">
        <div class="col-12">
            <div class="card border">
                <div class="card-header">
                    <h4 class="card-title"><?= $isEdit ? 'Edit Facility' : 'Create Facility' ?></h4>
                </div>

                <div class="card-body">
                    <form id="formData" enctype="multipart/form-data">
                        <?php if ($isEdit && !empty($facility->id)) : ?>
                            <input type="hidden" name="id" value="<?= htmlspecialchars($facility->id) ?>">
                        <?php endif; ?>

                        <div class="row">

                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label class="form-label required">Name</label>
                                    <input type="text" name="name" class="form-control"
                                        value="<?= htmlspecialchars($facility->name ?? '') ?>"
                                        placeholder="Facility Name">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label class="form-label required">Description</label>
                                    <input type="text" name="description" class="form-control"
                                        value="<?= htmlspecialchars($facility->description ?? '') ?>"
                                        placeholder="Facility Description">
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="id_roles" class="form-label required">Condition</label>
                                <div class="col-md-14">
                                    <select class="form-select" name="id" id="id" required>
                                        <option value=""> Select Condition  </option>
                                        <option value="good">Good</option>
                                        <option value="bad">Bad</option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label class="form-label <?= $isEdit ? '' : 'required' ?>">Image</label>

                                    <input type="file" name="image" id="image"
                                        class="form-control" accept="image/*">

                                    <?php if ($isEdit): ?>
                                        <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar.</small>
                                    <?php endif; ?>

                                    <?php
                                    $imageUrl = $isEdit && !empty($facility->image_name)
                                        ? base_url('uploads/facilities/' . $facility->image_name)
                                        : '';


                                    $styleDisplay = $imageUrl ? '' : 'display:none;';
                                    ?>
                                    <div class="mt-2">
                                        <img src="<?= $imageUrl ?>" id="img-preview"
                                            class="img-thumbnail"
                                            style="max-width:200px; max-height:200px; <?= $styleDisplay ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label class="form-label required">Quantity</label>
                                    <input type="number" name="qty" class="form-control"
                                        value="<?= $facility->qty ?? '' ?>" placeholder="Quantity">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="<?= base_url('admin/facilities') ?>"
                                        class="btn btn-secondary">Back</a>

                                    <button type="submit" class="btn btn-primary" id="btnSubmit">
                                        <span class="spinner-border spinner-border-sm me-2 d-none"></span>
                                        <i class="fas fa-save"></i> Save
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>

            </div>
        </div>
    </section>
</div>

<?php ob_start(); ?>
<script src="<?= asset('assets/mazer/extensions/summernote/summernote-lite.min.js') ?>"></script>

<script>
    var audio = new Audio("<?= base_url('assets/audio/success.wav') ?>");

    $(document).ready(function() {

        // Summernote untuk Description
        $('#description').summernote({
            height: 250,
            placeholder: 'Write facility description...',
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link']]
            ]
        });

        // Image preview
        $('#image').change(function(e) {
            const file = e.target.files[0];
            if (!file) {
                $('#img-preview').hide().attr('src', '');
                return;
            }

            let reader = new FileReader();
            reader.onload = function(e) {
                $('#img-preview').attr('src', e.target.result).show();
            };
            reader.readAsDataURL(file);
        });

        // Submit form via AJAX
        $('#formData').submit(function(e) {
            e.preventDefault();

            let btn = $('#btnSubmit');
            let spinner = btn.find('.spinner-border');
            let icon = btn.find('i');

            btn.prop('disabled', true);
            spinner.removeClass('d-none');
            icon.addClass('d-none');

            let formData = new FormData(this);

            <?php if ($isEdit && !empty($facility->id)): ?>
                formData.append('_method', 'PUT');
            <?php endif; ?>

            $.ajax({
                url: "<?= $formAction ?>",
                type: "POST",
                data: formData,
                dataType: "JSON",
                processData: false,
                contentType: false,

                success: function(res) {
                    if (res.success) {
                        audio.play();
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: res.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = "<?= base_url('admin/facilities') ?>";
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: res.message
                        });
                    }
                },

                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;

                        $('.text-danger').remove();

                        $.each(errors, function(key, value) {
                            let el = $(`[name="${key}"]`);

                            if (key === 'description') {
                                el = el.next('.note-editor');
                            }

                            el.after(`<small class="text-danger d-block mt-1">${value}</small>`);
                        });

                        Swal.fire({
                            icon: 'warning',
                            title: 'Validation Failed',
                            text: 'Please check your input.'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Server Error',
                            text: 'Something went wrong on the server.'
                        });
                    }
                },

                complete: function() {
                    btn.prop('disabled', false);
                    spinner.addClass('d-none');
                    icon.removeClass('d-none');
                }
            });

        });

    });
</script>
<?php $pageScripts = ob_get_clean(); ?>