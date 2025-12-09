<?php
$team = isset($data['team']) ? (object) $data['team'] : null;
$isEdit = !empty($team);
$formAction = $isEdit ? base_url('admin/team/' . $team->id) : base_url('admin/team');

// Pre-process data for JavaScript injection
if ($isEdit) {
    $expertises = $team->expertise;
    if (is_string($expertises)) {
        $expertises = json_decode($expertises, true);
    }
    $expertises = is_array($expertises) ? $expertises : [];

    $certifications = $team->certifications;
    if (is_string($certifications)) {
        $certifications = json_decode($certifications, true);
    }
    $certifications = is_array($certifications) ? $certifications : [];

    $courses = $team->courses_taught;
    if (is_string($courses)) {
        $courses = json_decode($courses, true);
    }
    $courses = is_array($courses) ? $courses : ['ganjil' => [], 'genap' => []];
    $courses_ganjil = is_array($courses['ganjil'] ?? null) ? $courses['ganjil'] : [];
    $courses_genap = is_array($courses['genap'] ?? null) ? $courses['genap'] : [];
} else {
    $expertises = [];
    $certifications = [];
    $courses_ganjil = [];
    $courses_genap = [];
}
?>

<style>
    .bi,
    .bi::before {
        display: inline-block;
        vertical-align: middle;
        line-height: 1;
    }

    button i.bi,
    .btn i.bi {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        vertical-align: middle;
    }

    button i.bi.me-1,
    .btn i.bi.me-1 {
        margin-right: .25rem;
    }

    /* Custom style for required label */
    .form-label.required:after {
        content: " *";
        color: red;
    }
