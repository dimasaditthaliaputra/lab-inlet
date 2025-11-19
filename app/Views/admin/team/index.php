<div class="page-heading">
    <h3 class="page-title"><?php echo e($title ?? 'Judul Halaman'); ?></h3>
</div>

<div class="page-content">
    <section class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title">Daftar Team</h4>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalForm" data-url="">Tambah Team Baru</button>
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
                                    <th>nip</th>
                                    <th>nidn</th>
                                    <th>study_program</th>
                                    <th>description</th>
                                    <th>social_media</th>
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
            processing: false,
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
                    data: 'description',
                    name: 'description'
                },
                {
                    data: 'social_media',
                    name: 'social_media'
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
                            <button type="button" data-url="${editUrl}" class="btn btn-warning btn-sm" id="btnEdit">Edit</button>
                            <button type="button" data-url="${deleteUrl}" class="btn btn-danger btn-sm" id="btnDelete">Hapus</button>
                        `;
                    }
                },
            ]
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
                        $('#modalTitleId').html('Form Edit Team');
                        $('#primary_id').val(res.data.id);

                        $('#name').val(res.data.name);
                        $('#position').val(res.data.position);
                        $('#nip').val(res.data.nip);
                        $('#nidn').val(res.data.nidn);
                        $('#study_program').val(res.data.study_program);
                        $('#description').val(res.data.description);
                        $('#social_media').val(res.data.social_media);

                        $('#modalForm').modal('show');
                    }
                }
            });
        });

        $('#modalForm').on('hidden.bs.modal', function(event) {
            $('#formData')[0].reset();
            $('#primary_id').val('');

            $('#name').val('');
            $('#position').val('');
            $('#nip').val('');
            $('#nidn').val('');
            $('#study_program').val('');
            $('#description').val('');
            $('#social_media').val('');

        });

        $('#formData').submit(function(e) {
            e.preventDefault();

            let social = {
                instagram: $('#instagram').val(),
                linkedin: $('#linkedin').val()
            };

            $('input[name="social_media"]').val(JSON.stringify(social));


            var btn = $('#btnSubmit');
            var spinner = btn.find('.spinner-border');

            btn.prop('disabled', true);
            spinner.removeClass('d-none');

            let id = $('#primary_id').val();
            let formData = $(this).serialize();
            let baseUrl = '<?php echo base_url('admin/team'); ?>';
            let url = id ? baseUrl + '/' + id : baseUrl;
            let method = id ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                type: method,
                data: formData,
                dataType: 'JSON',
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
                        let errorsHtml = '<ul>';
                        $.each(errors, function(key, value) {
                            errorsHtml += `<li>${value}</li>`;
                        });
                        errorsHtml += '</ul>';
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            html: errorsHtml
                        })
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