<style>
    /* Matrix Table Styling */
    .table-matrix-wrapper {
        overflow-x: auto;
        position: relative;
        max-height: 75vh;
        /* Vertical scroll if there are many students */
    }

    .table-matrix {
        width: max-content;
        border-collapse: separate;
        border-spacing: 0;
    }

    /* Sticky Student Name (Left Column) */
    .table-matrix th.sticky-col,
    .table-matrix td.sticky-col {
        position: sticky;
        left: 0;
        background-color: #fff;
        z-index: 10;
        border-right: 2px solid #dee2e6;
        min-width: 250px;
        max-width: 250px;
    }

    .table-matrix th.sticky-col {
        z-index: 11;
        /* Name header has a higher z-index */
        background-color: #f8f9fa;
    }

    .table-matrix th {
        text-align: center;
        padding: 10px 5px;
        min-width: 60px;
        font-size: 0.85rem;
        background-color: #f8f9fa;
    }

    /* Cell Styles */
    .cell-data {
        height: 60px;
        padding: 4px;
        text-align: center;
        vertical-align: middle;
        border-bottom: 1px solid #e9ecef;
        border-right: 1px solid #e9ecef;
        font-size: 0.75rem;
        cursor: pointer;
        transition: background 0.2s;
    }

    .cell-data:hover {
        background-color: #f1f3f5 !important;
    }

    /* Status Colors */
    .bg-weekend {
        background-color: #e9ecef;
        color: #adb5bd;
    }

    .bg-present {
        background-color: #d1e7dd;
        color: #0f5132;
    }

    /* Light Green */
    .bg-late {
        background-color: #fff3cd;
        color: #664d03;
        border-left: 3px solid #ffc107;
    }

    /* Yellow */
    .bg-permission {
        background-color: #cff4fc;
        color: #055160;
    }

    /* Light Blue */
    .bg-alpha {
        background-color: #f8d7da;
        color: #842029;
    }

    /* Pink */
    .bg-empty {
        color: #dee2e6;
    }

    .status-dot {
        height: 8px;
        width: 8px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 3px;
    }
</style>

<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h3 class="page-title"><i class="bi bi-calendar-week me-2"></i> <?php echo e($title); ?></h3>
            <p class="text-subtitle text-muted">Monthly student attendance monitoring.</p>
        </div>

        <div class="d-flex gap-2">
            <input type="text" id="searchStudent" class="form-control form-control-sm" placeholder="Search Student..." style="width: 200px;">
            <select id="filterMonth" class="form-select form-select-sm" style="width: 120px;"></select>
            <select id="filterYear" class="form-select form-select-sm" style="width: 100px;"></select>
            <button id="btnFilter" class="btn btn-sm btn-primary"><i class="bi bi-funnel"></i></button>
        </div>
    </div>
</div>

<div class="page-content">
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="d-flex gap-3 p-3 border-bottom text-muted small bg-dark">
                <div class="d-flex align-items-center"><span class="status-dot bg-success"></span> Present</div>
                <div class="d-flex align-items-center"><span class="status-dot bg-warning"></span> Late</div>
                <div class="d-flex align-items-center"><span class="status-dot bg-info"></span> Permit/Sick</div>
                <div class="d-flex align-items-center"><span class="status-dot bg-danger"></span> Alpha (No Info)</div>
                <div class="d-flex align-items-center"><span class="status-dot bg-secondary"></span> Holiday</div>
            </div>

            <div class="table-matrix-wrapper">
                <table class="table-matrix table-hover" id="attendanceMatrix">
                    <thead id="matrixHead">
                    </thead>
                    <tbody id="matrixBody">
                        <tr>
                            <td colspan="10" class="text-center p-5">Loading data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Attendance Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalContent">
            </div>
        </div>
    </div>
</div>

