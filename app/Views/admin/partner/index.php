<div class="page-heading">
    <h3 class="page-title"><?php echo e($title ?? 'Partner'); ?></h3>
    <p>Partner who’s always ready to support your growth and innovation.</p>
</div>

<div class="page-content">
    <section class="row">
        <div class="col-12">
            <div class="card border">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title">List Partner</h4>
                        <?php if (in_array('create', $access)) : ?>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalForm" data-url="">
                                <i class="bi bi-plus me-1" role="img" aria-label="Add new partner"></i>
                                Add New Partner
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="data-tables">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Name Partner</th>
                                    <th>Logo</th>
                                    <th>Url</th>
                                    <th width="15%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div
        class="modal fade"
        id="modalForm"
        tabindex="-1"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
        role="dialog"
        aria-labelledby="modalTitleId"
        aria-hidden="true">
        <div
            class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg"
            role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitleId">
                        Form Partner
                    </h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="formData">
                    <div class="modal-body">
                        <input type="hidden" name="primary_id" id="primary_id">

                        <div class="row mb-3 align-items-center">
                            <label for="partner_name" class="col-md-3 col-form-label required">Partner Name</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="partner_name" id="partner_name" placeholder="Masukkan nama partner">
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label for="partner_logo" class="col-md-3 col-form-label">Logo</label>
                            <div class="col-md-9">
                                <input type="file" class="form-control" name="partner_logo" id="partner_logo"
                                    placeholder="Masukkan nama partner" accept="image/png, image/jpeg, image/jpg, image/webp">

                                <div class="mt-2">
                                    <img id="img-preview" src="<?= $imageUrl ?>"
                                        alt="Image Preview" class="img-thumbnail"
                                        style="max-width: 200px; max-height: 200px; display: none;">
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label for="url" class="col-md-3 col-form-label">Link to Partner</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="url" id="url" placeholder="Masukkan link partner">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary" id="btnSubmit">
                            <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Image Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="" id="modalImageFull" class="img-fluid" alt="Preview Image">
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

    const CAN_UPDATE = <?php echo in_array('update', $access) ? 'true' : 'false'; ?>;
    const CAN_DELETE = <?php echo in_array('delete', $access) ? 'true' : 'false'; ?>;

    var showAction = (CAN_UPDATE || CAN_DELETE);

    $(document).ready(function() {
        $('#data-tables').DataTable({
            processing: true,
            responsive: true,
            autoWidth: false,
            ajax: '<?php echo base_url('admin/partner/data'); ?>',
            columns: [{
                    data: null,
                    name: 'ordering',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: 'partner_name',
                    name: 'partner_name',
                },
                {
                    data: 'partner_logo',
                    name: 'partner_logo',
                    render: function(data, type, row) {
                        if (data) {
                            return `<img src="${data}" alt="Logo" class="img-thumbnail img-clickable" style="height: 100px; cursor: pointer;">`;
                        }
                        return '-';
                    }
                },
                {
                    data: 'url',
                    name: 'url',
                    render: function(data, type, row) {
                        if (data) {
                            return `<a href="${data}" target="_blank">${data}</a>`;
                        }
                        return '-';
                    }
                },
                {
                    data: null,
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        let editUrl = '<?php echo base_url('admin/partner'); ?>/' + row.id + '/edit';
                        let deleteUrl = '<?php echo base_url('admin/partner'); ?>/' + row.id;

                        let buttons = '';

                        if (CAN_UPDATE) {
                            buttons += `<button type="button" data-url="${editUrl}" class="btn btn-warning btn-sm" id="btnEdit"><i class="fas fa-edit"></i> Edit</button>`;
                        }

                        if (CAN_DELETE) {
                            buttons += `<button type="button" data-url="${deleteUrl}" class="btn btn-danger btn-sm ms-2" id="btnDelete"><i class="fas fa-trash"></i> Delete</button>`;
                        }

                        return buttons;
                    }
                },
            ]
        });

        $(document).on('click', '.img-clickable', function() {
            var src = $(this).attr('src');
            $('#modalImageFull').attr('src', src);
            $('#imageModal').modal('show');
        });

        $('#partner_logo').change(function(e) {
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

        $(document).on('click', '#btnEdit', function(e) {
            e.preventDefault();

            let url = $(this).data('url');

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'JSON',
                success: function(res) {
                    if (res.success) {
                        $('#modalTitleId').html('Form Edit Partner');
                        $('#primary_id').val(res.data.id);
                        $('#partner_name').val(res.data.partner_name);

                        if (res.data.partner_logo) {
                            $('#img-preview').attr('src', res.data.partner_logo).show();
                        } else {
                            $('#img-preview').hide().attr('src', '');
                        }

                        $('#url').val(res.data.url);
                        $('#modalForm').modal('show');
                    }
                }
            });
        });

        $('#modalForm').on('hidden.bs.modal', function(event) {
            $('#formData')[0].reset();

            $('#modalTitleId').html('Form Partner');
            $('#primary_id').val('');
            $('#partner_name').val('');
            $('#img-preview').hide().attr('src', '');
            $('#url').val('');
        });

        $('#formData').submit(function(e) {
            e.preventDefault();

            var btn = $('#btnSubmit');
            var spinner = btn.find('.spinner-border');

            btn.prop('disabled', true);
            spinner.removeClass('d-none');

            let id = $('#primary_id').val();
            let formData = new FormData(this);
            let baseUrl = '<?php echo base_url('admin/partner'); ?>';
            let url = id ? baseUrl + '/' + id : baseUrl;
            let method = id ? 'PUT' : 'POST';

            if (id) {
                formData.append('_method', 'PUT');
            }

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                dataType: 'JSON',
                processData: false,
                contentType: false,
                success: function(res) {
                    if (res.success) {
                        $('#modalForm').modal('hide');
                        $('#data-tables').DataTable().ajax.reload();

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
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Terjadi kesalahan. Silakan coba lagi!'
                        })
                    }
                },
                complete: function() {
                    btn.prop('disabled', false);
                    spinner.addClass('d-none');
                }
            });
        });

        $(document).on('click', '#btnDelete', function(e) {
            e.preventDefault();

            let url = $(this).data('url');

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: "No, cancel!",
                didOpen: () => {
                    const confirmButton = Swal.getConfirmButton();
                    if (confirmButton) {
                        confirmButton.style.setProperty('background-color', '#d33', 'important');
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        dataType: 'JSON',
                        success: function(res) {
                            if (res.success) {
                                $('#data-tables').DataTable().ajax.reload();

                                audio.play();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: res.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                })
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
                                    errorMessage = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Terjadi kesalahan. Silakan coba lagi.';
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: errorMessage
                            })
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