<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3><?php echo e($title ?? 'Activity Log'); ?></h3>
                <p class="text-subtitle text-muted">List History Activity User Sistem.</p>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="row">
        <div class="col-12">
            <div class="card border">
                <div class="card-header">
                    <h4 class="card-title">Log History</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="table-log">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="15%">Time</th>
                                    <th width="15%">User</th>
                                    <th width="10%">Type Action</th>
                                    <th>Description</th>
                                    <th width="10%">Detail</th>
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

<div
    class="modal fade"
    id="modalDetail"
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
                <h5 class="modal-title" id="modalDetailTitle">Detail Perubahan Data</h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Data Lama (Old)</h6>
                        <pre class="bg-light p-2 border rounded" id="viewOldData" style="max-height: 300px; overflow: auto;">-</pre>
                    </div>
                    <div class="col-md-6">
                        <h6>Data Baru (New)</h6>
                        <pre class="bg-light p-2 border rounded" id="viewNewData" style="max-height: 300px; overflow: auto;">-</pre>
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
            </div>
        </div>
    </div>
</div>

<?php
ob_start();
?>
<script>
    $(document).ready(function() {
        $('#table-log').DataTable({
            processing: true,
            serverSide: false,
            responsive: true,
            autoWidth: false,
            ajax: '<?php echo base_url('admin/log-activity/data'); ?>',
            columns: [{
                    data: null,
                    sortable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'username',
                    name: 'username',
                    render: function(data) {
                        return data ? `<span class="font-weight-bold">${data}</span>` : '<span class="text-muted">System/Guest</span>';
                    }
                },
                {
                    data: 'action_type',
                    name: 'action_type',
                    render: function(data) {
                        let badgeClass = 'bg-secondary';
                        let text = data.toUpperCase();

                        if (text === 'LOGIN') badgeClass = 'bg-success';
                        else if (text === 'LOG OUT') badgeClass = 'bg-secondary';
                        else if (text === 'CREATE') badgeClass = 'bg-primary';
                        else if (text === 'UPDATE') badgeClass = 'bg-info';
                        else if (text === 'DELETE') badgeClass = 'bg-danger';

                        return `<span class="badge ${badgeClass}">${text}</span>`;
                    }
                },
                {
                    data: 'description',
                    name: 'description'
                },
                {
                    data: null,
                    orderable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        let oldData = row.old_data ? encodeURIComponent(JSON.stringify(row.old_data)) : '';
                        let newData = row.new_data ? encodeURIComponent(JSON.stringify(row.new_data)) : '';

                        let disabled = (row.old_data == null && row.new_data == null) ? 'disabled' : '';

                        return `
                            <button type="button" 
                                class="btn btn-info btn-sm btn-detail" 
                                data-old="${oldData}" 
                                data-new="${newData}"
                                ${disabled}>
                                <i class="fas fa-eye"></i> Info
                            </button>
                        `;
                    }
                }
            ]
        });

        $(document).on('click', '.btn-detail', function() {
            let oldDataRaw = $(this).data('old');
            let newDataRaw = $(this).data('new');

            let oldContent = '-';
            let newContent = '-';

            if (oldDataRaw) {
                try {
                    let json = JSON.parse(decodeURIComponent(oldDataRaw));
                    oldContent = JSON.stringify(json, null, 4);
                } catch (e) {
                    oldContent = 'Error parsing JSON';
                }
            }

            if (newDataRaw) {
                try {
                    let json = JSON.parse(decodeURIComponent(newDataRaw));
                    newContent = JSON.stringify(json, null, 4);
                } catch (e) {
                    newContent = 'Error parsing JSON';
                }
            }

            $('#viewOldData').text(oldContent);
            $('#viewNewData').text(newContent);
            $('#modalDetail').modal('show');
        });
    });
</script>
<?php
$pageScripts = ob_get_clean();
?>