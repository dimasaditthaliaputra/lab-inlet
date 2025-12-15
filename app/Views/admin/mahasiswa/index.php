<div class="page-heading">
    <h3 class="page-title"><?php echo e($title ?? 'Judul Halaman'); ?></h3>
</div>

<div class="page-content">
    <section class="row">
        <div class="col-12">
            <div class="card border">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title">Daftar Mahasiswa</h4>
                        <?php if (in_array('create', $access)): ?>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalForm" data-url="">
                                <i class="bi bi-plus me-1" role="img" aria-label="Add new mahasiswa"></i>
                                Tambah Mahasiswa
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="data-tables">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>NIM</th>
                                    <th>Nama Lengkap</th>
                                    <th>Program Studi</th>
                                    <th>Semester</th>
                                    <th class="text-center">Status</th>
                                    <th width="20%">Aksi</th>
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
                        Form Data Mahasiswa
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
                            <label for="nim" class="col-md-3 col-form-label">NIM</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="nim" id="nim" placeholder="Input NIM Mahasiswa">
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label for="full_name" class="col-md-3 col-form-label">Nama Lengkap</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="full_name" id="full_name" placeholder="Input Nama Lengkap">
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label for="university" class="col-md-3 col-form-label">Universitas</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="university" id="university" placeholder="Input Universitas">
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label for="study_program" class="col-md-3 col-form-label">Program Studi</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="study_program" id="study_program" placeholder="Input Program Studi">
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label for="entry_year" class="col-md-3 col-form-label">Tahun Masuk</label>
                            <div class="col-md-9">
                                <input type="number" class="form-control" name="entry_year" id="entry_year" placeholder="Contoh: 2023">
                            </div>
                        </div>
                        
                        <div class="row mb-3 align-items-center">
                            <label for="current_semester" class="col-md-3 col-form-label">Semester</label>
                            <div class="col-md-9">
                                <input type="number" class="form-control" name="current_semester" id="current_semester" placeholder="Contoh: 5">
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label for="phone_number" class="col-md-3 col-form-label">Nomor HP</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="phone_number" id="phone_number" placeholder="Input Nomor HP">
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label for="address" class="col-md-3 col-form-label">Alamat</label>
                            <div class="col-md-9">
                                <textarea class="form-control" name="address" id="address" placeholder="Input Alamat"></textarea>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label for="status" class="col-md-3 col-form-label">Status</label>
                            <div class="col-md-9">
                                <select name="status" id="status" class="form-control">
                                    <option value="Aktif">Aktif</option>
                                    <option value="Non-Aktif">Non-Aktif</option>
                                    <option value="Lulus">Lulus</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                            Tutup
                        </button>
                        <button type="submit" class="btn btn-primary" id="btnSubmit">
                            <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                            Simpan
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
    const CAN_DELETE = <?php echo in_array('delete', $access) ? 'true' : 'false'; ?>;

    var showAction = (CAN_UPDATE || CAN_DELETE);

    $(document).ready(function() {
        $('#data-tables').DataTable({
            processing: true,
            responsive: true,
            autoWidth: false,
            ajax: '<?php echo base_url('admin/mahasiswa/data'); ?>',
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
                    data: 'nim',
                    name: 'nim'
                },
                {
                    data: 'full_name',
                    name: 'full_name',
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'study_program',
                    name: 'study_program',
                },
                {
                    data: 'current_semester',
                    name: 'current_semester',
                    className: 'text-center',
                },
                {
                    data: 'status',
                    name: 'status',
                    className: 'text-center',
                    render: function(data) {
                        let badgeClass = 'bg-secondary';
                        if (data === 'Aktif') badgeClass = 'bg-success';
                        else if (data === 'Non-Aktif') badgeClass = 'bg-danger';
                        else if (data === 'Lulus') badgeClass = 'bg-info';

                        return `<span class="badge ${badgeClass}">${data}</span>`;
                    }
                },
                {
                    data: null,
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    visible: showAction,
                    render: function(data, type, row) {
                        let editUrl = '<?php echo base_url('admin/mahasiswa'); ?>/' + row.id + '/edit';
                        let deleteUrl = '<?php echo base_url('admin/mahasiswa'); ?>/' + row.id;

                        let buttons = '';

                        if (CAN_UPDATE) {
                            buttons += `<button type="button" data-url="${editUrl}" class="btn btn-warning btn-sm" id="btnEdit"><i class="fas fa-edit"></i> Edit</button>`;
                        }

                        if (CAN_DELETE) {
                            buttons += `<button type="button" data-url="${deleteUrl}" class="btn btn-danger btn-sm ms-2" id="btnDelete"><i class="fas fa-trash"></i> Hapus</button>`;
                        }

                        return buttons;
                    }
                },
            ]
        });

        // Event handler untuk EDIT
        $(document).on('click', '#btnEdit', function(e) {
            e.preventDefault();
            let url = $(this).data('url');

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'JSON',
                success: function(res) {
                    if (res.success) {
                        $('#modalTitleId').html('Form Edit Data Mahasiswa');
                        $('#primary_id').val(res.data.id);
                        $('#nim').val(res.data.nim);
                        $('#full_name').val(res.data.full_name);
                        $('#university').val(res.data.university);
                        $('#study_program').val(res.data.study_program);
                        $('#entry_year').val(res.data.entry_year);
                        $('#current_semester').val(res.data.current_semester);
                        $('#phone_number').val(res.data.phone_number);
                        $('#address').val(res.data.address);
                        $('#status').val(res.data.status); // Set nilai dropdown status

                        $('#modalForm').modal('show');
                    }
                }
            });
        });

        // Reset form saat modal ditutup
        $('#modalForm').on('hidden.bs.modal', function(event) {
            $('#formData')[0].reset();
            $('.text-danger').remove();
            $('#modalTitleId').html('Form Data Mahasiswa');
            $('#primary_id').val('');
            $('#status').val('Aktif'); // Reset status default
        });

        // Event handler untuk SUBMIT (Create/Update)
        $('#formData').submit(function(e) {
            e.preventDefault();

            var btn = $('#btnSubmit');
            var spinner = btn.find('.spinner-border');

            btn.prop('disabled', true);
            spinner.removeClass('d-none');

            let id = $('#primary_id').val();
            let formData = $(this).serialize();
            let baseUrl = '<?php echo base_url('admin/mahasiswa'); ?>';
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

        // Event handler untuk DELETE
        $(document).on('click', '#btnDelete', function(e) {
            e.preventDefault();
            let url = $(this).data('url');

            Swal.fire({
                title: "Anda yakin?",
                text: "Data mahasiswa akan dihapus secara permanen!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: "Batal",
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
                            // ... (Error handling)
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Gagal menghapus data. Cek apakah Mahasiswa ini masih terikat ke User!'
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