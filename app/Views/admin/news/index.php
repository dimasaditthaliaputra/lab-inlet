<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3><?php echo e($title ?? 'News'); ?></h3>
                <p class="text-subtitle text-muted">List News For Landing Page.</p>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="row">
        <div class="col-12">
            <div class="card border">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title">List News</h4>
                        <a href="<?= base_url('admin/news/create') ?>" class="btn btn-primary">
                            <i class="bi bi-plus me-1" role="img" aria-label="Add new news"></i>
                            Add New News
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="table-news">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="10%">Title</th>
                                    <th width="15%">Thumbnail</th>
                                    <th width="30%">Content</th>
                                    <th width="13%" class="text-center">Publish</th>
                                    <th width="13%" class="text-center">Creator</th>
                                    <th width="14%">Action</th>
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

    $(document).ready(function() {
        $('#table-news').DataTable({
            processing: true,
            serverSide: false,
            responsive: false,
            autoWidth: false,
            pageLength: 10,
            lengthChange: false,
            ajax: '<?php echo base_url('admin/news/data'); ?>',
            columns: [{
                    data: null,
                    sortable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: 'title',
                    name: 'title',
                    render: function(data, type, row) {
                        if (data && data.length > 30) {
                            return `<span title="${data}">${data.substr(0, 30)}...</span>`;
                        }
                        return data;
                    }
                },
                {
                    data: 'image',
                    name: 'image',
                    searchable: false,
                    orderable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        return data ?
                            `<img src="${data}" class="img-thumbnail img-clickable" style="max-height: 50px; cursor: pointer;" alt="Thumbnail" />` :
                            '-';
                    }
                },
                {
                    data: 'content',
                    name: 'content',
                    searchable: false,
                    orderable: false,
                    default: '-'
                },
                {
                    data: 'is_publish',
                    name: 'is_publish',
                    className: 'text-center',
                    render: function(data) {
                        if (data) return '<span class="badge bg-success"><i class="fas fa-check"></i> Published</span>';
                        return '<span class="badge bg-secondary"><i class="fas fa-eye"></i> Draft</span>';
                    }
                },
                {
                    data: 'created_by',
                    name: 'created_by',
                    searchable: false,
                    orderable: false,
                    className: 'text-center',
                },
                {
                    data: null,
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center text-nowrap',
                    render: function(data, type, row) {
                        let disablePublish = row.is_publish == 1 ? 'disabled' : '';
                        let publishUrl = '<?php echo base_url('admin/news/publish'); ?>/' + row.id;
                        let editUrl = '<?php echo base_url('admin/news'); ?>/' + row.id + '/edit';
                        let deleteUrl = '<?php echo base_url('admin/news'); ?>/' + row.id;

                        return `
                <button type="button" data-url="${publishUrl}" class="btn btn-info btn-sm ${disablePublish}" id="btnPublish" title="Publish">
                    <i class="fas fa-eye"></i> Publish
                </button>
                <a href="${editUrl}" class="btn btn-warning btn-sm" id="btnEdit" title="Edit">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <button type="button" data-url="${deleteUrl}" class="btn btn-danger btn-sm" id="btnDelete" title="Delete">
                    <i class="fas fa-trash"></i> Delete
                </button>`;
                    }
                }
            ]
        });

        $(document).on('click', '.img-clickable', function() {
            var src = $(this).attr('src');
            $('#modalImageFull').attr('src', src);
            $('#imageModal').modal('show');
        });

        $(document).on('click', '#btnPublish', function(e) {
            e.preventDefault();

            let url = $(this).data('url');

            Swal.fire({
                title: "Are you sure to publish this news?",
                text: "This news will be published.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: '#0000FF',
                confirmButtonText: 'Yes!',
                cancelButtonText: "No, cancel!",
                didOpen: () => {
                    const confirmButton = Swal.getConfirmButton();
                    if (confirmButton) {
                        confirmButton.style.setProperty('background-color', 'var(--var-primary)', 'important');
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'PUT',
                        dataType: 'JSON',
                        success: function(res) {
                            if (res.success) {
                                $('#table-news').DataTable().ajax.reload();

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
                                    errorMessage = 'Error: Halaman tidak ditemukan (404).';
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
                                $('#table-news').DataTable().ajax.reload();

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