<?php ob_start(); ?>
<script>
    const MONTH_NAMES = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    const BASE_URL = "<?= base_url() ?>";

    let globalData = {}; // Save data for client-side search filter

    $(document).ready(function() {
        initFilters();
        loadMatrixData();

        $('#btnFilter').click(loadMatrixData);

        // Search Live Filtering
        $('#searchStudent').on('keyup', function() {
            const val = $(this).val().toLowerCase();
            $("#matrixBody tr").filter(function() {
                $(this).toggle($(this).find('.student-name').text().toLowerCase().indexOf(val) > -1)
            });
        });
    });

    function initFilters() {
        const now = new Date();
        const currentMonth = now.getMonth() + 1;
        const currentYear = now.getFullYear();

        const monthSelect = $('#filterMonth');
        MONTH_NAMES.forEach((m, i) => {
            monthSelect.append(new Option(m, i + 1));
        });
        monthSelect.val(currentMonth);

        const yearSelect = $('#filterYear');
        for (let y = currentYear; y >= currentYear - 2; y--) {
            yearSelect.append(new Option(y, y));
        }
        yearSelect.val(currentYear);
    }

    function loadMatrixData() {
        const year = $('#filterYear').val();
        const month = $('#filterMonth').val();

        // Show Loading
        $('#matrixBody').html('<tr><td colspan="32" class="text-center p-5"><div class="spinner-border text-primary"></div></td></tr>');

        $.ajax({
            url: '<?= base_url("admin/attendance-history/data") ?>', // Adjust your route
            method: 'GET',
            data: {
                year: year,
                month: month
            },
            success: function(response) {
                if (response.success) {
                    renderMatrix(year, month, response);
                }
            },
            error: function() {
                alert('Failed to load data.');
            }
        });
    }

    function renderMatrix(year, month, data) {
        const daysInMonth = data.days_in_month;
        const students = data.students;
        const matrix = data.attendance_matrix;
        const todayStr = data.current_date;

        const thead = $('#matrixHead');
        const tbody = $('#matrixBody');

        // 1. Build Header (Date 1 - 30/31)
        let headerRow = '<tr><th class="sticky-col text-start ps-3">Student Name</th>';

        for (let d = 1; d <= daysInMonth; d++) {
            const dateObj = new Date(year, month - 1, d);
            const dayName = dateObj.toLocaleDateString('en-US', {
                weekday: 'narrow'
            }); // S, M, T, W...
            const isWeekend = (dateObj.getDay() === 0 || dateObj.getDay() === 6);
            const colorClass = isWeekend ? 'text-danger' : '';

            headerRow += `<th class="${colorClass}">${d}<br><small class="text-muted fw-normal">${dayName}</small></th>`;
        }
        headerRow += '</tr>';
        thead.html(headerRow);

        // 2. Build Rows (Students)
        tbody.empty();

        if (students.length === 0) {
            tbody.html('<tr><td colspan="35" class="text-center p-4">No student data.</td></tr>');
            return;
        }

        students.forEach(student => {
            let rowHtml = `<tr>
            <td class="sticky-col ps-3">
                <div class="fw-bold student-name text-truncate">${student.full_name}</div>
                <div class="small text-muted">${student.nim}</div>
            </td>`;

            // Loop Dates
            for (let d = 1; d <= daysInMonth; d++) {
                const dateStr = `${year}-${String(month).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
                const dateObj = new Date(year, month - 1, d);
                const isWeekend = (dateObj.getDay() === 0 || dateObj.getDay() === 6);

                // Get Attendance Data for this Student
                const logData = (matrix[student.id] && matrix[student.id][dateStr]) ? matrix[student.id][dateStr] : null;

                let cellClass = '';
                let cellContent = '';
                let clickData = null;

                if (isWeekend) {
                    // CONDITION 1: Weekend (Holiday)
                    cellClass = 'bg-weekend';
                    cellContent = '<span class="text-muted">-</span>';
                } else if (logData) {
                    clickData = JSON.stringify(logData).replace(/"/g, '&quot;');

                    if (logData.type === 'permission') {
                        // CONDITION 2: Permit
                        cellClass = 'bg-permission';
                        // Get abbreviation for permit (Sick -> S, Permit -> P)
                        const code = logData.title.charAt(0).toUpperCase();
                        cellContent = `<span class="fw-bold">${code}</span>`;
                    } else {
                        // CONDITION 3: Present
                        // Check for Late
                        const isLate = (logData.status_in && logData.status_in.toLowerCase().includes('late'));
                        cellClass = isLate ? 'bg-late' : 'bg-present';

                        const timeIn = logData.in || '-';
                        cellContent = `<span class="fw-bold">${timeIn}</span>`;
                    }
                } else {
                    // CONDITION 4: No Data
                    // Check if the date has passed (Alpha) or is in the future (Empty)
                    if (dateStr < todayStr) {
                        cellClass = 'bg-alpha';
                        cellContent = '<span class="fw-bold">A</span>';
                        // Fake data object for alpha modal
                        clickData = JSON.stringify({
                            type: 'alpha',
                            date: dateStr
                        }).replace(/"/g, '&quot;');
                    } else {
                        cellClass = '';
                        cellContent = '<span class="bg-empty">-</span>';
                    }
                }

                // Render Cell
                const clickAttr = clickData ? `onclick="showDetail('${student.full_name}', '${dateStr}', ${clickData})"` : '';
                rowHtml += `<td class="cell-data ${cellClass}" ${clickAttr}>${cellContent}</td>`;
            }

            rowHtml += '</tr>';
            tbody.append(rowHtml);
        });
    }

    // Show Detail Modal when cell is clicked
    function showDetail(name, date, data) {
        const modal = new bootstrap.Modal(document.getElementById('detailModal'));
        const content = $('#modalContent');
        const title = $('#modalTitle');

        // Date Format
        const dateFmt = new Date(date).toLocaleDateString('en-US', {
            weekday: 'long',
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
        title.html(`<strong>${name}</strong> <br><small class="text-muted">${dateFmt}</small>`);

        if (data.type === 'present') {
            const imgIn = data.photo_in ? `${BASE_URL}/${data.photo_in}` : null;
            const imgOut = data.photo_out ? `${BASE_URL}/${data.photo_out}` : null;

            content.html(`
            <div class="row g-2 text-center">
                <div class="col-6 border-end">
                    <h6 class="text-primary fw-bold">Check In</h6>
                    <div class="display-6 fw-bold mb-2">${data.in || '--:--'}</div>
                    <span class="badge ${data.status_in.includes('Late') ? 'bg-danger' : 'bg-success'} mb-2">${data.status_in}</span>
                    ${imgIn ? `<img src="${imgIn}" class="img-thumbnail d-block mx-auto mt-2" style="max-height:100px;">` : ''}
                </div>
                <div class="col-6">
                    <h6 class="text-warning fw-bold">Check Out</h6>
                    <div class="display-6 fw-bold mb-2">${data.out || '--:--'}</div>
                    <span class="badge bg-secondary mb-2">${data.status_out || '-'}</span>
                    ${imgOut ? `<img src="${imgOut}" class="img-thumbnail d-block mx-auto mt-2" style="max-height:100px;">` : ''}
                </div>
            </div>
        `);
        } else if (data.type === 'permission') {
            content.html(`
            <div class="text-center py-3">
                <div class="badge bg-info text-dark fs-5 mb-3">${data.title}</div>
                <p class="text-muted fst-italic">"${data.reason}"</p>
                <div class="alert alert-light border">Status: <strong>Approved</strong></div>
            </div>
        `);
        } else if (data.type === 'alpha') {
            content.html(`
            <div class="text-center py-4 text-danger">
                <i class="bi bi-x-circle display-1"></i>
                <h4 class="mt-3">Absent (Alpha)</h4>
                <p class="text-muted">The student was not present and did not submit a permission request on this date.</p>
            </div>
        `);
        }

        modal.show();
    }
</script>
<?php $pageScripts = ob_get_clean(); ?>