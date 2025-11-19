<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Lab Inlet' ?></title>

    <!-- CSS -->
    <link rel="stylesheet" href="<?= asset('assets/mazer/compiled/css/app.css') ?>">
    <link rel="stylesheet" href="<?= asset('assets/mazer/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('assets/mazer/compiled/css/table-datatable-jquery.css') ?>">
    <link rel="stylesheet" href="<?= asset('assets/mazer/compiled/css/iconly.css') ?>">
    <link rel="stylesheet" href="<?= asset('assets/mazer/extensions/sweetalert2/sweetalert2.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
</head>

<body>
    <div id="app">
        <?php include __DIR__ . '/components/sidebar.php'; ?>
        
        <div id="main">
            <?php include __DIR__ . '/components/navbar.php'; ?>
            
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            <div class="page-content">
                <?= $content ?>
            </div>
            <?php include_once __DIR__ . '/components/footer.php'; ?>
        </div>
    </div>

    <script src="<?= base_url('assets/mazer/compiled/js/app.js') ?>"></script>

    <script src="<?= base_url('assets/mazer/extensions/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/mazer/extensions/datatables.net/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?= base_url('assets/mazer/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') ?>"></script>
    <script src="<?= base_url('assets/mazer/static/js/pages/datatables.js') ?>"></script>

    <script src="<?= base_url('assets/mazer/extensions/sweetalert2/sweetalert2.all.min.js') ?>"></script>

    <script>
        function updateClock() {
            const now = new Date();

            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const timeString = `${hours}:${minutes}:${seconds}`;

            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            const dateString = now.toLocaleDateString('id-ID', options);

            document.getElementById('current-time').textContent = timeString;
            document.getElementById('current-date').textContent = dateString;
        }

        updateClock();
        const clockInterval = setInterval(updateClock, 1000);

        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                updateClock();
            }
        });

        window.addEventListener('focus', function() {
            updateClock();
        });

        $(document).ready(function() {
            $('#logout-link').click(function(e) {
                e.preventDefault();

                Swal.fire({
                    title: "Are you sure?",
                    text: "You will be logged out!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Yes, log out!',
                    cancelButtonText: "No, stay!",
                    didOpen: () => {
                        const confirmButton = Swal.getConfirmButton();
                        if (confirmButton) {
                            confirmButton.style.setProperty('background-color', '#d33', 'important');
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '<?= base_url('admin/logout') ?>',
                            type: 'POST',
                            dataType: 'json',
                            success: function(res) {
                                if (res.success) {
                                    Swal.fire({
                                        title: 'Logged Out!',
                                        text: 'You have been logged out.',
                                        icon: 'success',
                                        timer: 1500,
                                        showConfirmButton: false
                                    }).then(() => {
                                        window.location.href = '<?= base_url('admin/login') ?>';
                                    });

                                } else {
                                    swal("Error", "Logout failed. Please try again.", "error");
                                }
                            },
                            error: function() {
                                swal("Error", "Could not connect to the server.", "error");
                            },
                        });
                    }
                });
            });
        });
    </script>

    <?php if (isset($pageScripts)): ?>
        <?= $pageScripts ?>
    <?php endif; ?>
</body>

</html>