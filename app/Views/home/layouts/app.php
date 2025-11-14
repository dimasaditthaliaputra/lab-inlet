<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'MVC App' ?></title>
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>

<body>

    <?php include_once __DIR__ . '/component/navbar.php'; ?>

    <main class="content">
        <?= $content ?>
    </main>

    <?php include_once __DIR__ . '/component/footer.php'; ?>

    <script src="<?= asset('js/script.js') ?>"></script>

    <script>
        initTextSlideOnScroll('.about-us .text-slide-animate', {
            charDelay: 0.01,
            baseDelay: 0
        });
    </script>
</body>