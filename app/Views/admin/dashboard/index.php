<div class="page-heading mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="mb-1"><?php echo e($title ?? 'Dashboard'); ?></h2>
            <p class="text-muted mb-0">Selamat datang! Berikut ringkasan aktivitas hari ini</p>
        </div>
        <div class="text-end">
            <small class="text-muted d-block">Terakhir diperbarui</small>
            <strong><?php echo date('d M Y, H:i'); ?></strong>
        </div>
    </div>
</div>

<div class="page-content">

    <!-- ROW 1: SUMMARY CARDS (Sumber: v_dashboard_summary_cards & v_dashboard_attendance_today) -->
    <section class="row g-3 mb-4">
        <div class="col-12 col-lg-9">
            <div class="row g-3">
                <!-- Card: Total Users -->
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card border-0 shadow-sm hover-lift">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="stats-icon-modern purple">
                                        <i class="bi bi-people-fill"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <small class="text-muted text-uppercase d-block mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Total User</small>
                                    <!-- Sesuai column: total_users -->
                                    <h3 class="mb-0 fw-bold"><?php echo $stats['summary']->total_users ?? 0; ?></h3>
                                </div>
                            </div>
                            <div class="mt-3">
                                <span class="badge bg-light-purple text-purple">
                                    <i class="bi bi-graph-up-arrow"></i> +12%
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card: Active Students -->
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card border-0 shadow-sm hover-lift">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="stats-icon-modern blue">
                                        <i class="bi bi-mortarboard-fill"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <small class="text-muted text-uppercase d-block mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Mahasiswa</small>
                                    <!-- Sesuai column: active_students -->
                                    <h3 class="mb-0 fw-bold"><?php echo $stats['summary']->active_students ?? 0; ?></h3>
                                </div>
                            </div>
                            <div class="mt-3">
                                <span class="badge bg-light-info text-info">
                                    <i class="bi bi-graph-up-arrow"></i> +8%
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card: Total Projects -->
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card border-0 shadow-sm hover-lift">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="stats-icon-modern green">
                                        <i class="bi bi-briefcase-fill"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <small class="text-muted text-uppercase d-block mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Proyek Lab</small>
                                    <!-- Sesuai column: total_projects (plural di view summary) -->
                                    <h3 class="mb-0 fw-bold"><?php echo $stats['summary']->total_projects ?? 0; ?></h3>
                                </div>
                            </div>
                            <div class="mt-3">
                                <span class="badge bg-light-success text-success">
                                    <i class="bi bi-graph-up-arrow"></i> +15%
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card: Attendance Today -->
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card border-0 shadow-sm hover-lift">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="stats-icon-modern red">
                                        <i class="bi bi-clock-history"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <small class="text-muted text-uppercase d-block mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Hadir Hari Ini</small>
                                    <!-- Sesuai column: total_present -->
                                    <h3 class="mb-0 fw-bold"><?php echo $stats['attendance']->total_present ?? 0; ?></h3>
                                </div>
                            </div>
                            <div class="mt-3">
                                <span class="badge bg-light-danger text-danger">
                                    <i class="bi bi-calendar-check"></i> Hari Ini
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ROW 2: MAIN CHARTS -->
            <div class="row g-3 mt-2">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-transparent border-0 pt-4 pb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="mb-1 fw-bold">Tren Aktivitas Minggu Ini</h4>
                                    <small class="text-muted">Monitoring aktivitas harian pengguna</small>
                                </div>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary active">Minggu</button>
                                    <button type="button" class="btn btn-sm btn-outline-primary">Bulan</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-2">
                            <div id="chart-activity-trend"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ROW 3: SECONDARY CHARTS -->
            <div class="row g-3 mt-2">
                <div class="col-12 col-xl-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-transparent border-0 pt-4 pb-3">
                            <h4 class="mb-1 fw-bold">Proyek per Kategori</h4>
                            <small class="text-muted">Distribusi proyek berdasarkan kategori</small>
                        </div>
                        <div class="card-body pt-2">
                            <div id="chart-project-category"></div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-xl-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-transparent border-0 pt-4 pb-3">
                            <h4 class="mb-1 fw-bold">Pertumbuhan Mahasiswa</h4>
                            <small class="text-muted">Jumlah mahasiswa per tahun masuk</small>
                        </div>
                        <div class="card-body pt-2">
                            <div id="chart-student-growth"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT SIDEBAR: PROFILE & LOGS (Sumber: v_dashboard_latest_logs & mv_dashboard_user_distribution) -->
        <div class="col-12 col-lg-3">
            <!-- Role Distribution Chart -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-transparent border-0 pt-4 pb-3">
                    <h4 class="mb-1 fw-bold">Distribusi User</h4>
                    <small class="text-muted">Berdasarkan role</small>
                </div>
                <div class="card-body pt-2">
                    <div id="chart-role-distribution"></div>
                </div>
            </div>

            <!-- Latest Activity Log -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pt-4 pb-3">
                    <h4 class="mb-1 fw-bold">Aktivitas Terbaru</h4>
                    <small class="text-muted">Real-time updates</small>
                </div>
                <div class="card-content">
                    <div class="activity-timeline">
                        <?php if (!empty($stats['logs'])): ?>
                            <?php foreach ($stats['logs'] as $log): ?>
                                <div class="timeline-item px-4 py-3">
                                    <div class="d-flex gap-3">
                                        <div class="avatar-wrapper">
                                            <div class="avatar avatar-sm bg-light-primary">
                                                <span class="avatar-content">
                                                    <i class="bi bi-person-fill"></i>
                                                </span>
                                            </div>
                                            <div class="timeline-line"></div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <!-- Sesuai columns: user_name, action_type, table_name, created_at -->
                                            <h6 class="mb-1 fw-semibold"><?php echo e($log->user_name ?? 'System'); ?></h6>
                                            <p class="mb-1 text-sm"><?php echo e($log->action_type); ?> on <span class="text-primary"><?php echo e($log->table_name); ?></span></p>
                                            <small class="text-muted">
                                                <i class="bi bi-clock me-1"></i><?php echo date('H:i', strtotime($log->created_at)); ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="px-4 py-5 text-center">
                                <i class="bi bi-inbox text-muted" style="font-size: 2rem;"></i>
                                <p class="text-muted mt-2 mb-0">Belum ada aktivitas</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="px-4 pb-4">
                        <a href="<?php echo base_url('admin/log-activity'); ?>" class="btn btn-block btn-light-primary fw-semibold mt-2">
                            <i class="bi bi-arrow-right-circle me-2"></i>Lihat Semua Log
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Custom Styles -->
<style>
    .hover-lift {
        transition: all 0.3s ease;
    }

    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1) !important;
    }

    .stats-icon-modern {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .stats-icon-modern i {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
    }

    .stats-icon-modern.purple {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .stats-icon-modern.blue {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
    }

    .stats-icon-modern.green {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        color: white;
    }

    .stats-icon-modern.red {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        color: white;
    }

    .bg-light-purple {
        background-color: #f3f0ff !important;
    }

    .text-purple {
        color: #667eea !important;
    }

    .timeline-item {
        position: relative;
        border-bottom: 1px solid #f0f0f0;
    }

    .timeline-item:last-child {
        border-bottom: none;
    }

    .timeline-item:last-child .timeline-line {
        display: none;
    }

    .avatar-wrapper {
        position: relative;
    }

    .timeline-line {
        position: absolute;
        left: 50%;
        top: 40px;
        bottom: -20px;
        width: 2px;
        background: linear-gradient(180deg, #e0e0e0 0%, transparent 100%);
        transform: translateX(-50%);
    }

    .card {
        border-radius: 12px;
        overflow: hidden;
    }

    .text-sm {
        font-size: 0.875rem;
    }

    .activity-timeline {
        max-height: 400px;
        overflow-y: auto;
    }

    .activity-timeline::-webkit-scrollbar {
        width: 4px;
    }

    .activity-timeline::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .activity-timeline::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }

    .activity-timeline::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>

<?php
// MAPPING DATA DARI DATABASE
// Kita tidak lagi memecah array di PHP menggunakan array_column.
// Kita biarkan tetap berupa Array of Objects untuk dikirim ke JS.

$chartActivity = $stats['charts']['activity'] ?? [];
$chartRoles    = $stats['charts']['roles'] ?? [];
$chartProjects = $stats['charts']['projects'] ?? [];
$chartStudents = $stats['charts']['students'] ?? [];
?>

<?php ob_start(); ?>
<!-- Pastikan memuat ApexCharts JS -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    /**
     * HELPER: Raw Data from PHP (Array of Objects)
     * Data format: [{ key: value, key2: value2 }, ...]
     */
    const rawActivity = <?php echo json_encode($chartActivity); ?>;
    const rawRoles    = <?php echo json_encode($chartRoles); ?>;
    const rawProjects = <?php echo json_encode($chartProjects); ?>;
    const rawStudents = <?php echo json_encode($chartStudents); ?>;

    // --------------------------------------------------------
    // 1. CHART ACTIVITY TREND (Area)
    // --------------------------------------------------------
    var optionsActivity = {
        series: [{
            name: 'Total Aktivitas',
            // Mapping langsung di JS ke format {x, y}
            data: rawActivity.map(item => ({
                x: item.day_name,
                y: parseInt(item.total_activity)
            }))
        }],
        chart: {
            height: 320,
            type: 'area',
            toolbar: { show: false },
            fontFamily: 'inherit',
        },
        colors: ['#667eea'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0.1,
            }
        },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        dataLabels: { enabled: false },
        xaxis: {
            // Tidak perlu 'categories' terpisah, ApexCharts membaca dari property 'x' di data
            type: 'category', 
            labels: {
                style: { colors: '#9ca3af', fontSize: '12px' }
            }
        },
        yaxis: {
            labels: {
                style: { colors: '#9ca3af', fontSize: '12px' }
            }
        },
        grid: {
            borderColor: '#f1f1f1',
            strokeDashArray: 4,
        },
        tooltip: { theme: 'light' }
    };
    new ApexCharts(document.querySelector("#chart-activity-trend"), optionsActivity).render();


    // --------------------------------------------------------
    // 2. CHART ROLE DISTRIBUTION (Donut)
    // --------------------------------------------------------
    // Untuk Pie/Donut, ApexCharts membutuhkan array terpisah untuk series dan labels
    // Kita lakukan pemisahan (map) di Client-side (JS), bukan di Server-side (PHP)
    var optionsRoles = {
        series: rawRoles.map(item => parseInt(item.total_user)),
        labels: rawRoles.map(item => item.role_name),
        chart: {
            type: 'donut',
            width: '100%',
            height: 280,
            fontFamily: 'inherit',
        },
        colors: ['#667eea', '#4facfe', '#43e97b', '#fa709a', '#ffd56b'],
        legend: {
            position: 'bottom',
            labels: { colors: '#6b7280' }
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '70%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Total Users',
                            fontSize: '14px',
                            color: '#6b7280'
                        }
                    }
                }
            }
        },
        dataLabels: {
            enabled: true,
            style: { fontSize: '12px', fontWeight: 600 }
        },
        stroke: { width: 0 }
    };
    new ApexCharts(document.querySelector("#chart-role-distribution"), optionsRoles).render();


    // --------------------------------------------------------
    // 3. CHART PROJECT CATEGORY (Bar Horizontal)
    // --------------------------------------------------------
    var optionsProject = {
        series: [{
            name: 'Jumlah Proyek',
            // Mapping ke format {x, y}
            data: rawProjects.map(item => ({
                x: item.category_name,
                y: parseInt(item.total_project)
            }))
        }],
        chart: {
            type: 'bar',
            height: 320,
            toolbar: { show: false },
            fontFamily: 'inherit',
        },
        plotOptions: {
            bar: {
                borderRadius: 8,
                horizontal: true,
                distributed: true,
                dataLabels: { position: 'top' }
            }
        },
        colors: ['#667eea', '#4facfe', '#43e97b', '#fa709a', '#ffd56b', '#c084fc'],
        dataLabels: {
            enabled: true,
            offsetX: 30,
            style: {
                fontSize: '12px',
                colors: ['#fff'],
                fontWeight: 600
            }
        },
        xaxis: {
            // Axis categories otomatis diambil dari property 'x'
            labels: {
                style: { colors: '#9ca3af', fontSize: '12px' }
            }
        },
        yaxis: {
            labels: {
                style: { colors: '#6b7280', fontSize: '12px' }
            }
        },
        grid: { borderColor: '#f1f1f1' },
        legend: { show: false }
    };
    new ApexCharts(document.querySelector("#chart-project-category"), optionsProject).render();


    // --------------------------------------------------------
    // 4. CHART STUDENT GROWTH (Column)
    // --------------------------------------------------------
    var optionsStudent = {
        series: [{
            name: 'Mahasiswa Masuk',
            // Mapping ke format {x, y}
            data: rawStudents.map(item => ({
                x: item.entry_year,
                y: parseInt(item.total_student)
            }))
        }],
        chart: {
            type: 'bar',
            height: 320,
            toolbar: { show: false },
            fontFamily: 'inherit',
        },
        colors: ['#4facfe'],
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'light',
                type: 'vertical',
                shadeIntensity: 0.5,
                gradientToColors: ['#00f2fe'],
                opacityFrom: 0.9,
                opacityTo: 0.8,
            }
        },
        plotOptions: {
            bar: {
                borderRadius: 8,
                horizontal: false,
                columnWidth: '60%',
                dataLabels: { position: 'top' }
            }
        },
        dataLabels: {
            enabled: true,
            offsetY: -20,
            style: {
                fontSize: '12px',
                colors: ['#6b7280'],
                fontWeight: 600
            }
        },
        xaxis: {
            labels: {
                style: { colors: '#9ca3af', fontSize: '12px' }
            }
        },
        yaxis: {
            labels: {
                style: { colors: '#9ca3af', fontSize: '12px' }
            }
        },
        grid: {
            borderColor: '#f1f1f1',
            strokeDashArray: 4,
        },
        tooltip: { theme: 'light' }
    };
    new ApexCharts(document.querySelector("#chart-student-growth"), optionsStudent).render();
</script>
<?php $pageScripts = ob_get_clean(); ?>