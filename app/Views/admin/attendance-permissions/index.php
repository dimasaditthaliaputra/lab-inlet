<div class="page-heading">
  <div class="page-title">
    <div class="row">
      <div class="col-12 col-md-6 order-md-1 order-last">
        <h3><?= e($title ?? 'Attendance Permissions'); ?></h3>
        <p class="text-subtitle text-muted">List pengajuan izin kehadiran.</p>
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
            <h4 class="card-title">List Attendance Permissions</h4>
          </div>
        </div>

        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped table-hover" id="table-attendance-permissions">
              <thead>
                <tr>
                  <th width="5%">No</th>
                  <th>Nama Mahasiswa</th>
                  <th>Jenis Izin</th>
                  <th class="text-center">Tanggal Mulai</th>
                  <th class="text-center">Tanggal Berakhir</th>
                  <th class="text-center">Status</th>
                  <th class="text-center" width="10%">Action</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>

      </div>
    </div>
  </section>
</div>

<?php ob_start(); ?>
<script>
  var audio = new Audio("<?= base_url('assets/audio/success.wav'); ?>");

  $(document).ready(function () {
    $('#table-attendance-permissions').DataTable({
      processing: true,
      serverSide: false,
      responsive: false,
      autoWidth: false,
      pageLength: 10,
      lengthChange: false,
      ajax: '<?= base_url('admin/attendance-permissions/data'); ?>',
      columns: [
        {
          data: null,
          sortable: false,
          render: function(data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
          }
        },
        { data: 'mahasiswa_name', name: 'mahasiswa_name' },
        { data: 'permission_type', name: 'permission_type' },
        { data: 'start_date', className: 'text-center' },
        { data: 'end_date', className: 'text-center' },
        {
          data: 'status',
          className: 'text-center',
          render: function (data) {
            if (data === 'approved') return '<span class="badge bg-success">Approved</span>';
            if (data === 'rejected') return '<span class="badge bg-danger">Rejected</span>';
            return '<span class="badge bg-secondary">Pending</span>';
          }
        },
        {
          data: null,
          orderable: false,
          searchable: false,
          className: 'text-center text-nowrap',
          render: function (data, type, row) {
            let viewUrl = '<?= base_url('admin/attendance-permissions'); ?>/' + row.id + '/view';
            return `
              <a href="${viewUrl}" class="btn btn-success btn-sm" title="View Detail">
                <i class="fas fa-eye"></i>
              </a>
            `;
          }
        }
      ]
    });
  });
</script>
<?php $pageScripts = ob_get_clean(); ?>
