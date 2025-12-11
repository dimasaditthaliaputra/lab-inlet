<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3><?php echo e($title ?? 'Facilities'); ?></h3>
                <p class="text-subtitle text-muted">List of facilities used in the system.</p>
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
                        <h4 class="card-title">List Facilities</h4>
                        <a href="<?= base_url('admin/facilities/create') ?>" class="btn btn-primary">
                            <i class="bi bi-plus me-1"></i>
                            Add New Facility
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="table-facilities">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="18%">Name</th>
                                    <th width="25%">Description</th>
                                    <th width="10%">Condition</th>
                                    <th width="15%">Image</th>
                                    <th width="10%">Quantity</th>
                                    <th width="17%">Action</th>
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
                    <h5 class="modal-title">Image Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="" id="modalImageFull" class="img-fluid" alt="Image Preview">
                </div>
            </div>
        </div>
    </div>
</div>

</div>

<?php ob_start(); ?>
<script>
    var audio = new Audio("<?php echo base_url('assets/audio/success.wav'); ?>");

    $(document).ready(function() {

        $('#table-facilities').DataTable({
            processing: true,
            serverSide: false,
            autoWidth: false,
            pageLength: 10,
            ajax: '<?php echo base_url('admin/facilities/data'); ?>',
            columns: [{
                    data: null,
                    sortable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'description',
                    name: 'description'
                },
                {
                    data: 'condition',
                    name: 'condition'
                },
                {
                    data: 'image_name',
                    name: 'image_name',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    render: (data) =>
                        data ? `<img src="${data}" class="img-thumbnail img-clickable" style="max-height:50px; cursor:pointer;" />` : '-'
                },
                {
                    data: 'qty',
                    name: 'qty'
                },
                {
                    data: null,
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center text-nowrap',
                    render: function(data, type, row) {
                        let editUrl = '<?php echo base_url('admin/facilities'); ?>/' + row.id + '/edit';
                        let deleteUrl = '<?php echo base_url('admin/facilities'); ?>/' + row.id;

                        return `
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
            $('#modalImageFull').attr('src', $(this).attr('src'));
            $('#imageModal').modal('show');
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
                                $('#table-facilities').DataTable().ajax.reload();

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
<?php $pageScripts = ob_get_clean(); 
?>