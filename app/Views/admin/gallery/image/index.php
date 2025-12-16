<div class="page-heading">
    <h3 class="page-title"><?php echo e($title ?? 'Gallery Image'); ?></h3>
</div>

<div class="page-content">
    <section class="row">
        <div class="col-12">
            <div class="card border">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title">Gallery Image List</h4>
                        <?php if (in_array('create', $access)) : ?>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalForm">
                                <i class="bi bi-plus me-1" role="img" aria-label="Add new image"></i>
                                Add New Image
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
                                    <th width="15%">Image</th>
                                    <th>Title</th>
                                    <th>Description</th>
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

    <!-- Modal Form -->
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
                        Form Gallery Image
                    </h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="formData" action="<?php echo base_url('admin/gallery/image') ?>" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="primary_id" id="primary_id">

                        <div class="row mb-3 align-items-center">
                            <label for="title" class="col-md-3 col-form-label">Title</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="title" id="title" placeholder="Input title">
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label for="description" class="col-md-3 col-form-label">Description</label>
                            <div class="col-md-9">
                                <textarea class="form-control" name="description" id="description" rows="3" placeholder="Input description (optional)"></textarea>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label for="image" class="col-md-3 col-form-label">Image</label>
                            <div class="col-md-9">
                                <input type="file" class="form-control" name="image" id="image" accept=".jpg,.jpeg,.png,.webp">
                                <small class="text-muted d-block mt-1">Format: jpg, jpeg, png, webp.</small>
                                <div class="mt-2">
                                    <img id="previewImage" src="" alt="" class="img-thumbnail d-none" style="max-height: 150px;">
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

    <!-- 🔍 Modal Preview Image -->
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
    const baseImageUrl = "<?php echo base_url('uploads/gallery/images'); ?>";

    var CAN_UPDATE = <?php echo in_array('update', $access) ? 'true' : 'false'; ?>;
    var CAN_DELETE = <?php echo in_array('delete', $access) ? 'true' : 'false'; ?>;

    var showAction = (CAN_UPDATE || CAN_DELETE);

    $(document).ready(function() {
        // Datatable
        $('#data-tables').DataTable({
            processing: true,
            responsive: true,
            autoWidth: false,
            ajax: '<?php echo base_url('admin/gallery/image/data'); ?>',
            columns: [
                {
                    data: null,
                    name: 'ordering',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: 'image_name',
                    name: 'image_name',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        if (!data) return '-';
                        let src = baseImageUrl + '/' + data;
                        return `
                            <img src="${src}"
                                 alt="${row.title ?? ''}"
                                 class="img-thumbnail img-clickable"
                                 style="max-height:80px; cursor:pointer;">
                        `;
                    }
                },
                {
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'description',
                    name: 'description',
                    render: function(data) {
                        return data ? data : '-';
                    }
                },
                {
                    data: null,
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    visible: showAction,
                    render: function(data, type, row) {
                        let editUrl = '<?php echo base_url('admin/gallery/image'); ?>/' + row.id + '/edit';
                        let deleteUrl = '<?php echo base_url('admin/gallery/image'); ?>/' + row.id;

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

        // Preview image di form
        $('#image').on('change', function() {
            const [file] = this.files;
            if (file) {
                $('#previewImage').removeClass('d-none');
                $('#previewImage').attr('src', URL.createObjectURL(file));
            } else {
                $('#previewImage').addClass('d-none').attr('src', '');
            }
        });

        // 🔍 Klik thumbnail -> buka modal preview
        $(document).on('click', '.img-clickable', function() {
            var src = $(this).attr('src');
            $('#modalImageFull').attr('src', src);
            $('#imageModal').modal('show');
        });

        // Edit
        $(document).on('click', '#btnEdit', function(e) {
            e.preventDefault();

            let url = $(this).data('url');

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'JSON',
                success: function(res) {
                    if (res.success) {
                        $('#modalTitleId').html('Form Edit Gallery Image');
                        $('#primary_id').val(res.data.id);
                        $('#title').val(res.data.title);
                        $('#description').val(res.data.description ?? '');

                        if (res.data.image_name) {
                            $('#previewImage').removeClass('d-none');
                            $('#previewImage').attr('src', baseImageUrl + '/' + res.data.image_name);
                        } else {
                            $('#previewImage').addClass('d-none').attr('src', '');
                        }

                        $('#modalForm').modal('show');
                    }
                }
            });
        });

        // Reset modal
        $('#modalForm').on('hidden.bs.modal', function(event) {
            $('#formData')[0].reset();

            $('#modalTitleId').html('Form Gallery Image');
            $('.text-danger').remove();
            $('#primary_id').val('');
            $('#previewImage').addClass('d-none').attr('src', '');
        });

        // Submit (create & update)
        $('#formData').submit(function(e) {
            e.preventDefault();

            var btn = $('#btnSubmit');
            var spinner = btn.find('.spinner-border');

            btn.prop('disabled', true);
            spinner.removeClass('d-none');

            let id = $('#primary_id').val();
            let baseUrl = '<?php echo base_url('admin/gallery/image'); ?>';
            let url = id ? baseUrl + '/' + id : baseUrl;

            let form = $('#formData')[0];
            let formData = new FormData(form);

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
                            let inputEl = $(`#${key}`);
                            if (inputEl.length) {
                                inputEl.after(`
                                    <small class="text-danger d-block mt-1">
                                        ${value}
                                    </small>
                                `);
                            }
                        });

                        if (errors.image) {
                            $('#image').after(`
                                <small class="text-danger d-block mt-1">
                                    ${errors.image}
                                </small>
                            `);
                        }
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

        // Delete
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
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            let errorMessage;

                            switch (xhr.status) {
                                case 404:
                                    errorMessage = 'Data tidak ditemukan (404).';
                                    break;
                                case 0:
                                    errorMessage = 'Server terlalu lama merespon (timeout).';
                                    break;
                                default:
                                    errorMessage = (xhr.responseJSON && xhr.responseJSON.message)
                                        ? xhr.responseJSON.message
                                        : 'Terjadi kesalahan. Silakan coba lagi.';
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: errorMessage
                            });
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
