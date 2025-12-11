<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $_ENV['APP_NAME'] . ' - Landing Page' ?? 'Lab Inlet' ?></title>
    <link href="<?= asset('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('assets/mazer/extensions/@fortawesome/fontawesome-free/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('assets/mazer/extensions/bootstrap-icons/font/bootstrap-icons.css') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>

<body>
    <?php include_once __DIR__ . '/component/navbar.php'; ?>

    <main class="content">
        <?= $content ?>
    </main>

    <?php include_once __DIR__ . '/component/footer.php'; ?>

    <script src="<?= asset('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= asset('js/script.js') ?>"></script>
</body>