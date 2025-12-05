<?php
$team = $data['team'] ?? null;
$expertises     = $team['expertise'] ?? [];
$educations     = $team['education'] ?? [];
$certifications = $team['certifications'] ?? [];
$courses        = $team['courses_taught'] ?? [];
$socials        = $team['social_medias'] ?? [];
?>

<?php ob_start(); ?>
<style>
    .profile-img-container {
        position: relative;
        overflow: hidden;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .profile-img {
        width: 100%;
        height: auto;
        object-fit: cover;
        transition: transform 0.3s;
        cursor: pointer;
    }
    .profile-img:hover {
        transform: scale(1.03);
    }
    .badge-expertise {
        font-size: 0.9em;
        padding: 8px 12px;
        margin-right: 5px;
        margin-bottom: 5px;
        border-radius: 20px;
    }
    .timeline-item {
        border-left: 2px solid #e9ecef;
        padding-left: 20px;
        padding-bottom: 20px;
        position: relative;
    }
    .timeline-item::before {
        content: "";
        width: 12px;
        height: 12px;
        background: #435ebe;
        border-radius: 50%;
        position: absolute;
        left: -7px;
        top: 5px;
    }
    .info-label {
        font-weight: 600;
        color: #607080;
    }
</style>
<?php 
$pageStyle = ob_get_clean(); 
echo $pageStyle; 
?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Team Detail</h3>
                <p class="text-subtitle text-muted">View team member profile.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/team') ?>">Team</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <?php if ($team): ?>
    <section class="row">
        <div class="col-12 col-lg-4">
            <div class="card border">
                <div class="card-body text-center pt-4">
                    <div class="profile-img-container mb-3 mx-auto" style="max-width: 250px;">
                        <?php if (!empty($team['image_name'])): ?>
                            <img src="<?= base_url('uploads/team/' . $team['image_name']) ?>" 
                                 alt="<?= htmlspecialchars($team['full_name'] ?? '') ?>" 
                                 class="profile-img"
                                 data-bs-toggle="modal" data-bs-target="#imageModal">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/300x300?text=No+Image" class="profile-img" alt="No Image">
                        <?php endif; ?>
                    </div>
                    
                    <h4 class="mb-1"><?= htmlspecialchars($team['full_name'] ?? '-') ?></h4>
                    <p class="text-muted mb-3"><?= htmlspecialchars($team['academic_position'] ?? '-') ?></p>

                    <div class="d-flex justify-content-center flex-wrap gap-2 mb-4">
                         <?php if (!empty($socials) && (is_array($socials) || is_object($socials))): ?>
                            <?php foreach($socials as $soc): ?>
                                <?php 
                                    $link = is_array($soc) ? ($soc['link_sosmed'] ?? '#') : ($soc->link_sosmed ?? '#');
                                    $name = is_array($soc) ? ($soc['name'] ?? '') : ($soc->name ?? '');
                                    $icon = is_array($soc) ? ($soc['icon_name'] ?? 'bi bi-link') : ($soc->icon_name ?? 'bi bi-link');
                                ?>
                                <a href="<?= htmlspecialchars($link) ?>" target="_blank" class="btn btn-outline-primary btn-sm rounded-circle" title="<?= htmlspecialchars($name) ?>">
                                    <i class="<?= htmlspecialchars($icon) ?>"></i>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <small class="text-muted">No social media</small>
                        <?php endif; ?>
                    </div>

                    <div class="text-start mt-4">
                        <div class="mb-3">
                            <label class="info-label d-block"><i class="bi bi-envelope me-2"></i>Email</label>
                            <span><?= htmlspecialchars($team['email'] ?? '-') ?></span>
                        </div>
                        <div class="mb-3">
                            <label class="info-label d-block"><i class="bi bi-geo-alt me-2"></i>Office Address</label>
                            <span><?= htmlspecialchars($team['office_address'] ?? '-') ?></span>
                        </div>
                        <div class="mb-3">
                            <label class="info-label d-block"><i class="bi bi-card-heading me-2"></i>NIP</label>
                            <span><?= htmlspecialchars($team['nip'] ?? '-') ?></span>
                        </div>
                        <div class="mb-3">
                            <label class="info-label d-block"><i class="bi bi-card-text me-2"></i>NIDN</label>
                            <span><?= htmlspecialchars($team['nidn'] ?? '-') ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-8">
            <div class="card border mb-3">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0"><i class="bi bi-person-lines-fill me-2"></i>Positions</h5>
                </div>
                <div class="card-body mt-3">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="info-label">Lab Position</label>
                            <p class="fw-bold"><?= htmlspecialchars($team['lab_position'] ?? '-') ?></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="info-label">Study Program</label>
                            <p class="fw-bold"><?= htmlspecialchars($team['study_program'] ?? '-') ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="edu-tab" data-bs-toggle="tab" href="#edu" role="tab">Education</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="exp-tab" data-bs-toggle="tab" href="#exp" role="tab">Expertise</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="cert-tab" data-bs-toggle="tab" href="#cert" role="tab">Certifications</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="course-tab" data-bs-toggle="tab" href="#course" role="tab">Courses</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="myTabContent">
                        
                        <div class="tab-pane fade show active" id="edu" role="tabpanel">
                            <div class="mt-3">
                                <?php foreach (['S3', 'S2', 'S1'] as $level): ?>
                                    <?php if (!empty($educations[$level]['university'])): ?>
                                        <div class="timeline-item">
                                            <h6 class="text-primary mb-1"><?= $level ?> - <?= htmlspecialchars($educations[$level]['university']) ?></h6>
                                            <span class="text-muted"><?= htmlspecialchars($educations[$level]['major'] ?? '') ?></span>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                
                                <?php 
                                    $s1Univ = $educations['S1']['university'] ?? '';
                                    $s2Univ = $educations['S2']['university'] ?? '';
                                    $s3Univ = $educations['S3']['university'] ?? '';
                                    
                                    if (empty($s1Univ) && empty($s2Univ) && empty($s3Univ)): 
                                ?>
                                    <p class="text-muted">No education data.</p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="exp" role="tabpanel">
                            <div class="mt-3">
                                <?php if (!empty($expertises) && is_array($expertises)): ?>
                                    <?php foreach ($expertises as $exp): ?>
                                        <span class="badge bg-info badge-expertise">
                                            <?= htmlspecialchars($exp) ?>
                                        </span>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-muted">No expertise listed.</p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="cert" role="tabpanel">
                            <div class="table-responsive mt-3">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Name</th>
                                            <th>Publisher</th>
                                            <th>Year</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($certifications) && is_array($certifications)): ?>
                                            <?php foreach ($certifications as $cert): ?>
                                                <tr>
                                                    <td class="fw-bold"><?= htmlspecialchars($cert['name'] ?? '-') ?></td>
                                                    <td><?= htmlspecialchars($cert['publisher'] ?? '-') ?></td>
                                                    <td><?= htmlspecialchars($cert['year'] ?? '-') ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr><td colspan="3" class="text-center text-muted">No certifications found.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="course" role="tabpanel">
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <h6 class="text-primary border-bottom pb-2">Semester Ganjil</h6>
                                    <ul class="list-group list-group-flush">
                                        <?php if (!empty($courses['ganjil']) && is_array($courses['ganjil'])): ?>
                                            <?php foreach ($courses['ganjil'] as $c): ?>
                                                <li class="list-group-item px-0"><i class="bi bi-check2-circle text-success me-2"></i><?= htmlspecialchars($c) ?></li>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <li class="list-group-item px-0 text-muted">Empty</li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-primary border-bottom pb-2">Semester Genap</h6>
                                    <ul class="list-group list-group-flush">
                                        <?php if (!empty($courses['genap']) && is_array($courses['genap'])): ?>
                                            <?php foreach ($courses['genap'] as $c): ?>
                                                <li class="list-group-item px-0"><i class="bi bi-check2-circle text-success me-2"></i><?= htmlspecialchars($c) ?></li>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <li class="list-group-item px-0 text-muted">Empty</li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-end mt-3">
                <a href="<?= base_url('admin/team') ?>" class="btn btn-secondary me-2">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
                <a href="<?= base_url('admin/team/' . $team['id'] . '/edit') ?>" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Edit Profile
                </a>
            </div>

        </div>
    </section>
    <?php else: ?>
        <div class="alert alert-danger">Team member not found.</div>
        <a href="<?= base_url('admin/team') ?>" class="btn btn-secondary">Back</a>
    <?php endif; ?>

    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-body text-center p-0">
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
                    <?php if (!empty($team['image_name'])): ?>
                        <img src="<?= base_url('uploads/team/' . $team['image_name']) ?>" class="img-fluid" alt="Preview">
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php ob_start(); ?>
<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
<?php $pageScripts = ob_get_clean(); ?>