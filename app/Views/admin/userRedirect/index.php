<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center">
        <h3><?= e($title) ?></h3>
        <a href="<?= base_url('admin/userRedirect/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus"></i> Tambah User
        </a>
    </div>
</div>

<div class="page-content">
    <?php if ($msg = flash('success')): ?>
        <div class="alert alert-success"><?= $msg ?></div>
    <?php endif; ?>
    <?php if ($msg = flash('error')): ?>
        <div class="alert alert-danger"><?= $msg ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Full Name</th>
                            <th>Role</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($users)): ?>
                            <?php foreach ($users as $index => $row): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= e($row->username) ?></td>
                                    <td><?= e($row->email) ?></td>
                                    <td><?= e($row->full_name) ?></td>
                                    <td><span class="badge bg-secondary"><?= e($row->role_name ?? '-') ?></span></td>
                                    <td>
                                        <a href="<?= base_url("admin/userRedirect/{$row->id}/edit") ?>" class="btn btn-sm btn-warning">Edit</a>
                                        
                                        <form action="<?= base_url("admin/userRedirect/{$row->id}/delete") ?>" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                                            <?= csrf_field() ?>
                                            <?= method_field('DELETE') ?>
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">Belum ada data user.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>