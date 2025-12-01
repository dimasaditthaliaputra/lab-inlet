<div class="page-heading">
    <h3 class="page-title"><?php echo e($title ?? 'Judul Halaman'); ?></h3>
</div>

<div class="page-content">
    <section class="row">
        <div class="col-12">
            <div class="card border">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title">Roles List</h4>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalForm" data-url="">
                            <i class="bi bi-plus me-1" role="img" aria-label="Add new roles"></i>
                            Add New Roles
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="data-tables">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Role Name</th>
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
        role="dialog"
        aria-labelledby="modalTitleId"
        aria-hidden="true">
        <div
            class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg"
            role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitleId">
                        Form Roles
                    </h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="formData" action="<?php echo base_url('admin/roles') ?>" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="primary_id" id="primary_id">

                        <div class="row mb-3 align-items-center">
                            <label for="role_name" class="col-md-3 col-form-label">Role Name</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="role_name" id="role_name" placeholder="Input role name">
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
            ajax: '<?php echo base_url('admin/roles/data'); ?>',
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
                    data: 'role_name',
                    name: 'role_name'
                },
                {
                    data: null,
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        let editUrl = '<?php echo base_url('admin/roles'); ?>/' + row.id + '/edit';
                        let deleteUrl = '<?php echo base_url('admin/roles'); ?>/' + row.id;

                        return `
                            <button type="button" data-url="${editUrl}" class="btn btn-warning btn-sm" id="btnEdit"><i class="fas fa-edit"></i></button>
                            <button type="button" data-url="${deleteUrl}" class="btn btn-danger btn-sm" id="btnDelete"><i class="fas fa-trash"></i></button>
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
                        $('#modalTitleId').html('Form Edit Roles');
                        $('#primary_id').val(res.data.id);
                        $('#role_name').val(res.data.role_name);
                        $('#modalForm').modal('show');
                    }
                }
            });
        });

        $('#modalForm').on('hidden.bs.modal', function(event) {
            $('#formData')[0].reset();

            $('#modalTitleId').html('Form Roles');
            $('.text-danger').remove();
            $('#primary_id').val('');
            $('#role_name').val('');
        });

        $('#formData').submit(function(e) {
            e.preventDefault();

            var btn = $('#btnSubmit');
            var spinner = btn.find('.spinner-border');

            btn.prop('disabled', true);
            spinner.removeClass('d-none');

            let id = $('#primary_id').val();
            let formData = $(this).serialize();
            let baseUrl = '<?php echo base_url('admin/roles'); ?>';
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
                        console.error('Terjadi kesalahan server:', error);
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
                                audio.play();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: res.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                })
                                
                                $('#data-tables').DataTable().ajax.reload();
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