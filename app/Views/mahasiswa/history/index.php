<style>
    .calendar-wrapper {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 10px;
        background-color: #f8f9fa;
        padding: 10px;
        border-radius: 12px;
    }

    .calendar-header {
        text-align: center;
        font-weight: bold;
        padding: 10px 0;
        color: #6c757d;
        text-transform: uppercase;
        font-size: 0.85rem;
    }

    .calendar-day {
        background: #fff;
        border-radius: 8px;
        min-height: 120px;
        padding: 8px;
        border: 1px solid #e9ecef;
        position: relative;
        transition: transform 0.2s, box-shadow 0.2s;
        display: flex;
        flex-direction: column;
        cursor: pointer;
    }

    .calendar-day:hover:not(.disabled) {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        border-color: #435ebe;
        z-index: 2;
    }

    .day-number {
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 8px;
        color: #343a40;
    }

    .calendar-day.disabled {
        background-color: #f1f3f5;
        color: #adb5bd;
        border-color: transparent;
        cursor: default;
    }

    .calendar-day.disabled .day-number {
        color: #adb5bd;
    }

    .event-content {
        flex-grow: 1;
        font-size: 0.75rem;
    }

    .time-badge {
        display: block;
        margin-bottom: 4px;
        padding: 4px 6px;
        border-radius: 4px;
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        color: #495057;
    }

    @media (max-width: 768px) {
        .calendar-wrapper {
            grid-template-columns: repeat(1, 1fr);
            gap: 8px;
        }

        .calendar-header {
            display: none;
        }

        .calendar-day {
            min-height: auto;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }

        .day-number {
            margin-bottom: 0;
            width: 40px;
        }

        .event-content {
            text-align: right;
        }
    }
</style>

<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h3 class="page-title"><i class="bi bi-calendar3 me-2"></i> <?php echo e($title); ?></h3>
            <p class="text-subtitle text-muted">Monthly activity journal and attendance status.</p>
        </div>

        <div class="d-flex gap-2 mt-2 mt-md-0">
            <select id="filterMonth" class="form-select form-select-sm" style="width: 120px;"></select>
            <select id="filterYear" class="form-select form-select-sm" style="width: 100px;"></select>
            <button id="btnFilter" class="btn btn-sm btn-primary"><i class="bi bi-filter"></i></button>
        </div>
    </div>
</div>

<div class="page-content">
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="d-none d-md-grid" style="grid-template-columns: repeat(7, 1fr); gap: 10px; margin-bottom: 5px;">
                <div class="calendar-header text-danger">Sunday</div>
                <div class="calendar-header">Monday</div>
                <div class="calendar-header">Tuesday</div>
                <div class="calendar-header">Wednesday</div>
                <div class="calendar-header">Thursday</div>
                <div class="calendar-header">Friday</div>
                <div class="calendar-header text-danger">Saturday</div>
            </div>

            <div id="calendarGrid" class="calendar-wrapper">
                <div class="text-center w-100 py-5 text-muted col-span-7">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2">Loading data...</p>
                </div>
            </div>

            <div class="mt-3 d-flex gap-3 justify-content-end small text-muted">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-success me-1" style="width:10px;height:10px;"></div> Present
                </div>
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-warning me-1" style="width:10px;height:10px;"></div> Late/Early Checkout
                </div>
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-info me-1" style="width:10px;height:10px;"></div> Permit/Sick
                </div>
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-secondary me-1" style="width:10px;height:10px;"></div> Holiday
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="detailModalLabel">Activity Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h4 class="fw-bold mb-0 text-primary" id="modalDateDisplay">-</h4>
                    <span class="badge bg-light text-secondary mt-2" id="modalTypeBadge">-</span>
                </div>

                <div id="contentPresent" class="d-none">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 border rounded bg-light h-100">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="fw-bold mb-0 text-primary"><i class="bi bi-box-arrow-in-right"></i> Check In</h6>
                                    <span class="badge bg-primary" id="modalTimeIn">-</span>
                                </div>
                                <p class="small text-muted mb-2" id="modalStatusIn">-</p>
                                <div class="ratio ratio-4x3 bg-secondary rounded overflow-hidden">
                                    <img id="modalImgIn" src="" class="object-fit-cover" alt="Check In Photo">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 border rounded bg-light h-100">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="fw-bold mb-0 text-warning"><i class="bi bi-box-arrow-right"></i> Check Out</h6>
                                    <span class="badge bg-warning text-dark" id="modalTimeOut">-</span>
                                </div>
                                <p class="small text-muted mb-2" id="modalStatusOut">-</p>
                                <div class="ratio ratio-4x3 bg-secondary rounded overflow-hidden">
                                    <img id="modalImgOut" src="" class="object-fit-cover" alt="Check Out Photo">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="contentPermission" class="d-none text-center py-4">
                    <div class="icon-box mb-3 text-info">
                        <i class="bi bi-envelope-paper" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="fw-bold" id="permTitle">-</h5>
                    <p class="text-muted" id="permReason">-</p>
                    <div class="mt-3">
                        <span class="badge bg-success" id="permStatus">Approved</span>
                    </div>
                </div>

                <div id="contentEmpty" class="d-none text-center py-5 text-muted">
                    <i class="bi bi-calendar-x mb-2" style="font-size: 2rem;"></i>
                    <p>No activity data on this date.</p>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php ob_start(); ?>
