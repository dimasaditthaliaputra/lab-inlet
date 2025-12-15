<?php
$p = $permission ?? null;

function badgeStatus($status)
{
    $status = $status ?? 'pending';
    if ($status === 'approved') return '<span class="badge bg-success"><i class="fas fa-check me-1"></i> Approved</span>';
    if ($status === 'rejected') return '<span class="badge bg-danger"><i class="fas fa-times me-1"></i> Rejected</span>';
    return '<span class="badge bg-secondary"><i class="fas fa-hourglass-half me-1"></i> Pending</span>';
}

function isImageExt($ext)
{
    $ext = strtolower($ext);
    return in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
}

function isPdfExt($ext)
{
    return strtolower($ext) === 'pdf';
}

$attachment = $p->attachment ?? null;
$attUrl = $attachment ? base_url('uploads/attendance_permissions/' . $attachment) : null;
$attExt = $attachment ? strtolower(pathinfo($attachment, PATHINFO_EXTENSION)) : null;
?>

<?php ob_start(); ?>
<style>
    .info-card { box-shadow: 0 2px 4px rgba(0,0,0,0.08); }
    .doc-preview {
        border: 1px solid #e9ecef;
        border-radius: 10px;
        overflow: hidden;
        background: #fff;
    }
    .doc-preview iframe {
        width: 100%;
        height: 520px;
        border: 0;
        display: block;
    }
    .img-clickable {
        cursor: pointer;
        transition: transform .2s ease;
        border-radius: 10px;
        border: 2px solid #f2f2f2;
        max-height: 420px;
        width: auto;
    }
    .img-clickable:hover { transform: scale(1.01); }
    .meta-table td { padding: 6px 4px; }
