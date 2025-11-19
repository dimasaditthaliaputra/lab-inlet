<div class="page-heading">
    <h3 class="page-title"><?php echo e($title ?? 'Judul Halaman'); ?></h3>
</div>

<div class="page-content">
    <section class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title">Daftar About Us</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="data-tables">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Vision</th>
                                    <th>Mission</th>
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
            ajax: '<?php echo base_url('admin/aboutus/data'); ?>',
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
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'description',
                    name: 'description'
                },
                {
                    data: 'vision',
                    name: 'vision'
                },
                {
                    data: 'mission',
                    name: 'mission'
                },
                {
                    data: null,
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        let editUrl = '<?php echo base_url('admin/aboutus'); ?>/' + row.id + '/edit';

                        return `
                            <a href="${editUrl}" class="btn btn-primary btn-sm">Detail</a>
                        `;


                    }
                },
            ]
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

    })
</script>
<?php
$pageScripts = ob_get_clean();
?>