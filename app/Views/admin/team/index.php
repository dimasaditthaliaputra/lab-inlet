<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3><?= $title ?? 'Team'; ?></h3>
                <p class="text-subtitle text-muted">List Team Members.</p>
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
                        <h4 class="card-title">Team List</h4>
                        <a href="<?= base_url('admin/team/create') ?>" class="btn btn-primary">
                            <i class="bi bi-plus me-1"></i> Add New Team
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="table-team">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="10%">Image</th>
                                    <th>Name</th>
                                    <th>NIP</th>
                                    <th>NIDN</th>
                                    <th>Lab Position</th>
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

<?php ob_start(); ?>
<script>
    var audio = new Audio("<?= base_url('assets/audio/success.wav'); ?>");

    $(document).ready(function() {
        const table = $('#table-team').DataTable({
            processing: true,
            serverSide: false,
            responsive: false,
            autoWidth: false,
            ajax: '<?= base_url('admin/team/data'); ?>',
            columns: [{
                    data: null,
                    sortable: false,
                    render: (data, type, row, meta) =>
                        meta.row + meta.settings._iDisplayStart + 1
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
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'nip',
                    name: 'nip'
                },
                {
                    data: 'nidn',
                    name: 'nidn'
                },
                {
                    data: 'lab_position',
                    name: 'lab_position'
                },
                {
                    data: null,
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center text-nowrap',
                    render: function(data, type, row) {
                        let editUrl = '<?php echo base_url('admin/team'); ?>/' + row.id + '/edit';
                        let deleteUrl = '<?php echo base_url('admin/team'); ?>/' + row.id;

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
                text: "You can't undo this action!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: "Cancel",
                confirmButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        dataType: 'JSON',
                        success: function(res) {
                            if (res.success) {
                                table.ajax.reload();
                                audio.play();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted',
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

        <?php if (isset($_SESSION['success_message'])): ?>
            audio.play();
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '<?= $_SESSION['success_message']; ?>',
                showConfirmButton: false,
                timer: 1500
            });
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>
    });
</script>
<?php
$pageScripts = ob_get_clean();
?>