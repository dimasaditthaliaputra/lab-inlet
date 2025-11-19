<div class="page-heading">
    <h3><?= e($title) ?></h3>
</div>

<div class="page-content">
    <div class="card">
        <div class="card-body">
            <form action="<?= base_url("admin/userRedirect/{$user->id}/update") ?>" method="POST">
                <?= csrf_field() ?>
                <?= method_field('PUT') ?> <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" value="<?= old('username', $user->username) ?>" required>
                    <?php if(isset(flash('errors')['username'])): ?>
                        <div class="text-danger small"><?= flash('errors')['username'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= old('email', $user->email) ?>" required>
                    <?php if(isset(flash('errors')['email'])): ?>
                        <div class="text-danger small"><?= flash('errors')['email'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="full_name" class="form-control" value="<?= old('full_name', $user->full_name) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="id_roles" class="form-select" required>
                        <option value="">-- Pilih Role --</option>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?= $role->id ?>" <?= (old('id_roles', $user->id_roles) == $role->id) ? 'selected' : '' ?>>
                                <?= e($role->role_name) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Biarkan kosong jika tidak ingin mengubah password">
                </div>

                <div class="mt-4">
                    <a href="<?= base_url('admin/userRedirect') ?>" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>