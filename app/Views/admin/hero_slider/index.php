<div class="page-heading">
    <h3 class="page-title"><?php echo e($title ?? 'Hero Slider'); ?></h3>
    <p>Manage hero slider for landing page.</p>
</div>

<div class="page-content">
    <section class="row">
        <div class="col-12">
            <div class="card border">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title">List Hero Slider</h4>
                        <?php if (in_array('create', $access)): ?>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalForm">
                                <i class="bi bi-plus me-1" role="img" aria-label="Add new slider"></i>
                                Add New Slider
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
                                    <th>Title</th>
                                    <th>Subtitle</th>
                                    <th>Image</th>
                                    <th>Sort</th>
                                    <th>Status</th>
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
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitleId">
                        Form Hero Slider
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formData" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="primary_id" id="primary_id">

                        <div class="row mb-3 align-items-center">
                            <label for="title" class="col-md-3 col-form-label required">Title</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="title" id="title" placeholder="Judul slider">
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label for="subtitle" class="col-md-3 col-form-label">Subtitle</label>
                            <div class="col-md-9">
                                <textarea class="form-control" name="subtitle" id="subtitle" rows="2" placeholder="Subjudul (optional)"></textarea>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label for="button_text" class="col-md-3 col-form-label">Button Text</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="button_text" id="button_text" placeholder="Contoh: Learn More">
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label for="button_url" class="col-md-3 col-form-label">Button URL</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="button_url" id="button_url" placeholder="https://...">
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label for="sort_order" class="col-md-3 col-form-label">Sort Order</label>
                            <div class="col-md-9">
                                <input type="number" class="form-control" name="sort_order" id="sort_order" placeholder="Urutan tampilan (default 0)">
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label class="col-md-3 col-form-label">Status</label>
                            <div class="col-md-9 d-flex align-items-center">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                                    <label class="form-check-label" for="is_active">Active</label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label for="image" class="col-md-3 col-form-label required">Image</label>
                            <div class="col-md-9">
                                <input type="file" class="form-control" name="image" id="image" accept="image/*">
                                <small class="text-muted d-block mt-1">Maksimal 2MB. Format: jpg, jpeg, png, webp.</small>

                                <div class="mt-2">
                                    <img id="img-preview" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px; max-height: 200px; display:none;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
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

    <!-- Modal Preview Gambar -->
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

    var CAN_UPDATE = <?php echo in_array('update', $access) ? 'true' : 'false'; ?>;
    var CAN_DELETE = <?php echo in_array('delete', $access) ? 'true' : 'false'; ?>;

    var showAction = (CAN_UPDATE || CAN_DELETE);

    $(document).ready(function() {
        // DataTables
        $('#data-tables').DataTable({
            processing: true,
            responsive: true,
            autoWidth: false,
            ajax: '<?php echo base_url('admin/hero-slider/data'); ?>',
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
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'subtitle',
                    name: 'subtitle',
                    orderable: false,
                    searchable: false,
                    render: function(data) {
                        if (!data) return '-';
                        return data.length > 60 ? data.substr(0, 60) + '...' : data;
                    }
                },
                {
                    data: 'image',
                    name: 'image',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        if (data) {
                            return `
                                <img src="${data}"
                                     alt="Image"
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
                    data: 'is_active',
                    name: 'is_active',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    render: function(data) {
                        if (data) {
                            return '<span class="badge bg-success">Active</span>';
                        }
                        return '<span class="badge bg-secondary">Inactive</span>';
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
                        let editUrl = '<?php echo base_url('admin/hero-slider'); ?>/' + row.id + '/edit';
                        let deleteUrl = '<?php echo base_url('admin/hero-slider'); ?>/' + row.id;

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

        // Preview gambar di modal list
        $(document).on('click', '.img-clickable', function() {
            var src = $(this).attr('src');
            $('#modalImageFull').attr('src', src);
            $('#imageModal').modal('show');
        });

        // Preview gambar di form
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
                        $('#modalTitleId').html('Form Edit Hero Slider');
                        $('#primary_id').val(res.data.id);
                        $('#title').val(res.data.title);
                        $('#subtitle').val(res.data.subtitle);
                        $('#button_text').val(res.data.button_text);
                        $('#button_url').val(res.data.button_url);
                        $('#sort_order').val(res.data.sort_order);
                        $('#is_active').prop('checked', res.data.is_active ? true : false);

                        if (res.data.image_url) {
                            $('#img-preview').attr('src', res.data.image_url).show();
                        } else {
                            $('#img-preview').hide().attr('src', '');
                        }

                        $('#modalForm').modal('show');
                    }
                }
            });
        });

        // Reset modal ketika ditutup
        $('#modalForm').on('hidden.bs.modal', function(event) {
            $('#formData')[0].reset();
            $('#modalTitleId').html('Form Hero Slider');
            $('.text-danger').remove();
            $('#primary_id').val('');
            $('#img-preview').hide().attr('src', '');
            $('#is_active').prop('checked', true);
        });

        // Submit form (create & update)
        $('#formData').submit(function(e) {
            e.preventDefault();

            var btn = $('#btnSubmit');
            var spinner = btn.find('.spinner-border');

            btn.prop('disabled', true);
            spinner.removeClass('d-none');

            let id = $('#primary_id').val();
            let formData = new FormData(this);
            let baseUrl = '<?php echo base_url('admin/hero-slider'); ?>';
            let url = id ? baseUrl + '/' + id : baseUrl;

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
                                    errorMessage = (xhr.responseJSON && xhr.responseJSON.message) ?
                                        xhr.responseJSON.message :
                                        'Terjadi kesalahan. Silakan coba lagi.';
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