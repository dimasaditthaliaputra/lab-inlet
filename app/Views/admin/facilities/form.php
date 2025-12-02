<?php
$facilities = $data ?? null;
$isEdit = !empty($facilities);

$formAction = $isEdit ? base_url('admin/facilities/' . $facilities->id) : base_url('admin/facilities/store');
?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3><?php echo e($title ?? 'facilities'); ?></h3>
                <p class="text-subtitle text-muted">Form to create Facilities.</p>
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
                         <?php if ($isEdit): ?>
                            <input type="hidden" name="id" value="<?= $facilities->id ?>">
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label required">Name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="name" value="<?= $isEdit ? e($facilities->name) : '' ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        <label for="description" class="form-label required">Description</label>
                                        <input type="text" class="form-control" id="description" name="description"
                                            placeholder="description" value="<?= $isEdit ? e($facilities->description) : '' ?>">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <div class="col-6">
                                        <label for="condition" class="form-label required">Condition</label>
                                        <div class="col-md-14">
                                            <select class="form-select" name="condition" id="condition" required>
                                                <option value="">Select Condition</option>
                                                <option value="good" <?= $isEdit && $facilities->condition == 'good' ? 'selected' : '' ?>>Good</option>
                                                <option value="bad" <?= $isEdit && $facilities->condition == 'bad' ? 'selected' : '' ?>>Bad</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-3">
                                        <div class="form-group mb-3">
                                            <label class="form-label required">Quantity</label>
                                            <input type="number" name="qty" class="form-control"
                                                value="<?= $isEdit ? e($facilities->qty) : '' ?>"
                                                placeholder="Quantity">
                                        </div>
                                    </div>
                                </div>


                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        <label class="form-label <?= $isEdit ? '' : 'required' ?>">Image</label>
                                        <input type="file" class="form-control" name="image_name" id="image_name" accept="image/*">
                                        <?php if ($isEdit && $facilities->image_name): ?>
                                            <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar.</small>
                                        <?php endif; ?>
                                        <div class="mt-2">
                                            <?php
                                            $imageUrl = $isEdit && !empty($facilities->image_name) ? base_url('uploads/facilities/' . $facilities->image_name) : '';
                                            $displayStyle = ($isEdit && !empty($facilities->image_name)) ? '' : 'display: none;';
                                            ?>
                                            <img id="img-preview" src="<?= $imageUrl ?>" alt="Image Preview" class="img-thumbnail" style="max-width:200px; <?= $displayStyle ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 mt-3 d-flex justify-content-end">
                                    <a href="<?= base_url('admin/facilities') ?>" class="btn btn-secondary">Back</a>
                                    <button type="submit" class="btn btn-primary ms-2" id="btnSubmit">
                                        <span class="spinner-border spinner-border-sm d-none me-2"></span>
                                        Save
                                    </button>
                                </div>

                            </div>
                    </form>
                </div>

            </div>
        </div>
    </section>
</div>

<?php ob_start(); ?>
<script>
    var audio = new Audio("<?= base_url('assets/audio/success.wav') ?>");

    $(document).ready(function() {
        $('#image_name').change(function(e) {
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
                            window.location.href = '<?php echo base_url('admin/facilities'); ?>';
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
<?php $pageScripts = ob_get_clean();
?>