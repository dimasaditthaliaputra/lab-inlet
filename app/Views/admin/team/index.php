<div class="page-heading">
    <h3 class="page-title"><?php echo e($title ?? 'Judul Halaman'); ?></h3>
</div>

<div class="page-content">
    <section class="row">
        <div class="col-12">
            <div class="card border">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title">Daftar Team</h4>
                        <a href="<?php echo base_url('admin/team/create'); ?>" class="btn btn-primary">Tambah Team Baru</a>
                        <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalForm" data-url="">Tambah Team Baru</button> -->
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="data-tables">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <th>NIP</th>
                                    <th>NIDN</th>
                                    <th>Study Program</th>
                                    <th width="20%">Action</th>
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
        team="dialog"
        aria-labelledby="modalTitleId"
        aria-hidden="true">
        <div
            class="modal-dialog modal-dialog-scrollable modal-dialog-top modal-lg"
            team="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitleId">
                        Form Team
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
                            <label for="name" class="col-md-3 col-form-label">Team Name</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="name" id="name" placeholder="Masukkan nama team">
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <label for="position" class="col-md-3 col-form-label">Team Position</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="position" id="position" placeholder="Masukkan position team">
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <label for="nip" class="col-md-3 col-form-label">Team nip</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="nip" id="nip" placeholder="Masukkan nip team">
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <label for="nidn" class="col-md-3 col-form-label">Team nidn</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="nidn" id="nidn" placeholder="Masukkan nidn team">
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <label for="study_program" class="col-md-3 col-form-label">Team study_program</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="study_program" id="study_program" placeholder="Masukkan study_program team">
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <label for="description" class="col-md-3 col-form-label">Team description</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="description" id="description" placeholder="Masukkan description team">
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <label for="social_media" class="col-md-3 col-form-label">Team social_media</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control d-none" name="social_media" id="social_media" placeholder="Masukkan social_media team">
                                <input type="text" name="linkedin" class="form-control" placeholder="LinkedIn">
                                <input type="text" name="Google Scholar" class="form-control" placeholder="Google Scholar">
                                <input type="text" name="Sinta" class="form-control" placeholder="Sinta">
                                <input type="text" name="Email" class="form-control" placeholder="Email">
                                <input type="text" name="CV" class="form-control" placeholder="CV">
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
                            <span class="spinner-border spinner-border-sm me-2 d-none" team="status" aria-hidden="true"></span>
                            Save
                        </button>
                    </div>
                </form>
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
        $('#data-tables').DataTable({
            processing: true,
            responsive: true,
            autoWidth: false,
            ajax: '<?php echo base_url('admin/team/data'); ?>',
            columns: [{
                    data: null,
                    name: 'ordering',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'position',
                    name: 'position'
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
                    data: 'study_program',
                    name: 'study_program'
                },
                {
                    data: null,
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        let editUrl = '<?php echo base_url('admin/team'); ?>/' + row.id + '/edit';
                        let deleteUrl = '<?php echo base_url('admin/team'); ?>/' + row.id;

                        return `
                            <a href="${editUrl}" class="btn btn-warning btn-sm">Edit</a>
                            <button type="button" data-url="${deleteUrl}" class="btn btn-danger btn-sm" id="btnDelete"><i class="fas fa-trash"></i></button>
                        `;
                    }
                },
            ]
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

    <?php if (isset($_SESSION['success_message'])): ?>
        var audio = new Audio("<?php echo base_url('assets/audio/success.wav'); ?>");

        audio.play();
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '<?php echo $_SESSION['success_message']; ?>',
            showConfirmButton: false,
            timer: 1500
        });
    <?php
        unset($_SESSION['success_message']);
    endif;
    ?>
</script>
<?php
$pageScripts = ob_get_clean();
?>