<div class="page-heading">
    <h3 class="page-title"><?php echo e($title ?? 'Permissions Management'); ?></h3>
</div>

<div class="page-content">
    <section class="row">
        <div class="col-12">
            <div class="card border">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Role Permissions</h4>
                        <div class="d-flex gap-2 align-items-center w-25">
                            <select class="form-select" id="role_select" style="width: 250px;">
                                <option value=""></option>
                                <?php foreach ($listRoles as $role): ?>
                                    <option value="<?php echo e($role->id); ?>">
                                        <?php echo e($role->role_name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="loading-state" class="text-center py-5 d-none">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading permissions...</p>
                    </div>

                    <div id="empty-state" class="text-center py-5">
                        <i class="bi bi-shield-lock" style="font-size: 3rem; color: #ccc;"></i>
                        <p class="mt-3 text-muted">Please select a role to manage permissions</p>
                    </div>

                    <div id="permissions-container" class="d-none">
                        <form id="permissionsForm">
                            <input type="hidden" name="role_id" id="role_id">

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="5%" class="text-center">No</th>
                                            <th width="30%">Menu Name</th>
                                            <th width="15%" class="text-center">
                                                <label class="mb-0">
                                                    <input type="checkbox" id="check_all_read" class="form-check-input me-1">
                                                    Read
                                                </label>
                                            </th>
                                            <th width="15%" class="text-center">
                                                <label class="mb-0">
                                                    <input type="checkbox" id="check_all_create" class="form-check-input me-1">
                                                    Create
                                                </label>
                                            </th>
                                            <th width="15%" class="text-center">
                                                <label class="mb-0">
                                                    <input type="checkbox" id="check_all_update" class="form-check-input me-1">
                                                    Update
                                                </label>
                                            </th>
                                            <th width="15%" class="text-center">
                                                <label class="mb-0">
                                                    <input type="checkbox" id="check_all_delete" class="form-check-input me-1">
                                                    Delete
                                                </label>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="permissions-tbody">

                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-3">
                                <button type="button" class="btn btn-secondary" id="btnReset">
                                    <i class="bi bi-arrow-clockwise me-1"></i>
                                    Reset
                                </button>
                                <?php if (in_array('update', $access)): ?>
                                    <button type="submit" class="btn btn-primary" id="btnSave">
                                        <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                                        <i class="bi bi-save me-1"></i>
                                        Save Permissions
                                    </button>
                                <?php endif; ?>
                            </div>
                        </form>
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
    var rolePermissionsData = [];

    $(document).ready(function() {
        $('#role_select').select2({
            theme: 'bootstrap-5',
            placeholder: 'Choose Role',
            minimumResultsForSearch: Infinity,
            allowClear: true,
            width: '100%'
        });

        $('#role_select').on('change', function() {
            let roleId = $(this).val();
            if (roleId) {
                loadRolePermissions(roleId);
            } else {
                showEmptyState();
            }
        });

        $('#check_all_read').on('change', function() {
            $('.permission-read').prop('checked', $(this).prop('checked'));

            if (!$(this).prop('checked')) {
                $('.permission-create, .permission-update, .permission-delete').prop('checked', false);
                $('#check_all_create, #check_all_update, #check_all_delete').prop('checked', false);
            }
        });

        $('#check_all_create').on('change', function() {
            $('.permission-create').prop('checked', $(this).prop('checked'));

            if ($(this).prop('checked')) {
                $('.permission-read').prop('checked', true);
                $('#check_all_read').prop('checked', true);
            }
        });

        $('#check_all_update').on('change', function() {
            $('.permission-update').prop('checked', $(this).prop('checked'));
            if ($(this).prop('checked')) {
                $('.permission-read').prop('checked', true);
                $('#check_all_read').prop('checked', true);
            }
        });

        $('#check_all_delete').on('change', function() {
            $('.permission-delete').prop('checked', $(this).prop('checked'));
            if ($(this).prop('checked')) {
                $('.permission-read').prop('checked', true);
                $('#check_all_read').prop('checked', true);
            }
        });

        $(document).on('change', '.permission-read', function() {
            if (!$(this).is(':checked')) {
                let row = $(this).closest('tr');
                row.find('.permission-create, .permission-update, .permission-delete').prop('checked', false);
            }
            updateCheckAllStatus();
        });

        $(document).on('change', '.permission-create, .permission-update, .permission-delete', function() {
            if ($(this).is(':checked')) {
                let row = $(this).closest('tr');
                row.find('.permission-read').prop('checked', true);
            }
            updateCheckAllStatus();
        });

        $('#btnReset').on('click', function() {
            let roleId = $('#role_select').val();
            if (roleId) {
                loadRolePermissions(roleId);
            }
        });

        $('#permissionsForm').on('submit', function(e) {
            e.preventDefault();
            savePermissions();
        });
    });

    function loadRolePermissions(roleId) {
        showLoadingState();

        $.ajax({
            url: '<?php echo base_url('admin/permissions/data'); ?>/' + roleId,
            type: 'GET',
            dataType: 'JSON',
            success: function(res) {
                if (res.success) {
                    rolePermissionsData = res.data;
                    $('#role_id').val(roleId);
                    renderPermissionsTable();
                    showPermissionsContainer();
                } else {
                    showEmptyState();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: res.message
                    });
                }
            },
            error: function(xhr) {
                showEmptyState();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load permissions'
                });
            }
        });
    }

    function renderPermissionsTable() {
        let html = '';
        let no = 1;

        rolePermissionsData.forEach(function(item) {
            let available = [];
            if (item.available_permissions) {
                try {
                    available = (typeof item.available_permissions === 'string') ?
                        JSON.parse(item.available_permissions) : item.available_permissions;
                } catch (e) {
                    available = [];
                }
            }

            let current = [];
            if (item.current_permissions) {
                try {
                    let parsed = (typeof item.current_permissions === 'string') ?
                        JSON.parse(item.current_permissions) : item.current_permissions;

                    if (Array.isArray(parsed)) {
                        current = parsed;
                    } else if (typeof parsed === 'object' && parsed !== null) {
                        Object.keys(parsed).forEach(key => {
                            if (parsed[key] === true) current.push(key);
                        });
                    }
                } catch (e) {
                    console.error("JSON Parse current_permissions error", e);
                }
            }

            let isHeader = (available === null || available.length === 0);
            let rowClass = isHeader ? 'fw-bold table-light' : '';

            let nameDisplay = item.menu_name;
            if (item.level == 1) {
                nameDisplay = `<span class="ms-4">├─ ${item.menu_name}</span>`;
            } else if (!isHeader) {
                nameDisplay = `<span class="fw-bold">${item.menu_name}</span>`;
            }

            function makeCheckbox(type) {
                if (isHeader) return '';

                if (available.includes(type)) {
                    let isChecked = current.includes(type) ? 'checked' : '';

                    return `
                        <input type="checkbox" class="form-check-input permission-${type}" 
                               name="permissions[${item.id}][${type}]" 
                               ${isChecked}>
                    `;
                } else {
                    return '';
                }
            }

            html += `
                <tr>
                    <td class="text-center">${isHeader ? '' : no++}</td>
                    <td class="${rowClass}">
                        ${item.icon ? `<i class="${item.icon} me-2"></i>` : ''} 
                        ${nameDisplay}
                    </td>
                    <td class="text-center">${makeCheckbox('read')}</td>
                    <td class="text-center">${makeCheckbox('create')}</td>
                    <td class="text-center">${makeCheckbox('update')}</td>
                    <td class="text-center">${makeCheckbox('delete')}</td>
                </tr>
            `;
        });

        $('#permissions-tbody').html(html);
        updateCheckAllStatus();
    }

    function updateCheckAllStatus() {
        let types = ['read', 'create', 'update', 'delete'];

        types.forEach(type => {
            let allCheckboxes = $(`.permission-${type}`);
            let total = allCheckboxes.length;
            let checked = allCheckboxes.filter(':checked').length;

            let headerCheckbox = $(`#check_all_${type}`);

            if (total === 0) {
                headerCheckbox.prop('checked', false).prop('disabled', true);
            } else {
                headerCheckbox.prop('disabled', false);
                headerCheckbox.prop('checked', total === checked);
            }
        });
    }

    function savePermissions() {
        let btn = $('#btnSave');
        let spinner = btn.find('.spinner-border');

        btn.prop('disabled', true);
        spinner.removeClass('d-none');

        let permissions = {};

        $('#permissions-tbody input[type="checkbox"]:checked').each(function() {
            let name = $(this).attr('name');
            let match = name.match(/permissions\[(\d+)\]\[(\w+)\]/);
            if (match) {
                let id = match[1];
                let type = match[2];

                if (!permissions[id]) permissions[id] = {};
                permissions[id][type] = true;
            }
        });

        let roleId = $('#role_id').val();

        $.ajax({
            url: '<?php echo base_url('admin/permissions/update'); ?>',
            type: 'PUT',
            data: JSON.stringify({
                role_id: roleId,
                permissions: permissions
            }),
            contentType: 'application/json',
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
                    });
                    loadRolePermissions(roleId);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: res.message
                    });
                }
            },
            error: function(xhr) {
                let errorMessage = xhr.responseJSON?.message || 'Failed to save permissions';
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage
                });
            },
            complete: function() {
                btn.prop('disabled', false);
                spinner.addClass('d-none');
            }
        });
    }

    function showLoadingState() {
        $('#empty-state').addClass('d-none');
        $('#permissions-container').addClass('d-none');
        $('#loading-state').removeClass('d-none');
    }

    function showEmptyState() {
        $('#loading-state').addClass('d-none');
        $('#permissions-container').addClass('d-none');
        $('#empty-state').removeClass('d-none');
    }

    function showPermissionsContainer() {
        $('#loading-state').addClass('d-none');
        $('#empty-state').addClass('d-none');
        $('#permissions-container').removeClass('d-none');
    }
</script>
<?php
$pageScripts = ob_get_clean();
?>