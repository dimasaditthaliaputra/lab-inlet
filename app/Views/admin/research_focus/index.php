<div class="page-heading">
    <h3 class="page-title"><?php echo e($title ?? 'Research Focus'); ?></h3>
    <p>Manage research focus for landing page.</p>
</div>

<div class="page-content">
    <section class="row">
        <div class="col-12">
            <div class="card border">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title">List Research Focus</h4>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalForm">
                            <i class="bi bi-plus me-1" role="img" aria-label="Add new research focus"></i>
                            Add New Research Focus
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="data-tables">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Icon / Image</th>
                                    <th class="text-center">Sort</th>
                                    <th width="15%">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
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
                        Form Research Focus
                    </h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="formData" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="primary_id" id="primary_id">

                        <div class="row mb-3 align-items-center">
                            <label for="title" class="col-md-3 col-form-label required">Title</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="title" id="title" placeholder="Masukkan title">
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label for="description" class="col-md-3 col-form-label">Description</label>
                            <div class="col-md-9">
                                <textarea class="form-control" name="description" id="description" rows="3" placeholder="Masukkan deskripsi (optional)"></textarea>
                            </div>
                        </div>

                        <hr class="my-2">

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

                        <div class="row mb-3 align-items-center">
                            <label for="sort_order" class="col-md-3 col-form-label">Sort Order</label>
                            <div class="col-md-9">
                                <input type="number" class="form-control" name="sort_order" id="sort_order" placeholder="Urutan tampilan (default 0)">
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

    <!-- Modal Preview Image -->
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

    function updateIconImageState() {
        const iconInput  = $('#icon_name');
        const imageInput = $('#image_cover');

        const hasIcon = iconInput.val().trim() !== '';
        const hasFile = imageInput[0].files && imageInput[0].files.length > 0;

        if (hasIcon) {
            imageInput.prop('disabled', true);
        } else {
            imageInput.prop('disabled', false);
        }

        if (hasFile) {
            iconInput.prop('disabled', true);
        } else {
            // hanya enable kalau memang tidak ada file
            if (!hasIcon) {
                iconInput.prop('disabled', false);
            }
        }
    }

    $(document).ready(function() {
        $('#data-tables').DataTable({
            processing: true,
            responsive: true,
            autoWidth: false,
            ajax: '<?php echo base_url('admin/research-focus/data'); ?>',
            columns: [
                {
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
                    data: 'title',
                    name: 'title',
                },
                {
                    data: 'description',
                    name: 'description',
                    render: function(data) {
                        if (!data) return '-';
                        return data.length > 80 ? data.substr(0, 80) + '...' : data;
                    }
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
                    data: 'sort_order',
                    name: 'sort_order',
                    className: 'text-center',
                    render: function(data) {
                        return data ?? 0;
                    }
                },
                {
                    data: null,
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        let editUrl   = '<?php echo base_url('admin/research-focus'); ?>/' + row.id + '/edit';
                        let deleteUrl = '<?php echo base_url('admin/research-focus'); ?>/' + row.id;

                        return `
                            <button type="button" data-url="${editUrl}" class="btn btn-warning btn-sm" id="btnEdit"><i class="fas fa-edit"></i></button>
                            <button type="button" data-url="${deleteUrl}" class="btn btn-danger btn-sm" id="btnDelete"><i class="fas fa-trash"></i></button>
                        `;
                    }
                },
            ]
        });

        // Modal image preview
        $(document).on('click', '.img-clickable', function() {
            var src = $(this).attr('src');
            $('#modalImageFull').attr('src', src);
            $('#imageModal').modal('show');
        });

        // Icon / image disable logic
        $('#icon_name').on('input', function() {
            if ($(this).val().trim() !== '') {
                // hapus file kalau ada
                $('#image_cover').val('');
                $('#img-preview').hide().attr('src', '');
            }
            updateIconImageState();
        });

        $('#image_cover').change(function(e) {
            var file = e.target.files[0];

            if (file) {
                // kalau pilih gambar, kosongkan icon
                $('#icon_name').val('');
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#img-preview').attr('src', e.target.result).show();
                }
                reader.readAsDataURL(file);
            } else {
                $('#img-preview').hide().attr('src', '');
            }

            updateIconImageState();
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
                        $('#modalTitleId').html('Form Edit Research Focus');
                        $('#primary_id').val(res.data.id);
                        $('#title').val(res.data.title);
                        $('#description').val(res.data.description ?? '');
                        $('#icon_name').val(res.data.icon_name ?? '');
                        $('#sort_order').val(res.data.sort_order ?? '');

                        $('#image_cover').val('');
                        if (res.data.image_cover) {
                            $('#img-preview').attr('src', res.data.image_cover).show();
                        } else {
                            $('#img-preview').hide().attr('src', '');
                        }

                        // set state enable/disable
                        updateIconImageState();

                        $('#modalForm').modal('show');
                    }
                }
            });
        });

        // Reset modal
        $('#modalForm').on('hidden.bs.modal', function(event) {
            $('#formData')[0].reset();

            $('#modalTitleId').html('Form Research Focus');
            $('#primary_id').val('');
            $('#img-preview').hide().attr('src', '');
            $('.text-danger').remove();

            $('#icon_name').prop('disabled', false);
            $('#image_cover').prop('disabled', false);
        });

        // Submit form
        $('#formData').submit(function(e) {
            e.preventDefault();

            var btn     = $('#btnSubmit');
            var spinner = btn.find('.spinner-border');

            btn.prop('disabled', true);
            spinner.removeClass('d-none');

            let id       = $('#primary_id').val();
            let formData = new FormData(this);
            let baseUrl  = '<?php echo base_url('admin/research-focus'); ?>';
            let url      = id ? baseUrl + '/' + id : baseUrl;

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
                            let inputEl = $(#${key});
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
                                })
                            }
                        },
                        error: function(xhr, status, error) {
                            let errorMessage;

                            switch (xhr.status) {
                                case 404:
                                    errorMessage = 'Error: Data tidak ditemukan (404).';
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