<?php ob_start(); ?>

<style>
    /* START OF FIX: CSS untuk meratakan ikon di nav-link */

    /* Menjaga styling dasar ikon Bootstrap */
    .bi,
    [class^="bi-"],
    [class*=" bi-"] {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        line-height: 1 !important;
        vertical-align: middle !important;
    }

    .bi::before,
    [class^="bi-"]::before,
    [class*=" bi-"]::before {
        display: inline-block !important;
        margin: 0 !important;
    }

    /* END OF FIX: CSS untuk meratakan ikon di nav-link */


    /* 1. General Softening & Shadow */
    .card-modern {
        border-radius: 1.5rem !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        border: none !important;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
    }

    /* 2. Custom Tabs (Segmented Control / Pill Tabs) */
    .nav-pills-modern .nav-link {
        /* FIX APPLIED HERE: Menggunakan Flexbox untuk centering vertikal */
        display: flex !important;
        align-items: center !important;

        border-radius: 50px;
        padding: 8px 20px;
        color: #6c757d;
        background-color: transparent;
        transition: background-color 0.3s ease, color 0.3s ease, box-shadow 0.3s ease;
        margin-right: 8px;
        font-weight: 500;
    }

    /* FIX APPLIED HERE: Menambahkan margin kanan pada ikon di dalam nav-link */
    .nav-pills-modern .nav-link i.bi {
        margin-right: 0.25rem !important;
    }


    .nav-pills-modern .nav-link.active {
        background-color: var(--var-primary) !important;
        color: white;
        box-shadow: 0 4px 10px rgba(102, 126, 234, 0.4);
    }

    .nav-pills-modern {
        border-bottom: none !important;
        padding-bottom: 15px;
        display: flex;
        flex-wrap: wrap;
    }

    /* 3. Form Inputs & Textarea */
    .form-control-modern {
        border-radius: 10px;
        padding: 10px 15px;
        border-color: #e0e0e0;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .form-control-modern:focus {
        border-color: var(--var-primary);
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
    }

    .form-control-modern[readonly] {
        background-color: #f8f9fa !important;
        opacity: 0.8;
    }

    /* 4. Buttons */
    .btn-pill {
        border-radius: 50px !important;
        padding: 8px 20px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    /* Kustomisasi Btn-Primary (Tanpa Gradient, Mirip Tab Aktif) */
    .btn-primary {
        background-color: var(--var-primary);
        border-color: var(--var-primary);
        box-shadow: 0 2px 5px rgba(102, 126, 234, 0.2);
    }

    .btn-primary:hover {
        background-color: #764ba2;
        border-color: #764ba2;
        box-shadow: 0 4px 10px rgba(102, 126, 234, 0.4);
        transform: translateY(-1px);
    }

    .btn-primary:focus {
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.5);
    }

    /* Action button in sidebar */
    .btn-outline-action {
        color: var(--var-primary);
        border-color: var(--var-primary);
        background-color: transparent;
    }

    .btn-outline-action:hover {
        background-color: var(--var-primary);
        color: white;
        box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3);
    }

    /* 5. Profile Picture Styling */
    .profile-avatar-lg {
        width: 130px;
        height: 130px;
        object-fit: cover;
        border: 4px solid #e0e0e0;
        box-shadow: 0 0 0 5px rgba(102, 126, 234, 0.1);
    }

    /* 6. Text Hierarchy & Spacing */
    .profile-info {
        padding: 15px 0;
        margin-top: 15px;
        border-top: 1px solid #f0f0f0;
        font-size: 0.75rem;
    }

    .text-title {
        font-weight: 700;
        color: #495057;
    }
</style>
<?php $pageStyle = ob_get_clean(); ?>

<div class="page-heading">
    <h3 class="mb-0 fw-bold"><?php echo e($title ?? 'Student Profile'); ?></h3>
    <small class="text-muted">Manage your account details and contact information.</small>
</div>

