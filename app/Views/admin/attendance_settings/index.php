<div class="page-heading">
    <h3 class="page-title"><?php echo e($title ?? 'Judul Halaman'); ?></h3>
</div>

<div class="page-content">
    <section class="row">
        <div class="col-12">
            <div class="card border">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title">Attendance List</h4>
                        <div id="about-content" class="card border-0 shadow-sm" style="display: none;"></div>
                        <?php if (in_array('create', $access) && empty($data)) : ?>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalForm" data-url="">
                                <i class="bi bi-plus me-1"></i>
                                Add New Attendance
                            </button>
                        <?php endif ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="data-tables">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Mask Schedule</th>
                                    <th>check-in time limit</th>
                                    <th>Homecoming Schedule</th>
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
                        Form Attendance
                    </h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="formData" action="<?php echo base_url('admin/attendance_settings') ?>" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="primary_id" id="primary_id">

                        <div class="row mb-3 align-items-center">
                            <label for="clock_in_start_time" class="col-md-3 col-form-label">Mask Schedule</label>
                            <div class="col-md-9">
                                <input type="time" class="form-control" name="clock_in_start_time" id="clock_in_start_time" placeholder="Input Mask Schedule">
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label for="clock_in_end_time" class="col-md-3 col-form-label">check-in time limit</label>
                            <div class="col-md-9">
                                <input type="time" class="form-control" name="clock_in_end_time" id="clock_in_end_time" placeholder="Input Limit">
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <label for="clock_out_start_time" class="col-md-3 col-form-label">Homecoming Schedule</label>
                            <div class="col-md-9">
                                <input type="time" class="form-control" name="clock_out_start_time" id="clock_out_start_time" placeholder="Input Homecoming Schedule">
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

    const CAN_UPDATE = <?php echo in_array('update', $access) ? 'true' : 'false'; ?>;

    var showAction = (CAN_UPDATE);

    $(document).ready(function() {
        $('#data-tables').DataTable({
            processing: true,
            responsive: true,
            autoWidth: false,
            ajax: '<?php echo base_url('admin/attendance-settings/data'); ?>',
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
                    data: 'clock_in_start_time',
                    name: 'clock_in_start_time'
                },
                {
                    data: 'clock_in_end_time',
                    name: 'clock_in_end_time'
                },
                {
                    data: 'clock_out_start_time',
                    name: 'clock_out_start_time'
                },
                {
                    data: null,
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    visible: showAction,
                    render: function(data, type, row) {
                        let editUrl = '<?php echo base_url('admin/attendance-settings'); ?>/' + row.id + '/edit';
                        let deleteUrl = '<?php echo base_url('admin/attendance-settings'); ?>/' + row.id;

                        let buttons = '';

                        if (CAN_UPDATE) {
                            buttons += `<button type="button" data-url="${editUrl}" class="btn btn-warning btn-sm" id="btnEdit"><i class="fas fa-edit"></i> Edit</button>`;
                        }

                        if (CAN_DELETE) {
                            buttons += `<button type="button" data-url="${deleteUrl}" class="btn btn-danger btn-sm" id="btnDelete"><i class="fas fa-trash"></i> Delete</button>`;
                        }

                        return buttons;
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
                        $('#modalTitleId').html('Form Edit Attendance');
                        $('#primary_id').val(res.data.id);
                        $('#clock_in_start_time').val(res.data.clock_in_start_time);
                        $('#clock_in_end_time').val(res.data.clock_in_end_time);
                        $('#clock_out_start_time').val(res.data.clock_out_start_time);
                        $('#modalForm').modal('show');
                    }
                }
            });
        });

        $('#modalForm').on('hidden.bs.modal', function(event) {
            $('#formData')[0].reset();

            $('#modalTitleId').html('Form Attendance');
            $('.text-danger').remove();
            $('#primary_id').val('');
            $('#clock_in_start_time').val('');
            $('#clock_in_end_time').val('');
            $('#clock_out_start_time').val('');
        });

        $('#formData').submit(function(e) {
            e.preventDefault();

            var btn = $('#btnSubmit');
            var spinner = btn.find('.spinner-border');

            btn.prop('disabled', true);
            spinner.removeClass('d-none');

            let id = $('#primary_id').val();
            let formData = $(this).serialize();
            let baseUrl = '<?php echo base_url('admin/attendance-settings'); ?>';
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