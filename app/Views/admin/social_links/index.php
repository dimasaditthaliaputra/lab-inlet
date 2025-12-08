<div class="page-heading">
    <h3 class="page-title"><?php echo e($title ?? 'Social Media Project'); ?></h3>
    <p>Manage project categories.</p>
</div>

<div class="page-content">
    <section class="row">
        <div class="col-12">
            <div class="card border">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title">List Social Links</h4>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalForm" data-url="">
                            <i class="bi bi-plus me-1" role="img" aria-label="Add new category"></i>
                            Add New Social Media
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="data-tables">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Name</th>
                                    <th>Icon /Image</th>
                                    <th>Action</th>
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
            class="modal-dialog modal-dialog-scrollable modal-dialog-centered"
            role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitleId">
                        Form Social Media
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
                            <label for="name" class="col-md-3 col-form-label required">Name</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="name" id="name" placeholder="Masukkan nama social media">
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label for="icon_name" class="col-md-3 col-form-label">Icon Name</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="icon_name" id="icon_name" placeholder="Contoh: bi bi-cpu">
                                <small class="text-muted d-block mt-1">
                                    Gunakan class icon (Bootstrap Icons / Font Awesome).
                                </small>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label for="image_cover" class="col-md-3 col-form-label">Image Cover</label>
                            <div class="col-md-9">
                                <input type="file" class="form-control" name="image_cover" id="image_cover" accept="image/*">
                                <small class="text-muted d-block mt-1">
                                    Maksimal 2MB, format jpg / jpeg / png / webp.
                                </small>

                                <div class="mt-2">
                                    <img id="img-preview" src=""
                                        alt="Image Preview" class="img-thumbnail"
                                        style="max-width: 200px; max-height: 200px; display: none;">
                                </div>
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

    function updateInputState() {
        const iconInput = $('#icon_name');
        const imageInput = $('#image_cover');

        const hasIcon = iconInput.val().trim() !== '';
        const hasFile = imageInput[0].files && imageInput[0].files.length > 0;

        iconInput.prop('disabled', hasFile);
        imageInput.prop('disabled', hasIcon);
    }

    $('#icon_name').on('input', function() {
        if ($(this).val().trim() !== '') {
            $('#image_cover').val('');
            $('#img-preview').hide().attr('src', '');
        }
        updateInputState();
    });

    $('#image_cover').on('change', function(e) {
        var file = e.target.files[0];

        if (file) {
            $('#favicon').val('');

            var reader = new FileReader();
            reader.onload = function(e) {
                $('#img-preview').attr('src', e.target.result).show();
            }
            reader.readAsDataURL(file);
        } else {
            $('#img-preview').hide().attr('src', '');
        }

        updateInputState();
    });

    $('#modalForm').on('hidden.bs.modal', function() {
        $('#formData')[0].reset();
        $('#img-preview').hide().attr('src', '');
        updateInputState();
    });

    updateInputState();

    $(document).ready(function() {
        $('#data-tables').DataTable({
            processing: true,
            responsive: true,
            autoWidth: false,
            ajax: '<?php echo base_url('admin/social-links/data'); ?>',
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
                    data: 'name',
                    name: 'name',
                },
                {
                    data: null,
                    name: 'icon_or_image',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        if (row.icon_name) {
                            return `
                                <div>
                                    <i class="${row.icon_name}" style="font-size: 28px;"></i>
                                    <div class="small text-muted mt-1">${row.icon_name}</div>
                                </div>
                            `;
                        }
                        if (row.image_cover) {
                            return `
                                <img src="${row.image_cover}"
                                     alt="Cover"
                                     class="img-thumbnail img-clickable"
                                     style="height: 80px; cursor: pointer;">
                            `;
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
                        let editUrl = '<?php echo base_url('admin/social-links'); ?>/' + row.id + '/edit';
                        let deleteUrl = '<?php echo base_url('admin/social-links'); ?>/' + row.id;

                        return `
                            <button type="button" data-url="${editUrl}" class="btn btn-warning btn-sm" id="btnEdit"><i class="fas fa-edit"></i></button>
                            <button type="button" data-url="${deleteUrl}" class="btn btn-danger btn-sm" id="btnDelete"><i class="fas fa-trash"></i></button>
                        `;
                    }
                },
            ]
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
                        $('#modalTitleId').html('Form Edit Social Media');
                        $('#primary_id').val(res.data.id);
                        $('#name').val(res.data.name);
                        $('#icon_name').val(res.data.icon_name);
                        $('#image_cover').val(res.data.image_cover);
                        $('#modalForm').modal('show');
                    }
                }
            });
        });

        $('#modalForm').on('hidden.bs.modal', function(event) {
            $('#formData')[0].reset();
            $('#modalTitleId').html('Form Social Media');
            $('#primary_id').val('');
            $('#name').val('');
            $('#icon_name').val('');
            $('#image_cover').val('');
        });

        $('#formData').submit(function(e) {
            e.preventDefault();

            var btn = $('#btnSubmit');
            var spinner = btn.find('.spinner-border');

            btn.prop('disabled', true);
            spinner.removeClass('d-none');

            let id = $('#primary_id').val();
            let formData = new FormData(this);
            let baseUrl = '<?php echo base_url('admin/social-links'); ?>';
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
                            let errorMessage = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Terjadi kesalahan. Silakan coba lagi.';
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