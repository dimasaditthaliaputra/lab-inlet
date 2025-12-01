<?php
$product = $data ?? null;
$isEdit  = !empty($product);

$formAction = $isEdit
    ? base_url('admin/product/' . $product->id)
    : base_url('admin/product/store');

$initialFeatures = [];
$initialSpecs    = [];

if ($isEdit && $product->feature) {
    $decoded = json_decode($product->feature, true);
    if (is_array($decoded)) {
        $initialFeatures = $decoded;
    }
}

if ($isEdit && $product->specification) {
    $decoded = json_decode($product->specification, true);
    if (is_array($decoded)) {
        $initialSpecs = $decoded; // assoc: name => value
    }
}
?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3><?php echo e($title ?? 'Product'); ?></h3>
                <p class="text-subtitle text-muted">
                    Form to <?= $isEdit ? 'edit' : 'create' ?> product.
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
                    <h4 class="card-title"><?= $isEdit ? 'Edit Product' : 'Create Product' ?></h4>
                </div>
                <div class="card-body">
                    <form id="formData" enctype="multipart/form-data">
                        <?php if ($isEdit): ?>
                            <input type="hidden" name="id" value="<?= $product->id ?>">
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="product_name" class="form-label required">Product Name</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="product_name"
                                        id="product_name"
                                        placeholder="Nama produk"
                                        value="<?= $isEdit ? e($product->product_name) : '' ?>">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea
                                        class="form-control"
                                        name="description"
                                        id="description"
                                        rows="3"
                                        placeholder="Deskripsi produk (optional)"><?= $isEdit ? e($product->description) : '' ?></textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="release_date" class="form-label required">Release Date</label>
                                    <input
                                        type="date"
                                        class="form-control"
                                        name="release_date"
                                        id="release_date"
                                        value="<?= $isEdit && $product->release_date ? date('Y-m-d', strtotime($product->release_date)) : '' ?>">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="image" class="form-label">Image</label>
                                    <input
                                        type="file"
                                        class="form-control"
                                        name="image"
                                        id="image"
                                        accept="image/*">

                                    <?php if ($isEdit): ?>
                                        <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar.</small>
                                    <?php endif; ?>

                                    <div class="mt-2">
                                        <?php
                                        $imageUrl = $isEdit && !empty($product->image_name)
                                            ? base_url('uploads/product/' . $product->image_name)
                                            : '';
                                        $displayStyle = ($isEdit && !empty($product->image_name)) ? '' : 'display: none;';
                                        ?>
                                        <img
                                            id="img-preview"
                                            src="<?= $imageUrl ?>"
                                            alt="Image Preview"
                                            class="img-thumbnail"
                                            style="max-width: 200px; max-height: 200px; <?= $displayStyle ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- Features -->
                            <div class="col-12 mt-2">
                                <div class="card border">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <span class="fw-bold">Features</span>
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="addFeatureBtn">
                                            + Tambah Feature
                                        </button>
                                    </div>
                                    <div class="card-body" id="featuresContainer">
                                        <!-- items akan di-generate oleh JS -->
                                    </div>
                                </div>
                            </div>

                            <!-- Specifications -->
                            <div class="col-12 mt-3">
                                <div class="card border">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <span class="fw-bold">Specifications</span>
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="addSpecBtn">
                                            + Tambah Spec
                                        </button>
                                    </div>
                                    <div class="card-body" id="specsContainer">
                                        <!-- items akan di-generate oleh JS -->
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-4">
                                <div class="d-flex justify-content-between">
                                    <a href="<?= base_url('admin/product') ?>" class="btn btn-secondary">Back</a>

                                    <button type="submit" class="btn btn-primary" id="btnSave">
                                        <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                                        <i class="fa fa-save"></i> Save
                                    </button>
                                </div>
                            </div>
                        </div> <!-- /row -->
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
    var audio = new Audio("<?php echo base_url('assets/audio/success.wav'); ?>");

    let featureCount = 0;
    let specCount    = 0;

    function addFeatureRow(value = '') {
        featureCount++;
        const html = `
            <div class="feature-item mb-2" id="feature-${featureCount}">
                <div class="input-group">
                    <input type="text" class="form-control" name="feature[]" placeholder="Contoh: Real-time Monitoring" value="${value ? value.replace(/"/g, '&quot;') : ''}">
                    <button type="button" class="btn btn-outline-danger" onclick="removeElement('feature-${featureCount}')">Hapus</button>
                </div>
            </div>
        `;
        document.getElementById('featuresContainer').insertAdjacentHTML('beforeend', html);
    }

    function addSpecRow(name = '', value = '') {
        specCount++;
        const html = `
            <div class="spec-item mb-2" id="spec-${specCount}">
                <div class="row g-2">
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="spec_name[]" placeholder="Nama Spec (Contoh: RAM)" value="${name ? name.replace(/"/g, '&quot;') : ''}">
                    </div>
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="spec_value[]" placeholder="Nilai (Contoh: 8GB)" value="${value ? value.replace(/"/g, '&quot;') : ''}">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-outline-danger w-100" onclick="removeElement('spec-${specCount}')">Hapus</button>
                    </div>
                </div>
            </div>
        `;
        document.getElementById('specsContainer').insertAdjacentHTML('beforeend', html);
    }

    function removeElement(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    $(document).ready(function() {
        // preview gambar
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

        // tombol add feature/spec
        $('#addFeatureBtn').on('click', function() {
            addFeatureRow();
        });

        $('#addSpecBtn').on('click', function() {
            addSpecRow();
        });

        // inisialisasi dari PHP (edit mode)
        let initialFeatures = <?= json_encode($initialFeatures ?: []) ?>;
        let initialSpecs    = <?= json_encode($initialSpecs ?: new stdClass()) ?>;

        if (initialFeatures.length) {
            initialFeatures.forEach(function(f) {
                addFeatureRow(f);
            });
        } else {
            addFeatureRow();
        }

        if (initialSpecs && Object.keys(initialSpecs).length) {
            Object.keys(initialSpecs).forEach(function(key) {
                addSpecRow(key, initialSpecs[key]);
            });
        } else {
            addSpecRow();
        }

        // submit
        $('#formData').submit(function(e) {
            e.preventDefault();

            var btn     = $('#btnSave');
            var spinner = btn.find('.spinner-border');
            var icon    = btn.find('i');

            btn.prop('disabled', true);
            icon.addClass('d-none');
            spinner.removeClass('d-none');

            var form     = this;
            var formData = new FormData(form);

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
                            window.location.href = '<?php echo base_url('admin/product'); ?>';
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

                            if (key === 'image') {
                                inputEl = $('#image');
                            }

                            if (inputEl.length) {
                                inputEl.after(`
                                    <small class="text-danger d-block mt-1" style="font-size: 14px;">
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
                    btn.prop('disabled', false);
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
