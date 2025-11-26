<div class="page-heading">
    <h3>Edit Team</h3>
</div>
<div class="page-content">
    <section class="row">
        <div class="col-12">
            <div class="card border">
                <div class="card-body"></div>
                <form action="<?= base_url('admin/team/' . $team->id); ?>" method="POST">
                    <input type="hidden" name="_method" value="PUT">

                    <div class="mb-3">
                        <label class="form-label">Team Name</label>
                        <input type="text" class="form-control" name="name"
                            value="<?= $team->name; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Team Position</label>
                        <input type="text" class="form-control" name="position"
                            value="<?= $team->position; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">NIP</label>
                        <input type="text" class="form-control" name="nip"
                            value="<?= $team->nip; ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">NIDN</label>
                        <input type="text" class="form-control" name="nidn"
                            value="<?= $team->nidn; ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Study Program</label>
                        <input type="text" class="form-control" name="study_program"
                            value="<?= $team->study_program; ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <input type="text" class="form-control" name="description"
                            value="<?= $team->description; ?>">
                    </div>

                    <hr>
                    <h5>Sosial Media</h5>

                    <div class="mb-2">
                        <input type="text" class="form-control" name="linkedin"
                            value="<?= $social->linkedin ?? ''; ?>" placeholder="LinkedIn">
                    </div>
                    <div class="mb-2">
                        <input type="text" class="form-control" name="google_scholar"
                            value="<?= $social->google_scholar ?? ''; ?>" placeholder="Google Scholar">
                    </div>
                    <div class="mb-2">
                        <input type="text" class="form-control" name="sinta"
                            value="<?= $social->sinta ?? ''; ?>" placeholder="Sinta">
                    </div>
                    <div class="mb-2">
                        <input type="email" class="form-control" name="email"
                            value="<?= $social->email ?? ''; ?>" placeholder="Email">
                    </div>
                    <div class="mb-2">
                        <input type="text" class="form-control" name="cv"
                            value="<?= $social->cv ?? ''; ?>" placeholder="CV">
                    </div>

                    <button type="submit" class="btn btn-success">Update</button>
                    <a href="<?= base_url('admin/team'); ?>" class="btn btn-secondary">Kembali</a>

                </form>
            </div>
        </div>
</div>
</section>
</div>