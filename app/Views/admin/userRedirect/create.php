<div class="page-heading">
    <h3><?= e($title) ?></h3>
</div>

<div class="page-content">
    <div class="card border">
        <div class="card-body">
            <form action="<?= base_url('admin/userRedirect/insert') ?>" method="POST">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" value="<?= old('username') ?>" required>
                    <?php if (isset(flash('errors')['username'])): ?>
                        <div class="text-danger small"><?= flash('errors')['username'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= old('email') ?>" required>
                    <?php if (isset(flash('errors')['email'])): ?>
                        <div class="text-danger small"><?= flash('errors')['email'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="full_name" class="form-control" value="<?= old('full_name') ?>" required>
                    <?php if (isset(flash('errors')['full_name'])): ?>
                        <div class="text-danger small"><?= flash('errors')['full_name'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="id_roles" class="form-select" required>
                        <option value="">-- Pilih Role --</option>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?= $role->id ?>" <?= old('id_roles') == $role->id ? 'selected' : '' ?>>
                                <?= e($role->role_name) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset(flash('errors')['id_roles'])): ?>
                        <div class="text-danger small"><?= flash('errors')['id_roles'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                    <?php if (isset(flash('errors')['password'])): ?>
                        <div class="text-danger small"><?= flash('errors')['password'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="mt-4">
                    <a href="<?= base_url('admin/userRedirect') ?>" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>