<div class="page-content mt-4">
    <section class="row">
        <div class="col-12">
            <div class="row g-4">

                <div class="col-md-4">
                    <div class="card card-modern text-center h-100 p-3">
                        <div class="card-body">
                            <img src="<?php echo asset('assets/mazer/static/images/faces/2.jpg'); ?>"
                                alt="Profile Picture"
                                class="rounded-circle mb-3 profile-avatar-lg">

                            <h4 class="fw-bold mb-1"><?php echo e($profile['full_name']); ?></h4>
                            <p class="text-muted mb-1">@<?php echo e($profile['username']); ?></p>

                            <span class="badge rounded-pill bg-primary mb-4" style="background-color: var(--var-primary) !important;"><?php echo e($profile['roles']['role_name']); ?></span>

                            <div class="text-start profile-info">
                                <p class="mb-2 text-title"><i class="bi bi-person-vcard me-2 text-primary"></i> Student ID:
                                    <span class="float-end fw-semibold" id="nim_display"><?php echo e($profile['mahasiswa']['nim']); ?></span>
                                </p>
                                <p class="mb-1 text-title"><i class="bi bi-building me-2 text-primary"></i> Study Program:
                                    <span class="float-end fw-semibold"><?php echo e($profile['mahasiswa']['study_program']); ?></span>
                                </p>
                            </div>

                            <button class="btn btn-pill btn-outline-action w-100 mt-4" data-bs-toggle="modal" data-bs-target="#modalPassword">
                                <i class="bi bi-key-fill me-1"></i> Change Password
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card card-modern p-3 h-100">
                        <div class="card-body">

                            <ul class="nav nav-pills nav-pills-modern mb-3" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="account-tab" data-bs-toggle="tab" data-bs-target="#account" type="button" role="tab" aria-controls="account" aria-selected="true">
                                        <i class="bi bi-gear-fill me-1"></i> Account Settings
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">
                                        <i class="bi bi-telephone-fill me-1"></i> Contact & Address
                                    </button>
                                </li>
                            </ul>

                            <form id="profileForm" class="mt-4">
                                <input type="hidden" name="_method" value="PUT">
                                <div class="tab-content" id="myTabContent">

                                    <div class="tab-pane fade show active" id="account" role="tabpanel" aria-labelledby="account-tab">

                                        <div class="mb-3">
                                            <label for="full_name" class="form-label">Full Name</label>
                                            <input type="text" class="form-control form-control-modern" name="full_name" id="full_name" value="<?php echo e($profile['full_name']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Username</label>
                                            <input type="text" class="form-control form-control-modern" name="username" id="username" value="<?php echo e($profile['username']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control form-control-modern" name="email" id="email" value="<?php echo e($profile['email']); ?>" required>
                                        </div>

                                        <div class="row mt-4 pt-3 border-top">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label text-muted">Entry Year (Read-only)</label>
                                                <input type="text" class="form-control form-control-modern" value="<?php echo e($profile['mahasiswa']['entry_year']); ?>" readonly>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label text-muted">Current Semester (Read-only)</label>
                                                <input type="text" class="form-control form-control-modern" value="<?php echo e($profile['mahasiswa']['current_semester']); ?>" readonly>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                                        <div class="mb-3">
                                            <label for="phone_number" class="form-label">Phone Number</label>
                                            <input type="text" class="form-control form-control-modern" name="phone_number" id="phone_number" value="<?php echo e($profile['mahasiswa']['phone_number']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="address" class="form-label">Full Address</label>
                                            <textarea class="form-control form-control-modern" name="address" id="address" rows="4"><?php echo e($profile['mahasiswa']['address']); ?></textarea>
                                        </div>
                                    </div>

                                </div>

                                <div class="d-flex justify-content-end mt-5">
                                    <button type="submit" class="btn btn-pill btn-primary" id="btnSaveProfile">
                                        <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                                        <i class="bi bi-save me-1"></i> Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="modalPassword" tabindex="-1" aria-labelledby="modalPasswordLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white" style="border-top-left-radius: 12px; border-top-right-radius: 12px; background-color: #ffc107 !important;">
                <h5 class="modal-title" id="modalPasswordLabel"><i class="bi bi-lock-fill me-1"></i> Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="passwordForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control form-control-modern" name="password" id="new_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control form-control-modern" name="confirm_password" id="confirm_password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-pill btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-pill btn-warning" id="btnSavePassword">
                        <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                        Save New Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php ob_start(); ?>
