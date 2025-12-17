<style>
    .card-hover-scale {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .card-hover-scale:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        cursor: pointer;
    }
    .bg-gradient-primary {
        background: linear-gradient(45deg, #435ebe, #6c84e3);
    }
    .bg-gradient-info {
        background: linear-gradient(45deg, #0dcaf0, #5ee0fa);
    }
    .icon-shape {
        width: 48px;
        height: 48px;
        background-color: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .status-dot {
        height: 10px;
        width: 10px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 5px;
    }
    .bi,
    [class^="bi-"],
    [class*=" bi-"] {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        line-height: 1 !important;
        vertical-align: middle !important;
    }

    .bi::before,
    [class^="bi-"]::before,
    [class*=" bi-"]::before {
        display: inline-block !important;
        margin: 0 !important;
    }
</style>

<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0 text-dark fw-bold"><?= $greeting ?>, <?= explode(' ', $user->full_name)[0] ?>! 👋</h3>
            <p class="text-muted mt-1">Welcome back to the laboratory management system.</p>
        </div>
        <div class="d-none d-md-block text-end">
            <h6 class="mb-0 text-muted"><?= date('l, d F Y') ?></h6>
        </div>
    </div>
</div>

<div class="page-content">
    <div class="row g-4">
        
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card card-hover-scale h-100 border-0 shadow-sm bg-gradient-primary text-white" 
                 onclick="window.location.href='<?= base_url('mahasiswa/presence') ?>'">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-white-50 text-uppercase mb-2">Daily Attendance</h6>
                            <h4 class="mb-0 text-white fw-bold"><?= $attendanceStatus['message'] ?></h4>
                        </div>
                        <div class="icon-shape text-white">
                            <i class="bi <?= $attendanceStatus['icon'] ?>"></i>
                        </div>
                    </div>
                    <div class="mt-4 pt-2">
                        <button class="btn btn-sm btn-light text-primary fw-bold rounded-pill px-3">
                            Open Attendance <i class="bi bi-arrow-right ms-1"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
            <div class="card card-hover-scale h-100 border-0 shadow-sm"
                 onclick="window.location.href='<?= base_url('mahasiswa/permission') ?>'">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted text-uppercase mb-2">Permission</h6>
                            <h4 class="mb-0 fw-bold text-dark">Request Permit/Sick</h4>
                        </div>
                        <div class="icon-shape bg-light-info text-info rounded-3">
                            <i class="bi bi-file-earmark-text-fill"></i>
                        </div>
                    </div>
                    <div class="mt-4 pt-2">
                        <span class="text-muted small">
                            Need a break or have an urgent matter?
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-12 col-lg-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pt-4 pb-0">
                    <h6 class="text-muted text-uppercase mb-0">Latest Permission Status</h6>
                </div>
                <div class="card-body p-4 d-flex flex-column justify-content-center">
                    <?php if ($latestPermission): ?>
                        <?php 
                            $badgeClass = 'bg-warning';
                            $statusText = 'Pending';
                            if ($latestPermission->status == 'approved') {
                                $badgeClass = 'bg-success';
                                $statusText = 'Approved';
                            } elseif ($latestPermission->status == 'rejected') {
                                $badgeClass = 'bg-danger';
                                $statusText = 'Rejected';
                            }
                        ?>
                        <div class="d-flex align-items-center mb-3">
                            <span class="badge <?= $badgeClass ?> p-2 px-3 rounded-pill">
                                <?= $statusText ?>
                            </span>
                            <span class="ms-auto text-muted small">
                                <?= date('d M Y', strtotime($latestPermission->created_at)) ?>
                            </span>
                        </div>
                        <h5 class="fw-bold mb-1"><?= ucfirst($latestPermission->permission_type) ?></h5>
                        <p class="text-muted small mb-0 text-truncate">
                            <?= $latestPermission->reason ?>
                        </p>
                    <?php else: ?>
                        <div class="text-center text-muted py-2">
                            <i class="bi bi-emoji-smile mb-2 me-2 d-block" style="font-size: 1.5rem;"></i>
                            No permission request history yet.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pt-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Recent Activity</h5>
                    <a href="<?= base_url('mahasiswa/history') ?>" class="btn btn-sm btn-light-secondary">View All</a>
                </div>
                <div class="card-body px-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4">Time</th>
                                    <th>Attendance Type</th>
                                    <th>Status</th>
                                    <th>Location</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($recentLogs)): ?>
                                    <?php foreach ($recentLogs as $log): ?>
                                        <tr>
                                            <td class="px-4">
                                                <div class="d-flex flex-column">
                                                    <span class="fw-bold text-dark">
                                                        <?= date('H:i', strtotime($log->log_time)) ?>
                                                    </span>
                                                    <small class="text-muted">
                                                        <?= date('d M Y', strtotime($log->log_time)) ?>
                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if ($log->log_type == 'check_in'): ?>
                                                    <span class="text-primary fw-bold">
                                                        <i class="bi bi-arrow-right-circle me-1"></i> Check In
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-warning fw-bold">
                                                        <i class="bi bi-arrow-left-circle me-1"></i> Check Out
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php
                                                    $statusColor = 'success';
                                                    if (stripos($log->status, 'late') !== false) $statusColor = 'danger';
                                                    elseif (stripos($log->status, 'early') !== false) $statusColor = 'info';
                                                ?>
                                                <span class="badge bg-light-<?= $statusColor ?> text-<?= $statusColor ?> border border-<?= $statusColor ?>">
                                                    <?= $log->status ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="https://maps.google.com/?q=<?= $log->latitude ?>,<?= $log->longitude ?>" 
                                                   target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill">
                                                    <i class="bi bi-geo-alt-fill me-1"></i> Maps
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <i class="bi bi-clock-history d-block mb-2" style="font-size: 2rem;"></i>
                                            No attendance activity recorded yet.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>