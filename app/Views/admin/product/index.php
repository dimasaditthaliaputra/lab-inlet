<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3><?php echo e($title ?? 'Product'); ?></h3>
                <p>List of products.</p>
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
                        <h4 class="card-title">List Product</h4>
                        <?php if (in_array('create', $access)): ?>
                            <a href="<?= base_url('admin/product/create') ?>" class="btn btn-primary">
                                <i class="bi bi-plus me-1" role="img" aria-label="Add new product"></i>
                                Add New Product
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="table-product">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="15%">Product Name</th>
                                    <th width="10%">Thumbnail</th>
                                    <th width="20%">Description</th>
                                    <th width="10%" class="text-center">Release Date</th>
                                    <th width="10%">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal preview image -->
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

    function featuresToText(features) {
        if (!features || !features.length) return '-';
        if (features.length > 3) {
            return features.slice(0, 3).join(', ') + '...';
        }
        return features.join(', ');
    }

    function specsToText(specs) {
        if (!specs || Object.keys(specs).length === 0) return '-';
        let arr = [];
        for (let key in specs) {
            if (!specs.hasOwnProperty(key)) continue;
            arr.push(key + ': ' + specs[key]);
        }
        if (arr.length > 3) {
            return arr.slice(0, 3).join(', ') + '...';
        }
        return arr.join(', ');
    }

    $(document).ready(function() {
        $('#table-product').DataTable({
            processing: true,
            serverSide: false,
            responsive: false,
            autoWidth: false,
            pageLength: 10,
            lengthChange: false,
            ajax: '<?php echo base_url('admin/product/data'); ?>',
            columns: [{
                    data: null,
                    sortable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: 'product_name',
                    name: 'product_name',
                    render: function(data) {
                        if (data && data.length > 40) {
                            return `<span title="${data}">${data.substr(0, 40)}...</span>`;
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
                    render: function(data) {
                        return data ?
                            `<img src="${data}" class="img-thumbnail img-clickable" style="max-height: 50px; cursor: pointer;" alt="Thumbnail" />` :
                            '-';
                    }
                },
                {
                    data: 'description',
                    name: 'description',
                    searchable: false,
                    orderable: false,
                    render: function(data) {
                        if (!data) return '-';
                        if (data.length > 80) {
                            return `<span title="${data}">${data.substr(0, 80)}...</span>`;
                        }
                        return data;
                    }
                },
                {
                    data: 'release_date',
                    name: 'release_date',
                    className: 'text-center',
                    render: function(data) {
                        return data || '-';
                    }
                },
                {
                    data: null,
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center text-nowrap',
                    visible: showAction,
                    render: function(data, type, row) {
                        let viewUrl = '<?php echo base_url('admin/product'); ?>/' + row.id + '/view';
                        let editUrl = '<?php echo base_url('admin/product'); ?>/' + row.id + '/edit';
                        let deleteUrl = '<?php echo base_url('admin/product'); ?>/' + row.id;

                        let buttons = '';

                        buttons += `<a href="${viewUrl}" class="btn btn-success btn-sm" title="View">
                                        <i class="fas fa-eye"></i> View
                                    </a>`;

                        if (CAN_UPDATE) {
                            buttons += `<a href="${editUrl}" class="btn btn-warning btn-sm ms-2" title="Edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>`;
                        }

                        if (CAN_DELETE) {
                            buttons += `<button type="button" data-url="${deleteUrl}" class="btn btn-danger btn-sm ms-2" id="btnDelete" title="Delete">
                                <i class="fas fa-trash"></i> Delete
                            </button>`;
                        }

                        return buttons;
                    }
                }
            ]
        });

        // preview image
        $(document).on('click', '.img-clickable', function() {
            var src = $(this).attr('src');
            $('#modalImageFull').attr('src', src);
            $('#imageModal').modal('show');
        });

        // delete
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
                                $('#table-product').DataTable().ajax.reload();

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