<script>
    const MONTH_NAMES = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    const BASE_URL = "<?= base_url() ?>";

    const placeholderImg = "data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22300%22%20height%3D%22200%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20300%20200%22%20preserveAspectRatio%3D%22none%22%3E%3Cdefs%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E%23holder_1%20text%20%7B%20fill%3A%23AAAAAA%3Bfont-weight%3Abold%3Bfont-family%3AArial%2C%20Helvetica%2C%20Open%20Sans%2C%20sans-serif%2C%20monospace%3Bfont-size%3A18pt%20%7D%20%3C%2Fstyle%3E%3C%2Fdefs%3E%3Cg%20id%3D%22holder_1%22%3E%3Crect%20width%3D%22300%22%20height%3D%22200%22%20fill%3D%22%23EEEEEE%22%3E%3C%2Frect%3E%3Cg%3E%3Ctext%20x%3D%2290%22%20y%3D%22110%22%3ENo%20Photo%3C%2Ftext%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E";

    let currentMonthEvents = {};

    $(document).ready(function() {
        initFilters();
        loadCalendarData();

        $('#btnFilter').click(function() {
            loadCalendarData();
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

    function loadCalendarData() {
        const year = $('#filterYear').val();
        const month = $('#filterMonth').val();
        const grid = $('#calendarGrid');

        grid.html('<div class="col-12 text-center py-5"><div class="spinner-border text-primary"></div></div>');

        $.ajax({
            url: '<?= base_url("mahasiswa/log/presence/data") ?>',
            method: 'GET',
            data: {
                year: year,
                month: month
            },
            success: function(response) {
                if (response.success) {
                    currentMonthEvents = response.data;
                    renderCalendar(year, month);
                } else {
                    grid.html('<div class="col-12 text-center text-muted py-5">No data available.</div>');
                }
            },
            error: function() {
                grid.html('<div class="col-12 text-center text-danger py-5">Failed to load data.</div>');
            }
        });
    }

    function renderCalendar(year, month) {
        const grid = $('#calendarGrid');
        grid.empty();

        const firstDay = new Date(year, month - 1, 1).getDay();
        const daysInMonth = new Date(year, month, 0).getDate();

        if (window.innerWidth >= 768) {
            for (let i = 0; i < firstDay; i++) {
                grid.append('<div class="calendar-day disabled border-0 bg-transparent d-none d-md-block"></div>');
            }
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const dateStr = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const dateObj = new Date(year, month - 1, day);
            const dayOfWeek = dateObj.getDay();
            const isWeekend = (dayOfWeek === 0 || dayOfWeek === 6);

            let eventData = currentMonthEvents[dateStr] || null;
            let contentHtml = '';
            let cardClass = '';
            let clickAction = '';

            const dateDisplay = dateObj.toLocaleDateString('id-ID', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });

            if (isWeekend) {
                cardClass = 'disabled';
                contentHtml = '<div class="text-center mt-2"><small class="badge bg-secondary text-white rounded-pill">Libur</small></div>';
            } else {
                if (eventData) {
                    clickAction = `onclick="openDetailModal('${dateStr}', '${dateDisplay}')"`;

                    if (eventData.type === 'permission') {
                        contentHtml = `
                        <div class="event-content mt-2">
                            <div class="alert alert-info p-1 mb-0 border-0 small">
                                <i class="bi bi-file-earmark-text-fill"></i> <strong>${eventData.title}</strong>
                            </div>
                        </div>
                    `;
                    } else {
                        const inTime = eventData.check_in || '--:--';
                        const outTime = eventData.check_out || '--:--';

                        let inClass = eventData.status_in && eventData.status_in.includes('Late') ? 'text-danger border-danger' : 'text-primary border-primary';
                        let outClass = eventData.status_out && eventData.status_out.includes('Early') ? 'text-info border-info' : 'text-success border-success';

                        contentHtml = `
                        <div class="event-content mt-1 w-100">
                            <div class="time-badge bg-white ${inClass} d-flex justify-content-between align-items-center">
                                <span>IN</span> <strong>${inTime}</strong>
                            </div>
                            <div class="time-badge bg-white ${outClass} d-flex justify-content-between align-items-center">
                                <span>OUT</span> <strong>${outTime}</strong>
                            </div>
                        </div>
                    `;
                    }
                } else {
                    clickAction = `onclick="openDetailModal('${dateStr}', '${dateDisplay}')"`;
                    contentHtml = `
                    <div class="event-content d-flex align-items-center justify-content-center text-muted h-100">
                        <small style="font-size:0.7rem;">- Alpha -</small>
                    </div>
                `;
                }
            }

            const dayNameMobile = dateObj.toLocaleDateString('en-US', {
                weekday: 'short'
            });

            grid.append(`
            <div class="calendar-day ${cardClass}" ${clickAction}>
                <div class="d-flex justify-content-between w-100 align-items-start">
                    <span class="day-number">${day}</span>
                    <span class="d-md-none badge bg-light text-dark border">${dayNameMobile}</span>
                </div>
                ${contentHtml}
            </div>
        `);
        }
    }

    function openDetailModal(dateKey, dateDisplay) {
        const eventData = currentMonthEvents[dateKey];

        $('#modalDateDisplay').text(dateDisplay);

        $('#contentPresent').addClass('d-none');
        $('#contentPermission').addClass('d-none');
        $('#contentEmpty').addClass('d-none');

        const modal = new bootstrap.Modal(document.getElementById('detailModal'));

        $('#modalImgIn').attr('src', placeholderImg);
        $('#modalImgOut').attr('src', placeholderImg);

        if (!eventData) {
            $('#contentEmpty').removeClass('d-none');
            $('#modalTypeBadge').text('No Data').attr('class', 'badge bg-light-secondary mt-2');
        } else if (eventData.type === 'present') {
            $('#contentPresent').removeClass('d-none');
            $('#modalTypeBadge').text('Present').attr('class', 'badge bg-success mt-2');

            $('#modalTimeIn').text(eventData.check_in || '--:--');
            $('#modalStatusIn').text(eventData.status_in || 'Not yet attended');

            const imgInPath = eventData.photo_in ? (BASE_URL + eventData.photo_in) : placeholderImg;
            $('#modalImgIn')
                .off('error')
                .one('error', function() {
                    $(this).attr('src', placeholderImg);
                })
                .attr('src', imgInPath);

            $('#modalTimeOut').text(eventData.check_out || '--:--');
            $('#modalStatusOut').text(eventData.status_out || 'Not yet attended');

            const imgOutPath = eventData.photo_out ? (BASE_URL + eventData.photo_out) : placeholderImg;
            $('#modalImgOut')
                .off('error')
                .one('error', function() {
                    $(this).attr('src', placeholderImg);
                })
                .attr('src', imgOutPath);

        } else if (eventData.type === 'permission') {
            $('#contentPermission').removeClass('d-none');
            $('#modalTypeBadge').text('Permission').attr('class', 'badge bg-info mt-2');

            $('#permTitle').text(eventData.title);
            $('#permReason').text(eventData.reason);
            $('#permStatus').text(eventData.status || 'Approved');
        }

        modal.show();
    }
</script>
<?php $pageScripts = ob_get_clean(); ?>