<script>
    const PROFILE_UPDATE_URL = '<?php echo base_url('mahasiswa/profile/update'); ?>';
    const CURRENT_USER_ID = '<?php echo e($profile['user_id']); ?>';

    var audio = new Audio("<?php echo base_url('assets/audio/success.wav'); ?>");

    $(document).ready(function() {

        $('#profileForm').submit(function(e) {
            e.preventDefault();

            var btn = $('#btnSaveProfile');
            var spinner = btn.find('.spinner-border');

            btn.prop('disabled', true);
            spinner.removeClass('d-none');
            $('.text-danger').remove();

            let formData = $(this).serializeArray();
            formData.push({
                name: 'password',
                value: ''
            });

            $.ajax({
                url: PROFILE_UPDATE_URL,
                type: `POST`,
                data: formData,
                dataType: 'JSON',
                success: function(res) {
                    if (res.success) {
                        audio.play();
                        Swal.fire('Success!', res.message, 'success');
                        $('#nim_display').text($('#nim').val());
                    } else {
                        handleFormErrors(res.errors);
                        Swal.fire('Failed!', res.message || 'Failed to save changes.', 'error');
                    }
                },
                error: function(xhr) {
                    let errorMessage = xhr.responseJSON?.message || 'An error occurred on the server.';
                    if (xhr.status === 422) {
                        handleFormErrors(xhr.responseJSON.errors);
                        errorMessage = 'Validation failed. Please check your input.';
                    }
                    Swal.fire('Error!', errorMessage, 'error');
                },
                complete: function() {
                    btn.prop('disabled', false);
                    spinner.addClass('d-none');
                }
            });
        });

        $('#passwordForm').submit(function(e) {
            e.preventDefault();

            var newPass = $('#new_password').val();
            var confPass = $('#confirm_password').val();

            if (newPass !== confPass) {
                Swal.fire('Warning', 'Password confirmation does not match!', 'warning');
                return;
            }

            var btn = $('#btnSavePassword');
            var spinner = btn.find('.spinner-border');
            btn.prop('disabled', true);
            spinner.removeClass('d-none');
            $('.text-danger').remove();

            let profileData = $('#profileForm').serializeArray();
            let updateData = profileData.filter(item => item.name !== 'password');

            updateData.push({
                name: 'password',
                value: newPass
            });

            if (updateData.filter(item => item.name === '_method').length === 0) {
                updateData.push({
                    name: '_method',
                    value: 'PUT'
                });
            }

            let finalData = [];
            $.each($('#profileForm').serializeArray(), function(i, field) {
                if (field.name !== 'password' && field.name !== 'confirm_password') {
                    finalData.push(field);
                }
            });
            finalData.push({
                name: 'password',
                value: newPass
            });
            if (finalData.filter(item => item.name === '_method').length === 0) {
                finalData.push({
                    name: '_method',
                    value: 'PUT'
                });
            }


            $.ajax({
                url: PROFILE_UPDATE_URL,
                type: `POST`, // <--- Dipertahankan sebagai POST, mengandalkan _method=PUT di data
                data: $.param(finalData), // Mengirimkan semua data yang valid + _method=PUT
                dataType: 'JSON',
                success: function(res) {
                    if (res.success) {
                        $('#modalPassword').modal('hide');
                        $('#passwordForm')[0].reset();
                        Swal.fire('Success!', 'Password updated successfully.', 'success');
                    } else {
                        Swal.fire('Failed!', res.message || 'Failed to update password.', 'error');
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error!', 'An error occurred while updating the password.', 'error');
                },
                complete: function() {
                    btn.prop('disabled', false);
                    spinner.addClass('d-none');
                }
            });
        });

        function handleFormErrors(errors) {
            $('.text-danger').remove();
            $.each(errors, function(key, value) {
                let inputEl = $(`#${key}`);
                if (inputEl.length) {
                    inputEl.after(`<small class="text-danger d-block mt-1">${value}</small>`);
                }
            });
        }
    });
</script>
<?php $pageScripts = ob_get_clean(); ?>