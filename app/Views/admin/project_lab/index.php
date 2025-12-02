<div class="page-heading">
    <h3 class="page-title"><?php echo e($title ?? 'Project Lab'); ?></h3>
    <p>Manage list of projects.</p>
</div>

<div class="page-content">
    <section class="row">
        <div class="col-12">
            <div class="card border">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title">List Project</h4>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalForm" data-url="">
                            <i class="bi bi-plus me-1" role="img" aria-label="Add new project"></i>
                            Add New Project
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
                                    <th>Category</th>
                                    <th>Image</th>
                                    <th>Status</th>
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
            class="modal-dialog modal-dialog-centered modal-lg"
            role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitleId">
                        Form Project
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
                                <input type="text" class="form-control" name="name" id="name" placeholder="Masukkan nama project">
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label for="id_kategori" class="col-md-3 col-form-label required">Category</label>
                            <div class="col-md-9">
                                <select class="form-select" name="id_kategori" id="id_kategori">
                                    <option value="">Pilih Kategori</option>
                                    <?php foreach ($categories as $cat) : ?>
                                        <option value="<?= $cat->id ?>"><?= $cat->name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label for="description" class="col-md-3 col-form-label required">Description</label>
                            <div class="col-md-9">
                                <textarea class="form-control" name="description" id="description" rows="3" placeholder="Masukkan deskripsi"></textarea>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label for="video_url" class="col-md-3 col-form-label">Video URL</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="video_url" id="video_url" placeholder="Masukkan link video">
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label for="status" class="col-md-3 col-form-label required">Status</label>
                            <div class="col-md-9">
                                <select class="form-select" name="status" id="status">
                                    <option value="archived">Archive</option>
                                    <option value="in_progress">On Progress</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label for="image_url" class="col-md-3 col-form-label">Image</label>
                            <div class="col-md-9">
                                <input type="file" class="form-control" name="image_url[]" id="image_url" placeholder="Pilih gambar" accept="image/*" multiple>

                                <div class="mt-2 d-flex flex-wrap gap-2" id="preview-container">
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
                    <h5 class="modal-title" id="imageModalLabel">Project Images</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div id="carouselProjectImages" class="carousel slide" data-bs-ride="false">
                        <div class="carousel-inner" id="carousel-inner-content"></div>

                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselProjectImages" data-bs-slide="prev" id="btn-prev-img">
                            <span class="carousel-control-prev-icon" aria-hidden="true" style="background-color: rgba(0,0,0,0.5); border-radius: 50%;"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselProjectImages" data-bs-slide="next" id="btn-next-img">
                            <span class="carousel-control-next-icon" aria-hidden="true" style="background-color: rgba(0,0,0,0.5); border-radius: 50%;"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
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

    $(document).ready(function() {
        $('#id_kategori').select2({
            theme: 'bootstrap-5',
            dropdownParent: $('#modalForm'),
            placeholder: 'Pilih Kategori',
            width: '100%',
            allowClear: true,
            minimumResultsForSearch: Infinity
        });

        $('#status').select2({
            theme: 'bootstrap-5',
            dropdownParent: $('#modalForm'),
            placeholder: 'Pilih Status Pengerjaan',
            width: '100%',
            allowClear: true,
            minimumResultsForSearch: Infinity
        });

        $('#data-tables').DataTable({
            processing: true,
            responsive: true,
            autoWidth: false,
            ajax: '<?php echo base_url('admin/project-lab/data'); ?>',
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
                    data: 'kategori_name',
                    name: 'kategori_name',
                },
                {
                    data: 'thumbnail',
                    name: 'image_url',
                    orderable: false,
                    render: function(data, type, row) {
                        if (data) {
                            let imagesJson = encodeURIComponent(JSON.stringify(row.images_list));

                            return `
                                <div class="position-relative d-inline-block">
                                    <img src="${data}" 
                                         alt="Image" 
                                         class="img-thumbnail img-clickable" 
                                         data-images="${imagesJson}" 
                                         style="height: 100px; cursor: pointer;">
                                    ${row.images_list.length > 1 ? 
                                      `<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary">
                                        +${row.images_list.length - 1}
                                      </span>` : ''}
                                </div>`;
                        }
                        return '-';
                    }
                },
                {
                    data: 'status',
                    name: 'status',
                    render: function(data, type, row) {
                        if (data == 'Completed') {
                            return `<span class="badge bg-success">Completed</span>`;
                        } else {
                            return `<span class="badge bg-danger">On Progress</span>`;
                        }
                    }
                },
                {
                    data: null,
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        let editUrl = '<?php echo base_url('admin/project-lab'); ?>/' + row.id + '/edit';
                        let deleteUrl = '<?php echo base_url('admin/project-lab'); ?>/' + row.id;

                        return `
                            <button type="button" data-url="${editUrl}" class="btn btn-warning btn-sm" id="btnEdit"><i class="fas fa-edit"></i></button>
                            <button type="button" data-url="${deleteUrl}" class="btn btn-danger btn-sm" id="btnDelete"><i class="fas fa-trash"></i></button>
                        `;
                    }
                },
            ]
        });

        $(document).on('click', '.img-clickable', function() {
            var rawData = $(this).attr('data-images');
            var images = JSON.parse(decodeURIComponent(rawData));

            var carouselInner = $('#carousel-inner-content');
            carouselInner.empty();

            if (images.length > 0) {
                images.forEach(function(imgUrl, index) {
                    var activeClass = (index === 0) ? 'active' : '';
                    var itemHtml = `
                        <div class="carousel-item ${activeClass}">
                            <div class="d-flex justify-content-center" style="min-height:300px; align-items:center;">
                                <img src="${imgUrl}" class="d-block" style="max-height: 500px; max-width: 100%; object-fit: contain;" alt="Project Image ${index+1}">
                            </div>
                        </div>
                    `;
                    carouselInner.append(itemHtml);
                });

                if (images.length > 1) {
                    $('#btn-prev-img, #btn-next-img').show();
                } else {
                    $('#btn-prev-img, #btn-next-img').hide();
                }

                $('#imageModal').modal('show');
            }
        });

        $('#image_url').change(function(e) {
            var files = e.target.files;
            var container = $('#preview-container');

            container.empty();

            if (files && files.length > 0) {
                $.each(files, function(i, file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var img = `
                        <img src="${e.target.result}" 
                             class="img-thumbnail me-2 mb-2" 
                             alt="Preview" 
                             style="width: 100px; height: 100px; object-fit: cover;">
                    `;
                        container.append(img);
                    }
                    reader.readAsDataURL(file);
                });
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
                        $('#modalTitleId').html('Form Edit Project');
                        $('#primary_id').val(res.data.id);
                        $('#name').val(res.data.name);
                        $('#id_kategori').val(res.data.id_kategori);
                        $('#description').val(res.data.description);
                        $('#video_url').val(res.data.video_url);
                        $('#status').val(res.data.status);

                        var container = $('#preview-container');
                        container.empty();

                        if (res.data.images_list && res.data.images_list.length > 0) {
                            res.data.images_list.forEach(function(imgUrl) {
                                var img = `
                                <img src="${imgUrl}" 
                                     class="img-thumbnail me-2 mb-2" 
                                     alt="Existing Image" 
                                     style="width: 100px; height: 100px; object-fit: cover;">
                            `;
                                container.append(img);
                            });
                        }

                        $('#modalForm').modal('show');
                    }
                }
            });
        });

        $('#modalForm').on('hidden.bs.modal', function(event) {
            $('#formData')[0].reset();
            $('#modalTitleId').html('Form Project');
            $('#primary_id').val('');
            $('#name').val('');
            $('#id_kategori').val('');
            $('#description').val('');
            $('#video_url').val('');
            $('#status').val('Active');
            $('#preview-container').empty();
        });

        $('#formData').submit(function(e) {
            e.preventDefault();

            var btn = $('#btnSubmit');
            var spinner = btn.find('.spinner-border');

            btn.prop('disabled', true);
            spinner.removeClass('d-none');

            let id = $('#primary_id').val();
            let formData = new FormData(this);
            let baseUrl = '<?php echo base_url('admin/project-lab'); ?>';
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