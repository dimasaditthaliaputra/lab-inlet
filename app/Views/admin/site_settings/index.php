<?php ob_start(); ?>
<link rel="stylesheet" href="<?= asset('assets/mazer/extensions/summernote/summernote-lite.min.css') ?>">

<style>
    .note-editor .dropdown-toggle::after {
        display: none;
    }
</style>
<?php $pageStyle = ob_get_clean(); ?>

<?php
// Pastikan $site adalah object atau null
$site = $data ?? null;
?>

<div class="page-heading">
    <h3><?= $title ?></h3>
    <p class="text-subtitle text-muted">Form to update Site Settings.</p>
</div>

<div class="page-content">
    <section class="row">
        <div class="col-12">
            <div class="card border">
                <div class="card-header">
                    <h4 class="card-title">Site Settings Form</h4>
                </div>
                <div class="card-body">
                    <form id="formData" enctype="multipart/form-data">

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label required">Site Name</label>
                                <input type="text" class="form-control" name="site_name"
                                    value="<?= $site->site_name ?? '' ?>">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label required">Email</label>
                                <input type="email" class="form-control" name="email"
                                    value="<?= $site->email ?? '' ?>">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control" name="phone"
                                    value="<?= $site->phone ?? '' ?>">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Address</label>
                                <input type="text" class="form-control" name="address" id="address" value="<?= $site->address ?? '' ?>"></input>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Map Embed URL</label>
                                <textarea class="form-control" name="map_embed_url"><?= $site->map_embed_url ?? '' ?></textarea>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Logo</label>
                                <input type="file" name="logo_path" class="form-control" id="logo_path" accept="image/*">

                                <?php
                                // UBAH: Cek property object
                                $logoPath = $site->logo_path ?? '';
                                $logoUrl = !empty($logoPath) ? base_url('uploads/settings/' . $logoPath) : '';
                                $logoDisplay = !empty($logoPath) ? '' : 'display:none;';
                                ?>
                                <img src="<?= $logoUrl ?>" class="img-thumbnail mt-2" id="logo-preview"
                                    style="max-width:150px; <?= $logoDisplay ?>">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Favicon</label>
                                <input type="text" class="form-control" name="favicon"
                                    value="<?= $site->favicon ?? '' ?>">
                            </div>

                            <h6 class="mt-3">Social Links</h6>
                            <?php
                            // Parsing JSON dari object properti
                            $social = json_decode($site->social_links ?? '{}');
                            ?>
                            <div class="col-md-12 mb-3">
                                <input type="text" class="form-control" name="facebook"
                                    placeholder="Facebook URL"
                                    value="<?= $social->facebook ?? '' ?>">

                                <input type="text" class="form-control mt-2" name="instagram"
                                    placeholder="Instagram URL"
                                    value="<?= $social->instagram ?? '' ?>">

                                <input type="text" class="form-control mt-2" name="youtube"
                                    placeholder="YouTube URL"
                                    value="<?= $social->youtube ?? '' ?>">
                            </div>

                            <?php if (in_array('update', $access)): ?>
                                <div class="d-flex justify-content-end mt-4">
                                    <button type="submit" class="btn btn-primary px-4 py-2" id="btnSubmit">
                                        <span class="spinner-border spinner-border-sm me-2 d-none"></span>
                                        <i class="fas fa-save"></i> Save
                                    </button>
                                </div>
                            <?php endif; ?>
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
        function showPreview(input, previewID) {
            let file = input.files[0]
            if (file) {
                let reader = new FileReader()
                reader.onload = e => {
                    $(previewID).attr('src', e.target.result).show()
                }
                reader.readAsDataURL(file)
            }
        }

        $('#logo_path').change(function() {
            showPreview(this, '#logo-preview')
        });

        $('#formData').submit(function(event) {
            event.preventDefault()
            let btn = $('#btnSubmit')
            let spinner = btn.find('.spinner-border')
            let icon = btn.find('i')

            btn.prop('disabled', true)
            spinner.removeClass('d-none')
            icon.addClass('d-none')

            let formData = new FormData(this)

            $.ajax({
                url: "<?= base_url('admin/site-settings/store') ?>",
                method: 'POST',
                data: formData,
                dataType: 'JSON',
                processData: false,
                contentType: false,

                success: function(res) {
                    if (res.success) {
                        audio.play()
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: res.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.reload()
                        })
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Validation Failed',
                            text: res.message
                        })
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        $('.text-danger').remove()
                        let errors = xhr.responseJSON.errors

                        $.each(errors, function(key, value) {
                            let inputEl = $(`[name="${key}"]`)
                            if (inputEl.length) {
                                inputEl.after(`
                                    <small class="text-danger d-block mt-1">${value}</small>
                                `)
                            }
                        })

                        Swal.fire('Warning', 'Periksa input kamu!', 'warning')
                    } else {
                        Swal.fire('Error', 'Server error', 'error')
                    }
                },
                complete: function() {
                    btn.prop('disabled', false)
                    spinner.addClass('d-none')
                    icon.removeClass('d-none')
                }
            })
        })

    })
</script>
<?php $pageScripts = ob_get_clean(); ?>