</style>
<?php
$pageStyle = ob_get_clean();
echo $pageStyle;
?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-7 order-md-1 order-last">
                <h3>Attendance Permission Detail</h3>
                <p class="text-subtitle text-muted">Lihat detail pengajuan dan lakukan approve/reject.</p>
            </div>
            <div class="col-12 col-md-5 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/attendance-permissions') ?>">Attendance Permissions</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="row">
        <div class="col-12">
            <div class="card border info-card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Permission Information</h4>
                        <div><?= $p ? badgeStatus($p->status ?? 'pending') : '' ?></div>
                    </div>
                </div>

                <div class="card-body">
                    <?php if (!$p): ?>
                        <div class="alert alert-warning"><i class="fas fa-exclamation-triangle me-1"></i> Data tidak ditemukan.</div>
                    <?php else: ?>

                        <div class="row g-3">
                            <!-- LEFT: Identitas & detail izin -->
                            <div class="col-12 col-lg-6">
                                <div class="card bg-light info-card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">
                                            <i class="fas fa-user text-primary me-1"></i> Mahasiswa
                                        </h5>

                                        <table class="table table-borderless table-sm meta-table mb-0">
                                            <tr>
                                                <td width="35%" class="fw-bold">Nama</td>
                                                <td>: <?= htmlspecialchars($p->mahasiswa_name ?? '-') ?></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">NIM</td>
                                                <td>: <?= htmlspecialchars($p->mahasiswa_nim ?? '-') ?></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Jenis Izin</td>
                                                <td>: <?= htmlspecialchars($p->permission_type ?? '-') ?></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Tanggal Mulai</td>
                                                <td>: <?= $p->start_date ? date('d F Y', strtotime($p->start_date)) : '-' ?></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Tanggal Berakhir</td>
                                                <td>: <?= $p->end_date ? date('d F Y', strtotime($p->end_date)) : '-' ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- RIGHT: Metadata approval -->
                            <div class="col-12 col-lg-6">
                                <div class="card info-card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">
                                            <i class="fas fa-info-circle text-info me-1"></i> Metadata
                                        </h5>

                                        <table class="table table-borderless table-sm meta-table mb-0">
                                            <tr>
                                                <td width="40%" class="fw-bold">Status</td>
                                                <td>: <?= badgeStatus($p->status ?? 'pending') ?></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Approved/Rejected By</td>
                                                <td>: <?= htmlspecialchars($p->approved_by_name ?? '-') ?></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Created At</td>
                                                <td>: <?= $p->created_at ? date('d M Y H:i', strtotime($p->created_at)) : '-' ?></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Updated At</td>
                                                <td>: <?= $p->updated_at ? date('d M Y H:i', strtotime($p->updated_at)) : '-' ?></td>
                                            </tr>
                                        </table>

                                        <?php if (($p->status ?? 'pending') === 'rejected' && !empty($p->rejection_note)): ?>
                                            <div class="alert alert-danger mt-3 mb-0">
                                                <div class="fw-bold mb-1"><i class="fas fa-sticky-note me-1"></i> Rejection Note</div>
                                                <?= nl2br(htmlspecialchars($p->rejection_note)) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Reason -->
                            <div class="col-12">
                                <div class="card bg-light info-card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-2">
                                            <i class="fas fa-file-alt text-primary me-1"></i> Reason
                                        </h5>
                                        <div><?= nl2br(htmlspecialchars($p->reason ?? '-')) ?></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Dokumen Pendukung (Preview) -->
                            <div class="col-12">
                                <div class="card info-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h5 class="card-title mb-0">
                                                <i class="fas fa-paperclip text-warning me-1"></i> Dokumen Pendukung
                                            </h5>
                                            <?php if ($attUrl): ?>
                                                <a href="<?= $attUrl ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-external-link-alt me-1"></i> Open / Download
                                                </a>
                                            <?php endif; ?>
                                        </div>

                                        <?php if (!$attUrl): ?>
                                            <div class="alert alert-light mb-0">
                                                <i class="fas fa-folder-open me-1"></i> Tidak ada dokumen pendukung.
                                            </div>
                                        <?php else: ?>
                                            <div class="text-muted mb-2"><?= htmlspecialchars($attachment) ?></div>

                                            <?php if (isImageExt($attExt)): ?>
                                                <div class="text-center">
                                                    <img
                                                        src="<?= $attUrl ?>"
                                                        class="img-fluid img-clickable"
                                                        alt="Dokumen Pendukung"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#imageModal"
                                                        id="docImage"
                                                    >
                                                    <div class="small text-muted mt-2">Klik gambar untuk melihat ukuran penuh.</div>
                                                </div>

                                            <?php elseif (isPdfExt($attExt)): ?>
                                                <div class="doc-preview">
                                                    <iframe src="<?= $attUrl ?>#toolbar=1&navpanes=0&scrollbar=1"></iframe>
                                                </div>

                                            <?php else: ?>
                                                <div class="alert alert-info mb-0">
                                                    Preview tidak tersedia untuk tipe file <b><?= htmlspecialchars($attExt) ?></b>.
                                                    Silakan klik <b>Open / Download</b>.
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="<?= base_url('admin/attendance-permissions') ?>" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i> Back to List
                                    </a>

                                    <?php if (($p->status ?? 'pending') === 'pending'): ?>
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-success" id="btnApprove" data-id="<?= $p->id ?>">
                                                <i class="fas fa-check me-1"></i> Approve
                                            </button>
                                            <button type="button" class="btn btn-danger" id="btnReject" data-id="<?= $p->id ?>">
                                                <i class="fas fa-times me-1"></i> Reject
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Image Preview -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Dokumen Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <?php if ($attUrl && isImageExt($attExt)): ?>
                        <img src="<?= $attUrl ?>" class="img-fluid" alt="Preview">
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php ob_start(); ?>
<script>
    var audio = new Audio("<?= base_url('assets/audio/success.wav'); ?>");

    function ajaxPut(url, data, onSuccess) {
        $.ajax({
            url: url,
            type: 'PUT',
            dataType: 'JSON',
            data: data || {},
            success: function(res) {
                if (res && res.success) {
                    audio.play();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: res.message || 'Berhasil',
                        showConfirmButton: false,
                        timer: 1300
                    }).then(() => onSuccess && onSuccess());
                } else {
                    Swal.fire({ icon: 'error', title: 'Oops...', text: (res && res.message) ? res.message : 'Gagal' });
                }
            },
            error: function(xhr) {
                let msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Terjadi kesalahan.';
                Swal.fire({ icon: 'error', title: 'Oops...', text: msg });
            }
        });
    }

    $(document).ready(function () {
        $('#btnApprove').click(function () {
            let id = $(this).data('id');
            let url = '<?= base_url('admin/attendance-permissions'); ?>/' + id + '/approve';

            Swal.fire({
                title: "Approve permission?",
                text: "Status akan menjadi APPROVED.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, approve",
                cancelButtonText: "Cancel",
            }).then((result) => {
                if (!result.isConfirmed) return;
                ajaxPut(url, {}, () => location.reload());
            });
        });

        $('#btnReject').click(function () {
            let id = $(this).data('id');
            let url = '<?= base_url('admin/attendance-permissions'); ?>/' + id + '/reject';

            Swal.fire({
                title: "Reject permission?",
                input: "textarea",
                inputLabel: "Rejection note (wajib)",
                inputPlaceholder: "Tulis alasan penolakan...",
                showCancelButton: true,
                confirmButtonText: "Reject",
                cancelButtonText: "Cancel",
                preConfirm: (value) => {
                    if (!value || value.trim() === '') Swal.showValidationMessage('Rejection note wajib diisi');
                    return value;
                }
            }).then((result) => {
                if (!result.isConfirmed) return;
                ajaxPut(url, { rejection_note: result.value }, () => location.reload());
            });
        });
    });
</script>
<?php $pageScripts = ob_get_clean(); ?>
