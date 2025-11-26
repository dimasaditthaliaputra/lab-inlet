<div class="page-heading">
    <h3 class="page-title"><?php echo e($title ?? 'Profile'); ?></h3>
    <p>Your profile data</p>
</div>

<div class="page-content">
    <section class="row">
        <div class="col-12">
            <div class="card border">
                <div class="card-body">
                    <form id="formData">
                        <input type="hidden" value="<?= e($user['id']) ?>" name="primary_id" id="primary_id">
                        <div class="row mb-3">
                            <label for="username" class="col-2 col-form-label fw-bold">Username</label>
                            <div class="col-10">
                                <input
                                    type="text"
                                    name="username"
                                    id="username"
                                    class="form-control"
                                    value="<?= e($user['username']) ?>"
                                    readonly />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="email" class="col-2 col-form-label fw-bold">Email</label>
                            <div class="col-10">
                                <input
                                    type="email"
                                    name="email"
                                    id="email"
                                    class="form-control"
                                    value="<?= e($user['email']) ?>" />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="full_name" class="col-12 col-md-2 col-form-label fw-bold">Full Name</label>
                            <div class="col-12 col-md-4">
                                <input
                                    type="text"
                                    name="full_name"
                                    id="full_name"
                                    class="form-control"
                                    value="<?= e($user['full_name']) ?>" />
                            </div>

                            <label for="password" class="col-12 col-md-2 col-form-label fw-bold">Password</label>
                            <div class="col-12 col-md-4">
                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    class="form-control"
                                    aria-describedby="helpId" />
                                <small id="helpId" class="text-muted" style="font-size: 0.75rem;">
                                    Leave blank to keep current password.
                                </small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" id="btnSubmit" class="btn btn-primary">
                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                Save
                            </button>
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
    var audio = new Audio("<?php echo base_url('assets/audio/success.wav'); ?>");

    $(document).ready(function() {
        $('#formData').submit(function(e) {
            e.preventDefault();

            var btn = $('#btnSubmit');
            var spinner = btn.find('.spinner-border');

            btn.prop('disabled', true);
            spinner.removeClass('d-none');

            let id = $('#primary_id').val();
            let formData = $(this).serialize();
            let baseUrl = '<?php echo base_url('admin/profile'); ?>';
            let url = baseUrl + '/' + id;
            let method = 'PUT';

            $.ajax({
                url: url,
                type: method,
                data: formData,
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
                        })
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: res.message
                        })
                    }
                },
                error: function(xhr, status, error) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;

                        $('.text-danger').remove();

                        $.each(errors, function(key, value) {
                            let inputEl = $(`#${key}`);
                            if (inputEl.length) {
                                inputEl.after(`
                                <small class="text-danger d-block mt-1">
                                    ${value}
                                </small>
                            `);
                            }
                        });
                    } else {
                        console.error('Terjadi kesalahan server:', error);
                    }
                },
                complete: function() {
                    btn.prop('disabled', false);
                    spinner.addClass('d-none');
                }
            });
        });
    });
</script>
<?php
$pageScripts = ob_get_clean();
?>