<?php
$team = isset($data['team']) ? (object) $data['team'] : null;
$isEdit = !empty($team);
$formAction = $isEdit ? base_url('admin/team/' . $team->id) : base_url('admin/team');
?>

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
                            <input type="hidden" name="id" value="<?= $team->id ?>">
                            <input type="hidden" name="_method" value="PUT">
                        <?php endif; ?>

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


                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="nip" class="form-label">NIP</label>
                                    <input type="text" class="form-control" id="nip" name="nip" placeholder="nip"
                                        value="<?= $isEdit ? e($team->nip) : '' ?>">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="nidn" class="form-label">NIDN</label>
                                    <input type="text" class="form-control" id="nidn" name="nidn" placeholder="nidn"
                                        value="<?= $isEdit ? e($team->nidn) : '' ?>">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="academic_position" class="form-label">Academic Position</label>
                                    <input type="text" class="form-control" id="academic_position" name="academic_position" placeholder="academic position"
                                        value="<?= $isEdit ? e($team->academic_position) : '' ?>">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="study_program" class="form-label">Study Program</label>
                                    <input type="text" class="form-control" id="study_program" name="study_program" placeholder="study program"
                                        value="<?= $isEdit ? e($team->study_program) : '' ?>">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="text" class="form-control" id="email" name="email" placeholder="email"
                                        value="<?= $isEdit ? e($team->email) : '' ?>">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="office_address" class="form-label">Office Address</label>
                                    <input type="text" class="form-control" id="office_address" name="office_address" placeholder="office address"
                                        value="<?= $isEdit ? e($team->office_address) : '' ?>">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label class="form-label <?= $isEdit ? '' : 'required' ?>">Image</label>
                                    <input type="file" class="form-control" name="image" id="image" accept="image/*">
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

                            <div class="col-12 mt-2">
                                <div class="card border">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <span class="fw-bold">Expertise</span>
                                        <button type="button" class="btn btn-outline-primary btn-sm" id="addExpertiseBtn">+ Tambah Expertise</button>
                                    </div>
                                    <div class="card-body" id="expertiseContainer"></div>
                                </div>
                            </div>

                            <div class="card border mt-3 col-12">
                                <div class="card-header fw-bold">Education</div>
                                <div class="card-body">

                                    <div class="input-group mb-2">
                                        <span class="input-group-text">S1</span>
                                        <input type="text" class="form-control" name="education_s1_univ"
                                            placeholder="University"
                                            value="<?= $isEdit ? e(json_decode($team->education, true)['S1']['university'] ?? '') : '' ?>">
                                        <span class="input-group-text ms-3  ">Major</span>
                                        <input type="text" class="form-control" name="education_s1_major"
                                            placeholder="Major"
                                            value="<?= $isEdit ? e(json_decode($team->education, true)['S1']['major'] ?? '') : '' ?>">
                                    </div>

                                    <div class="input-group mb-2">
                                        <span class="input-group-text">S2</span>
                                        <input type="text" class="form-control" name="education_s2_univ"
                                            placeholder="University"
                                            value="<?= $isEdit ? e(json_decode($team->education, true)['S2']['university'] ?? '') : '' ?>">
                                        <span class="input-group-text ms-3">Major</span>
                                        <input type="text" class="form-control" name="education_s2_major"
                                            placeholder="Major"
                                            value="<?= $isEdit ? e(json_decode($team->education, true)['S2']['major'] ?? '') : '' ?>">
                                    </div>

                                    <div class="input-group">
                                        <span class="input-group-text">S3</span>
                                        <input type="text" class="form-control" name="education_s3_univ"
                                            placeholder="University"
                                            value="<?= $isEdit ? e(json_decode($team->education, true)['S3']['university'] ?? '') : '' ?>">
                                        <span class="input-group-text ms-3">Major</span>
                                        <input type="text" class="form-control" name="education_s3_major"
                                            placeholder="Major"
                                            value="<?= $isEdit ? e(json_decode($team->education, true)['S3']['major'] ?? '') : '' ?>">

                                    </div>

                                </div>
                            </div>

                            <div class="card border mt-3 col-12">
                                <div class="card-header fw-bold d-flex justify-content-between align-items-center">
                                    <span>Certifications</span>
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="addCertBtn">+ Tambah Sertifikat</button>
                                </div>
                                <div class="card-body" id="certContainer"></div>
                            </div>

                            <div class="row col-12">
                                <div class="col-md-6">
                                    <div class="card border mt-3">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <span class="fw-bold">Courses Taught - Semester Ganjil</span>
                                            <button type="button" class="btn btn-outline-primary btn-sm" id="addGanjilBtn">+ Tambah Course</button>
                                        </div>
                                        <div class="card-body">
                                            <div id="ganjilContainer" class="mb-3"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card border mt-3">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <span class="fw-bold">Courses Taught - Semester Genap</span>
                                            <button type="button" class="btn btn-outline-primary btn-sm" id="addGenapBtn">+ Tambah Course</button>
                                        </div>
                                        <div class="card-body">
                                            <div id="genapContainer" class="mb-3"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-3">
                                <div class="card border">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <span class="fw-bold">Social Links</span>
                                        <button type="button" class="btn btn-outline-primary btn-sm" id="addSocialBtn">+ Tambah Social Media</button>
                                    </div>
                                    <div class="card-body" id="socialContainer"></div>
                                </div>
                            </div>

                            <div class="col-12 mt-3 d-flex justify-content-end">
                                <a href="<?= base_url('admin/team') ?>" class="btn btn-secondary">Back</a>
                                <button type="submit" class="btn btn-primary ms-2" id="btnSubmit">
                                    <span class="spinner-border spinner-border-sm d-none me-2"></span>
                                    Save
                                </button>
                            </div>
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

    let socialMediaOptions = <?= json_encode($socialMediaOptions) ?> ?? [];

    function addExpertise(value = '') {
        $("#expertiseContainer").append(`
            <div class="input-group mb-2" id="exp-${expertiseIndex}">
                <input type="text" class="form-control" name="expertise[]" placeholder="Ex: IoT Security" value="${value}">
                <button class="btn btn-outline-danger" type="button" onclick="$('#exp-${expertiseIndex}').remove()">Hapus</button>
            </div>
        `);
        expertiseIndex++;
    }
    $("#addExpertiseBtn").on("click", () => addExpertise());

    function addCertification(name = '', publisher = '', year = '') {
        $("#certContainer").append(`
            <div class="row mb-2 align-items-center" id="cert-${certIndex}">
                <div class="col-md-5">
                    <input type="text" class="form-control" name="cert_name[]" placeholder="Certification Name" value="${name}">
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="cert_publisher[]" placeholder="Publisher" value="${publisher}">
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" name="cert_year[]" placeholder="Year" value="${year}">
                </div>
                <div class="col-md-1 text-end">
                    <button class="btn btn-outline-danger btn-sm" type="button" onclick="$('#cert-${certIndex}').remove()">Hapus</button>
                </div>
            </div>
        `);
        certIndex++;
    }
    $("#addCertBtn").on("click", () => addCertification());

    function addCourse(type = "ganjil", value = '') {
        let idx = type === "ganjil" ? ganjilIndex : genapIndex;
        let container = type === "ganjil" ? "#ganjilContainer" : "#genapContainer";

        $(container).append(`
            <div class="input-group mb-2" id="${type}-${idx}">
                <input type="text" class="form-control" name="courses_${type}[]" placeholder="Course Name" value="${value}">
                <button class="btn btn-outline-danger" type="button" onclick="$('#${type}-${idx}').remove()">Hapus</button>
            </div>
        `);

        (type === "ganjil") ? ganjilIndex++ : genapIndex++;
    }
    $("#addGanjilBtn").on("click", () => addCourse("ganjil"));
    $("#addGenapBtn").on("click", () => addCourse("genap"));

    function addSocialMedia(selectedId = '', linkValue = '') {
        let options = '<option value="">-- Pilih Social Media --</option>';

        socialMediaOptions.forEach(sm => {
            let selected = sm.id == selectedId ? 'selected' : '';
            options += `<option value="${sm.id}" ${selected}>${sm.name}</option>`;
        });

        $("#socialContainer").append(`
            <div class="row mb-2 align-items-center" id="social-${socialIndex}">
                <div class="col-md-4">
                    <select class="form-select" name="social_media_id[]" required>${options}</select>
                </div>
                <div class="col-md-7">
                    <input type="text" class="form-control" name="social_media_link[]" placeholder="https://..." value="${linkValue}" required>
                </div>
                <div class="col-md-1 text-end">
                    <button class="btn btn-outline-danger btn-sm" type="button" onclick="$('#social-${socialIndex}').remove()">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        `);

        socialIndex++;
    }

    $("#addSocialBtn").on("click", () => addSocialMedia());

    $(document).ready(function() {

        <?php if ($isEdit && !empty($team->social_medias)): ?>
            <?php foreach ($team->social_medias as $sos): ?>
                addSocialMedia(
                    "<?= $sos->id ?>",
                    "<?= htmlspecialchars($sos->link_sosmed, ENT_QUOTES) ?>"
                );
            <?php endforeach; ?>
        <?php endif; ?>

        $('#image').on("change", function(e) {
            let file = e.target.files[0];
            if (!file) return;
            let reader = new FileReader();
            reader.onload = e => $('#img-preview').attr('src', e.target.result).show();
            reader.readAsDataURL(file);
        });

        <?php if ($isEdit): ?>
            <?php
            $expertises = $team->expertise;
            if (is_string($expertises)) {
                $expertises = json_decode(stripslashes($expertises), true);
            }
            if (!empty($expertises)):
                foreach ($expertises as $exp):
            ?>
                    addExpertise("<?= e($exp) ?>");
            <?php endforeach;
            endif; ?>
        <?php endif; ?>

        <?php if ($isEdit && !empty($team->courses_taught)): ?>
            <?php
            $courses = is_string($team->courses_taught) ? json_decode($team->courses_taught, true) : $team->courses_taught;
            ?>

            <?php if (!empty($courses['ganjil'])): ?>
                <?php foreach ($courses['ganjil'] as $c): ?>
                    addCourse("ganjil", "<?= e($c) ?>");
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (!empty($courses['genap'])): ?>
                <?php foreach ($courses['genap'] as $c): ?>
                    addCourse("genap", "<?= e($c) ?>");
                <?php endforeach; ?>
            <?php endif; ?>
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