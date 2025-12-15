<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?? $_ENV['APP_NAME'] ?></title>
    <link rel="stylesheet" href="<?= asset('assets/mazer/compiled/css/app.css') ?>">
    <link rel="stylesheet" href="<?= asset('assets/mazer/compiled/css/app-dark.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">

    <style>
        body {
            height: 100vh;
            overflow: hidden;
        }
    </style>
</head>

<body>
    <section class="h-100 d-flex flex-column align-items-center justify-content-center">
        <div class="container-fluid">
            <div class="row d-flex justify-content-center align-items-center">
                <div class="col-md-9 col-lg-6 col-xl-5">
                    <lottie-player
                        src="<?= base_url('assets/lottie/Login.json') ?>"
                        background="transparent"
                        speed="1"
                        class="img-fluid" loop
                        autoplay>
                    </lottie-player>
                </div>
                <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                    <form id="formData">
                        <div class="mb-3 text-center">
                            <h2 class="fw-bold fs-1">Sign In</h2>
                        </div>
                        <div data-mdb-input-init class="form-outline mb-4">
                            <input type="text"
                                id="form3Example3"
                                class="form-control form-control-lg"
                                placeholder="Enter Your Username"
                                name="username" />
                        </div>

                        <div data-mdb-input-init class="form-outline mb-3">
                            <input type="password"
                                id="form3Example4"
                                class="form-control form-control-lg"
                                placeholder="Enter password"
                                name="password" />
                        </div>

                        <div class="d-flex column align-items-start mb-4">
                            <span class="error-message text-danger small">
                            </span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <div class="form-check mb-0">
                                <input class="form-check-input me-2"
                                    type="checkbox"
                                    value="true"
                                    id="form2Example3"
                                    name="remember_me" />
                                <label class="form-check-label" for="form2Example3">
                                    Remember me
                                </label>
                            </div>
                            <a href="#!" class="text-body">Forgot password?</a>
                        </div>

                        <div class="text-center text-lg-start mt-4 pt-2">
                            <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg" id="btnLogin"
                                style="padding-left: 2.5rem; padding-right: 2.5rem;">
                                <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                                Login
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script src="<?= base_url('assets/mazer/compiled/js/app.js') ?>"></script>
    <script src="<?= base_url('assets/mazer/static/js/initTheme.js') ?>"></script>
    <script src="<?= base_url('assets/mazer/static/js/components/dark.js') ?>"></script>

    <script src="<?= base_url('assets/mazer/extensions/jquery/jquery.min.js') ?>"></script>

    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

    <script>
        $('#formData').submit(function(e) {
            console.log('DOM siap. jQuery berhasil dimuat.');

            e.preventDefault();

            let formData = $(this).serialize();

            const btnSubmit = $('#btnLogin');
            const spinner = btnSubmit.find('.spinner-border');
            const errorCatch = $('.error-message');

            btnSubmit.prop('disabled', true);
            spinner.removeClass('d-none');
            errorCatch.html('');

            $.ajax({
                url: '<?= base_url('/login/process') ?>',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(res) {
                    if (res.success) {
                        if (res.redirect_url) {
                            window.location.href = '/' + res.redirect_url;
                        } else {
                            window.location.href = '/mahasiswa/dashboard';
                        }
                    } else {
                        errorCatch.html(res.message);
                    }
                },
                error: function(xhr, status, error) {
                    let errorMessage;

                    switch (xhr.status) {
                        case 404:
                            errorMessage = 'Error: Halaman proses login tidak ditemukan (404).';
                            break;
                        case 0:
                            errorMessage = 'Server terlalu lama merespon (timeout).';
                            break;
                        default:
                            errorMessage = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Terjadi kesalahan. Silahkan coba lagi.';
                    }

                    errorCatch.html(errorMessage);
                },
                complete: function() {
                    btnSubmit.prop('disabled', false);
                    spinner.addClass('d-none');
                }
            });
        });
    </script>
</body>

</html>