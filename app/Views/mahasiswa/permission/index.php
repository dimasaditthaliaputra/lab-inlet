<div class="page-heading">
    <h3 class="page-title"><?php echo e($title ?? 'Permission'); ?></h3>
</div>

<div class="page-content">
    <section class="row">
        <div class="col-12">
            <div class="card border">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title">Submission History</h4>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalForm">
                            <i class="bi bi-plus me-1"></i>
                            Create Request
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="data-tables">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Type</th>
                                    <th>Date</th>
                                    <th>Reason</th>
                                    <th>Attachment</th>
                                    <th>Status</th>
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

    <div class="modal fade" id="modalForm" tabindex="-1" data-bs-backdrop="static" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitleId">Permission Request Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formData" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Permission Type</label>
                            <select class="form-select" name="permission_type" id="permission_type" required>
                                <option value=""></option>
                                <option value="sick">Sick</option>
                                <option value="leave">Leave / Permit</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Start</label>
                                <input type="date" class="form-control" name="start_date" id="start_date" required>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">End</label>
                                <input type="date" class="form-control" name="end_date" id="end_date" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Reason</label>
                            <textarea class="form-control" name="reason" id="reason" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Proof (Optional)</label>
                            <input type="file" class="form-control" name="attachment" id="attachment" accept=".jpg,.png,.pdf">
                            <small class="text-muted">Max 2MB (JPG, PNG, PDF)</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="btnSubmit">
                            <span class="spinner-border spinner-border-sm me-2 d-none" role="status"></span> Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php ob_start(); ?>
<script>
    var audio = new Audio("<?php echo base_url('assets/audio/success.wav'); ?>");

    $(document).ready(function() {
        $('#permission_type').select2({
            theme: 'bootstrap-5',
            dropdownParent: $('#modalForm'),
            placeholder: 'Select Permission Type',
            width: '100%',
            closeOnSelect: false,
        });

        $('#data-tables').DataTable({
            processing: true,
            responsive: true,
            autoWidth: false,
            ajax: '<?php echo base_url('mahasiswa/request-permission/data'); ?>',
            columns: [
                {
                    data: null,
                    render: (data, type, row, meta) => meta.row + 1
                },
                {
                    data: 'permission_type',
                    render: function(data) {
                        const types = {
                            'sick': '<span class="badge bg-danger">Sick</span>',
                            'leave': '<span class="badge bg-warning text-dark">Permit</span>',
                            'other': '<span class="badge bg-secondary">Other</span>'
                        };
                        return types[data] || data;
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return `${row.start_date} <i class="bi bi-arrow-right-short"></i> ${row.end_date}`;
                    }
                },
                { data: 'reason' },
                {
                    data: 'attachment',
                    render: function(data) {
                        if (data) {
                            return `<a href="<?php echo asset('uploads/attendance_permissions/'); ?>${data}" target="_blank" class="btn btn-xs btn-outline-info"><i class="bi bi-paperclip"></i> View</a>`;
                        }
                        return '<span class="text-muted">-</span>';
                    }
                },
                {
                    data: 'status',
                    render: function(data) {
                        const badges = {
                            'pending': '<span class="badge bg-warning">Pending</span>',
                            'approved': '<span class="badge bg-success">Approved</span>',
                            'rejected': '<span class="badge bg-danger">Rejected</span>'
                        };
                        return badges[data] || data;
                    }
                },
                {
                    data: null,
                    className: 'text-center',
                    render: function(data, type, row) {
                        if (row.status === 'pending') {
                            let deleteUrl = '<?php echo base_url('mahasiswa/request-permission/destroy'); ?>/' + row.id;
                            return `<button type="button" data-url="${deleteUrl}" class="btn btn-danger btn-sm" id="btnDelete"><i class="bi bi-trash"></i></button>`;
                        }
                        return '';
                    }
                }
            ]
        });

        $('#modalForm').on('hidden.bs.modal', function() {
            $('#formData')[0].reset();
            $('#permission_type').val('').trigger('change');
            $('#start_date').val('');
            $('#end_date').val('');
            $('#reason').val('');
            $('#attachment').val('')
            $('.text-danger').remove();
        });

        $('#formData').submit(function(e) {
            e.preventDefault();

            var btn = $('#btnSubmit');
            var spinner = btn.find('.spinner-border');
            
            var formData = new FormData(this);

            btn.prop('disabled', true);
            spinner.removeClass('d-none');

            $.ajax({
                url: '<?php echo base_url('mahasiswa/request-permission/store'); ?>',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'JSON',
                success: function(res) {
                    if (res.success) {
                        $('#modalForm').modal('hide');
                        $('#data-tables').DataTable().ajax.reload();
                        audio.play();
                        Swal.fire({ icon: 'success', title: 'Success', text: res.message, showConfirmButton: false, timer: 1500 });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Failed', text: res.message });
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $('.text-danger').remove();
                        $.each(errors, function(key, value) {
                            $(`#${key}`).after(`<small class="text-danger d-block mt-1">${value}</small>`);
                        });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', text: xhr.responseJSON?.message || 'Server Error' });
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
                title: "Cancel Request?",
                text: "The data will be permanently deleted.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        dataType: 'JSON',
                        success: function(res) {
                            if (res.success) {
                                audio.play();
                                Swal.fire({ icon: 'success', title: 'Deleted', text: res.message, showConfirmButton: false, timer: 1500 });
                                $('#data-tables').DataTable().ajax.reload();
                            } else {
                                Swal.fire({ icon: 'error', title: 'Failed', text: res.message });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({ icon: 'error', title: 'Error', text: xhr.responseJSON?.message || 'Server Error' });
                        }
                    });
                }
            });
        });
    });
</script>
<?php $pageScripts = ob_get_clean(); ?>