</style>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3><?php echo e($title ?? 'Team'); ?></h3>
                <p class="text-subtitle text-muted">Form to create Team.</p>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="row">
        <div class="col-12">
            <div class="card border">
                <div class="card-header">
                    <h4 class="card-title"><?= $isEdit ? 'Edit Team' : 'Create Team' ?></h4>
                </div>

                <div class="card-body">
                    <form id="formData" enctype="multipart/form-data" method="POST" action="<?= $formAction ?>">
                        <?php if ($isEdit): ?>
                            <input type="hidden" name="id" value="<?= e($team->id) ?>">
                            <input type="hidden" name="_method" value="PUT">
                        <?php endif; ?>

                        <ul class="nav nav-tabs mb-4" id="teamTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic" type="button" role="tab">
                                    <i class="bi bi-person me-1"></i> Basic Info
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="expertise-tab" data-bs-toggle="tab" data-bs-target="#expertise" type="button" role="tab">
                                    <i class="bi bi-lightbulb me-1"></i> Expertise
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="education-tab" data-bs-toggle="tab" data-bs-target="#education" type="button" role="tab">
                                    <i class="bi bi-mortarboard me-1"></i> Education
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="certification-tab" data-bs-toggle="tab" data-bs-target="#certification" type="button" role="tab">
                                    <i class="bi bi-award me-1"></i> Certifications
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="courses-tab" data-bs-toggle="tab" data-bs-target="#courses" type="button" role="tab">
                                    <i class="bi bi-book me-1"></i> Courses
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="social-tab" data-bs-toggle="tab" data-bs-target="#social" type="button" role="tab">
                                    <i class="bi bi-share me-1"></i> Social Media
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content" id="teamTabsContent">

                            <div class="tab-pane fade show active" id="basic" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="full_name" class="form-label required">Name</label>
                                            <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Full Name"
                                                value="<?= $isEdit ? e($team->full_name) : '' ?>" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="lab_position" class="form-label">Lab Position</label>
                                            <input type="text" class="form-control" id="lab_position" name="lab_position" placeholder="Lab Position"
                                                value="<?= $isEdit ? e($team->lab_position) : '' ?>">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="nip" class="form-label">NIP</label>
                                            <input type="text" class="form-control" id="nip" name="nip" placeholder="NIP"
                                                value="<?= $isEdit ? e($team->nip) : '' ?>">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="nidn" class="form-label">NIDN</label>
                                            <input type="text" class="form-control" id="nidn" name="nidn" placeholder="NIDN"
                                                value="<?= $isEdit ? e($team->nidn) : '' ?>">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="academic_position" class="form-label">Academic Position</label>
                                            <input type="text" class="form-control" id="academic_position" name="academic_position" placeholder="Academic Position"
                                                value="<?= $isEdit ? e($team->academic_position) : '' ?>">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="study_program" class="form-label">Study Program</label>
                                            <?php
                                            $programOptions = ["Teknik Informatika", "Sistem Informasi Bisnis", "Rekayasa Teknologi Informasi", "Pengembangan Perangkat Lunak"];

                                            $dbValue = $isEdit ? ($team->study_program ?? '') : '';
                                            $currentValue = e($dbValue);
                                            ?>

                                            <select class="form-select" id="study_program" name="study_program" style="width: 100%;">
                                                <option value=""></option>

                                                <?php
                                                if (!empty($dbValue) && !in_array($dbValue, $programOptions)): ?>
                                                    <option value="<?= $currentValue ?>" selected="selected"><?= $currentValue ?></option>
                                                <?php endif; ?>

                                                <?php foreach ($programOptions as $option): ?>
                                                    <option value="<?= e($option) ?>" <?= ($dbValue == $option) ? 'selected="selected"' : '' ?>>
                                                        <?= e($option) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" placeholder="Email"
                                                value="<?= $isEdit ? e($team->email) : '' ?>">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="office_address" class="form-label">Office Address</label>
                                            <input type="text" class="form-control" id="office_address" name="office_address" placeholder="Office Address"
                                                value="<?= $isEdit ? e($team->office_address) : '' ?>">
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group mb-3">
                                            <label class="form-label <?= $isEdit ? '' : 'required' ?>">Image</label>
                                            <input type="file" class="form-control" name="image" id="image" accept="image/*" <?= $isEdit ? '' : 'required' ?>>
                                            <?php if ($isEdit && $team->image_name): ?>
                                                <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar.</small>
                                            <?php endif; ?>
                                            <div class="mt-2">
                                                <?php
                                                $imageUrl = $isEdit && !empty($team->image_name) ? base_url('uploads/team/' . $team->image_name) : '';
                                                $displayStyle = ($isEdit && !empty($team->image_name)) ? '' : 'display: none;';
                                                ?>
                                                <img id="img-preview" src="<?= $imageUrl ?>" alt="Image Preview" class="img-thumbnail" style="max-width:200px; <?= $displayStyle ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="expertise" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">Expertise Areas</h5>
                                    <button type="button" class="btn btn-primary btn-sm" id="addExpertiseBtn">
                                        <i class="bi bi-plus-circle me-1"></i> Add Expertise
                                    </button>
                                </div>
                                <div id="expertiseContainer"></div>
                            </div>

                            <div class="tab-pane fade" id="education" role="tabpanel">
                                <h5 class="mb-3">Educational Background</h5>

                                <?php
                                // Decode education data once
                                $education_data = $isEdit ? json_decode($team->education ?? '[]', true) : [];
                                ?>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">S1 (Bachelor's Degree)</label>
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <input type="text" class="form-control" name="education_s1_univ" placeholder="University"
                                                value="<?= e($education_data['S1']['university'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <input type="text" class="form-control" name="education_s1_major" placeholder="Major"
                                                value="<?= e($education_data['S1']['major'] ?? '') ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">S2 (Master's Degree)</label>
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <input type="text" class="form-control" name="education_s2_univ" placeholder="University"
                                                value="<?= e($education_data['S2']['university'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <input type="text" class="form-control" name="education_s2_major" placeholder="Major"
                                                value="<?= e($education_data['S2']['major'] ?? '') ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">S3 (Doctoral Degree)</label>
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <input type="text" class="form-control" name="education_s3_univ" placeholder="University"
                                                value="<?= e($education_data['S3']['university'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <input type="text" class="form-control" name="education_s3_major" placeholder="Major"
                                                value="<?= e($education_data['S3']['major'] ?? '') ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="certification" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">Professional Certifications</h5>
                                    <button type="button" class="btn btn-primary btn-sm" id="addCertBtn">
                                        <i class="bi bi-plus-circle me-1"></i> Add Certification
                                    </button>
                                </div>
                                <div id="certContainer"></div>
                            </div>

                            <div class="tab-pane fade" id="courses" role="tabpanel">
                                <h5 class="mb-3">Courses Taught</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card border-primary">
                                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                                <span><i class="bi bi-calendar-event me-2"></i>Semester Ganjil (Odd)</span>
                                                <button type="button" class="btn btn-light btn-sm" id="addGanjilBtn">
                                                    <i class="bi bi-plus-circle"></i>
                                                </button>
                                            </div>
                                            <div class="card-body">
                                                <div id="ganjilContainer" class="my-3"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="card border-success">
                                            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                                <span><i class="bi bi-calendar-check me-2"></i>Semester Genap (Even)</span>
                                                <button type="button" class="btn btn-light btn-sm" id="addGenapBtn">
                                                    <i class="bi bi-plus-circle"></i>
                                                </button>
                                            </div>
                                            <div class="card-body">
                                                <div id="genapContainer" class="my-3"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="social" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">Social Media Links</h5>
                                    <button type="button" class="btn btn-primary btn-sm" id="addSocialBtn">
                                        <i class="bi bi-plus-circle me-1"></i> Add Social Media
                                    </button>
                                </div>
                                <div id="socialContainer"></div>
                            </div>

                        </div>

                        <div class="col-12 mt-4 pt-3 border-top d-flex justify-content-between">
                            <a href="<?= base_url('admin/team') ?>" class="btn btn-secondary">
                                Back
                            </a>
                            <button type="submit" class="btn btn-primary" id="btnSubmit">
                                <span class="spinner-border spinner-border-sm d-none me-2"></span>
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<?php ob_start(); ?>
<script>
    var audio = new Audio("<?= base_url('assets/audio/success.wav'); ?>");

    let expertiseIndex = 0;
    let certIndex = 0;
    let ganjilIndex = 0;
    let genapIndex = 0;
    let socialIndex = 0;

    let socialMediaOptions = <?= json_encode($socialMediaOptions ?? []) ?>;

    function addExpertise(value = '') {
        $("#expertiseContainer").append(`
      <div class="input-group mb-2" id="exp-${expertiseIndex}">
        <span class="input-group-text d-flex justify-content-center align-items-center"><i class="bi bi-lightbulb-fill text-warning"></i></span>
        <input type="text" class="form-control" name="expertise[]" placeholder="Ex: IoT Security, Machine Learning, etc." value="${value}">
        <button class="btn btn-outline-danger" type="button" onclick="$('#exp-${expertiseIndex}').remove()">
          <i class="bi bi-trash"></i>
        </button>
      </div>
    `);
        expertiseIndex++;
    }
    $("#addExpertiseBtn").on("click", () => addExpertise());

    function addCertification(name = '', publisher = '', year = '') {
        $("#certContainer").append(`
      <div class="card mb-3 border" id="cert-${certIndex}">
        <div class="card-body">
          <div class="row align-items-center">
            <div class="col-md-5">
              <label class="form-label small">Certification Name</label>
              <input type="text" class="form-control" name="cert_name[]" placeholder="Certification Name" value="${name}">
            </div>
            <div class="col-md-4">
              <label class="form-label small">Publisher</label>
              <input type="text" class="form-control" name="cert_publisher[]" placeholder="Publisher" value="${publisher}">
            </div>
            <div class="col-md-2">
              <label class="form-label small">Year</label>
              <input type="text" class="form-control" name="cert_year[]" placeholder="Year" value="${year}">
            </div>
            <div class="col-md-1 text-end mt-3">
              <button class="btn btn-outline-danger btn-sm" type="button" onclick="$('#cert-${certIndex}').remove()">
                <i class="bi bi-trash"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
    `);
        certIndex++;
    }
    $("#addCertBtn").on("click", () => addCertification());

    function addCourse(type = "ganjil", value = '') {
        let idx = type === "ganjil" ? ganjilIndex : genapIndex;
        let container = type === "ganjil" ? "#ganjilContainer" : "#genapContainer";
        let icon = type === "ganjil" ? "calendar-event" : "calendar-check";
        let indexName = type === "ganjil" ? "ganjilIndex" : "genapIndex"; // For global index update

        $(container).append(`
      <div class="input-group mb-2" id="${type}-${idx}">
        <span class="input-group-text"><i class="bi bi-${icon}"></i></span>
        <input type="text" class="form-control" name="courses_${type}[]" placeholder="Course Name" value="${value}">
        <button class="btn btn-outline-danger" type="button" onclick="$('#${type}-${idx}').remove()">
          <i class="bi bi-trash"></i>
        </button>
      </div>
    `);

        (type === "ganjil") ? ganjilIndex++ : genapIndex++;
    }
    $("#addGanjilBtn").on("click", () => addCourse("ganjil"));
    $("#addGenapBtn").on("click", () => addCourse("genap"));

    function addSocialMedia(selectedId = '', linkValue = '') {
        let currentSocialIndex = socialIndex;
        let options = '<option value=""></option>';

        socialMediaOptions.forEach(sm => {
            let selected = String(sm.id) === String(selectedId) ? 'selected' : '';
            options += `<option value="${sm.id}" ${selected}>${sm.name}</option>`;
        });

        $("#socialContainer").append(`
        <div class="card mb-3 border" id="social-${currentSocialIndex}">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <label class="form-label small">Platform</label>
                        <select class="form-select select2-social" id="social-select-${currentSocialIndex}" name="social_media_id[]" required>${options}</select>
                    </div>
                    <div class="col-md-7">
                        <label class="form-label small">Link URL</label>
                        <input type="url" class="form-control" name="social_media_link[]" placeholder="https://..." value="${linkValue}" required>
                    </div>
                    <div class="col-md-1 text-end mt-3">
                        <button class="btn btn-outline-danger btn-sm" type="button" onclick="$('#social-${currentSocialIndex}').remove()">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `);

        $(`#social-select-${currentSocialIndex}`).select2({
            theme: "bootstrap-5",
            width: '100%'
        });

        socialIndex++;
    }

    $("#addSocialBtn").on("click", () => addSocialMedia());

    $(document).ready(function() {

        $(document).on('input', '#nip, #nidn', function() {
            let val = $(this).val().replace(/\D/g, '');

            if (this.id === 'nip') {
                val = val.substring(0, 18);
            }

            if (this.id === 'nidn') {
                val = val.substring(0, 10);
            }

            $(this).val(val);
        });

        $('#study_program').select2({
            theme: "bootstrap-5",
            width: '100%',
            tags: true,
            createTag: function(params) {
                return {
                    id: params.term,
                    text: params.term,
                    newOption: true
                }
            }
        });

        $('#image').on("change", function(e) {
            let file = e.target.files[0];
            if (!file) return;
            let reader = new FileReader();
            reader.onload = e => $('#img-preview').attr('src', e.target.result).show();
            reader.readAsDataURL(file);
        });

        <?php if ($isEdit): ?>
            <?php foreach ($expertises as $exp): ?>
                addExpertise("<?= addslashes(e($exp)) ?>");
            <?php endforeach; ?>

            <?php if (empty($expertises)): ?>
                addExpertise();
            <?php endif; ?>

            <?php foreach ($certifications as $cert):
                $cert = (object) $cert;
            ?>
                addCertification(
                    "<?= addslashes(e($cert->name ?? '')) ?>",
                    "<?= addslashes(e($cert->publisher ?? '')) ?>",
                    "<?= addslashes(e($cert->year ?? '')) ?>"
                );
            <?php endforeach; ?>

            <?php if (empty($certifications)): ?>
                addCertification();
            <?php endif; ?>

            <?php if (!empty($courses_ganjil)): ?>
                <?php foreach ($courses_ganjil as $c): ?>
                    addCourse("ganjil", "<?= addslashes(e($c)) ?>");
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (!empty($courses_genap)): ?>
                <?php foreach ($courses_genap as $c): ?>
                    addCourse("genap", "<?= addslashes(e($c)) ?>");
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (empty($courses_ganjil)): ?>
                addCourse("ganjil");
            <?php endif; ?>
            <?php if (empty($courses_genap)): ?>
                addCourse("genap");
            <?php endif; ?>

            <?php foreach ($team->social_medias as $sos):
                $sos = (object) $sos;
            ?>
                addSocialMedia(
                    "<?= e($sos->social_media_id ?? $sos->id) ?>",
                    "<?= addslashes(e($sos->link_sosmed ?? $sos->link)) ?>"
                );
            <?php endforeach; ?>

            <?php if (empty($team->social_medias)): ?>
                addSocialMedia();
            <?php endif; ?>

        <?php else: ?>
            addExpertise();
            addCertification();
            addCourse("ganjil");
            addCourse("genap");
            addSocialMedia();
        <?php endif; ?>

        $("#formData").submit(function(e) {
            e.preventDefault();
            let btn = $("#btnSubmit");
            let spinner = btn.find(".spinner-border");
            btn.prop("disabled", true);
            spinner.removeClass("d-none");

            $.ajax({
                url: "<?= $formAction ?>",
                type: "POST",
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function(res) {
                    audio.play();

                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        if (res.success) {
                            window.location.href = "<?= base_url('admin/team') ?>";
                        }
                    });
                },
                error: xhr => {
                    Swal.fire("Error", xhr.responseJSON?.message || "Server Error", "error");
                },
                complete: () => {
                    btn.prop("disabled", false);
                    spinner.addClass("d-none");
                }
            });
        });
    });
</script>
<?php $pageScripts = ob_get_clean(); ?>