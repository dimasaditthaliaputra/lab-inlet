<div class="page-heading">
    <h3>Tambah Team Baru</h3>
</div>

<div class="page-content">
    <section class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <form action="<?= base_url('admin/team'); ?>" method="POST">

                        <div class="mb-3">
                            <label class="form-label">Team Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Masukkan nama team" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Team Position</label>
                            <input type="text" class="form-control" name="position" placeholder="Masukkan posisi team" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">NIP</label>
                            <input type="text" class="form-control" name="nip" placeholder="Masukkan NIP">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">NIDN</label>
                            <input type="text" class="form-control" name="nidn" placeholder="Masukkan NIDN">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Study Program</label>
                            <input type="text" class="form-control" name="study_program" placeholder="Masukkan Study Program">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <input type="text" class="form-control" name="description" placeholder="Masukkan deskripsi">
                        </div>

                        <hr>
                        <h5>Sosial Media</h5>

                        <div class="mb-2">
                            <input type="text" class="form-control" name="linkedin" placeholder="LinkedIn">
                        </div>
                        <div class="mb-2">
                            <input type="text" class="form-control" name="google_scholar" placeholder="Google Scholar">
                        </div>
                        <div class="mb-2">
                            <input type="text" class="form-control" name="sinta" placeholder="Sinta">
                        </div>
                        <div class="mb-2">
                            <input type="email" class="form-control" name="email" placeholder="Email">
                        </div>
                        <div class="mb-2">
                            <input type="text" class="form-control" name="cv" placeholder="CV">
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="<?= base_url('admin/team'); ?>" class="btn btn-secondary">Kembali</a>

                    </form>
                </div>
            </div>
        </div>
    </section>
</div>