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
                                    <th width="10%">Qty</th>
                                    <th width="17%">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Image Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="" id="modalImageFull" class="img-fluid" alt="Preview Image">
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
            columns: [
                {
                    data: null,
                    sortable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                { data: 'name' },
                {
                    data: 'description',
                    render: function(data) {
                        if (!data) return '-';
                        return data.length > 40 ? data.substr(0, 40) + '...' : data;
                    }
                },
                { data: 'condition' },
                {
                    data: 'image_name',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    render: function(data) {
                        if (!data) return '-';
                        return `
                            <img src="<?= base_url('uploads/facilities/') ?>${data}" 
                                class="img-thumbnail img-clickable"
                                style="max-height: 50px; cursor:pointer;">
                        `;
                    }
                },
                { data: 'qty' },
                {
                    data: null,
                    className: 'text-center text-nowrap',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        let editUrl = '<?php echo base_url('admin/facilities'); ?>/' + row.id + '/edit';
                        let deleteUrl = '<?php echo base_url('admin/facilities'); ?>/' + row.id;

                        return `
                            <a href="${editUrl}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>

                            <button type="button" data-url="${deleteUrl}" 
                                class="btn btn-danger btn-sm" id="btnDelete">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        `;
                    }
                }
            ]
        });

        // 🔍 preview image
        $(document).on('click', '.img-clickable', function() {
            $('#modalImageFull').attr('src', $(this).attr('src'));
            $('#imageModal').modal('show');
        });

        // 🗑 delete (SweetAlert sama seperti News)
        $(document).on('click', '#btnDelete', function() {
            let url = $(this).data('url');

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
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
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: xhr.responseJSON?.message ?? 'Terjadi kesalahan'
                            });
                        }
                    });

                }

            });

        });

    });
</script>
<?php $pageScripts = ob_get_clean(); ?>

