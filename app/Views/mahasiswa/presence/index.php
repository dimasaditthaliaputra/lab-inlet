<div class="page-heading">
    <h3 class="page-title"><i class="bi bi-fingerprint me-2"></i> <?php echo e($title ?? 'Student Attendance'); ?></h3>
</div>

<div class="page-content">
    <section class="row">
        <div class="col-12 col-lg-8 offset-lg-2">

            <div class="card border border-primary shadow-sm mb-4">
                <div class="card-body text-center">
                    <h5 class="text-primary mb-3">Today's Attendance Schedule</h5>
                    <div class="d-flex justify-content-center gap-5">
                        <div class="text-success">
                            <i class="bi bi-box-arrow-in-right fs-4"></i>
                            <h6 class="mt-2 mb-0">CHECK-IN</h6>
                            <h4 class="fw-bold"><?php echo e(substr($checkInTime, 0, 5)); ?></h4>
                        </div>
                        <div class="text-danger">
                            <i class="bi bi-box-arrow-right fs-4"></i>
                            <h6 class="mt-2 mb-0">CHECK-OUT</h6>
                            <h4 class="fw-bold"><?php echo e(substr($checkOutTime, 0, 5)); ?></h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-lg mb-4">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Your Attendance Status</h5>
                </div>
                <div class="card-body text-center">
                    <?php if ($isPermitted): ?>
                        <div class="alert alert-warning">
                            <i class="bi bi-bell-fill me-2"></i> You are on an approved Leave/Permit. No attendance required.
                        </div>
                        <h1 class="text-warning"><i class="bi bi-calendar-check"></i></h1>
                        <p class="mb-0">Your attendance activity is disabled for today.</p>
                    <?php else: ?>
                        <div id="status-display" class="mt-2">

                            <?php if ($hasCheckedIn && $hasCheckedOut): ?>
                                <h1 class="text-info"><i class="bi bi-check-all"></i></h1>
                                <p class="mb-1 fw-bold">Attendance for today is complete</p>
                                <p class="text-muted small">See you tomorrow!</p>

                            <?php elseif ($hasCheckedIn && !$hasCheckedOut): ?>
                                <h1 class="text-success"><i class="bi bi-check-circle-fill"></i></h1>
                                <p class="mb-1 fw-bold">You have already checked in</p>
                                <p class="text-success small">Check-in Time: <?php echo e(date('H:i:s', strtotime($currentLog->log_time))); ?></p>
                                <button id="btnCheckOut" class="btn btn-warning btn-lg mt-3 w-75" data-bs-toggle="modal" data-bs-target="#cameraModal" data-log-type="check_out" disabled>
                                    <i class="bi bi-camera-fill me-1"></i> Check-out & Selfie
                                </button>
                                <p class="small text-muted mt-2">You are allowed to check out after <?php echo e(substr($checkOutTime, 0, 5)); ?></p>

                            <?php else: ?>
                                <h1 class="text-danger"><i class="bi bi-x-circle-fill"></i></h1>
                                <p class="mb-1 fw-bold">You haven't checked in today</p>
                                <button id="btnCheckIn" class="btn btn-success btn-lg mt-3 w-75" data-bs-toggle="modal" data-bs-target="#cameraModal" data-log-type="check_in">
                                    <i class="bi bi-camera-fill me-1"></i> Check-in & Selfie
                                </button>
                                <p class="small text-muted mt-2">Check-in is available from <?php echo e(substr($checkInTime, 0, 5)); ?></p>

                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted"><i class="bi bi-geo-alt-fill me-1"></i> Location Information</h6>
                    <p class="card-text small mb-1">GPS Status: <span id="gps-status" class="badge bg-secondary">Getting Location...</span></p>
                    <p class="card-text small mb-0">Latitude: <span id="lat-display">N/A</span></p>
                    <p class="card-text small">Longitude: <span id="lon-display">N/A</span></p>
                </div>
            </div>

        </div>
    </section>
</div>

<div class="modal fade" id="cameraModal" tabindex="-1" aria-labelledby="cameraModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cameraModalLabel"><i class="bi bi-camera-video me-1"></i> Ambil Selfie Absensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p id="camera-status" class="text-muted small">Memuat kamera...</p>
                <video id="webcamVideo" class="img-fluid rounded shadow-sm" style="display: none; width: 100%; max-height: 300px; transform: scaleX(-1);"></video>
                <canvas id="photoCanvas" style="display: none; width: 100%; max-height: 300px;"></canvas>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btnCancelCamera">Batal</button>
                <button type="button" class="btn btn-primary" id="btnCapture" disabled>
                    <i class="bi bi-camera me-1"></i> Ambil Foto
                </button>
                <button type="button" class="btn btn-success d-none" id="btnConfirmAttendance" disabled>
                    <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                    <i class="bi bi-check-circle-fill me-1"></i> Konfirmasi Absensi
                </button>
                <button type="button" class="btn btn-info d-none" id="btnRetake">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Ambil Ulang
                </button>
            </div>
        </div>
    </div>
</div>

<?php ob_start(); ?>
<script>
    const PROCESS_URL = '<?php echo base_url('mahasiswa/presence/process'); ?>';
    const CHECK_IN_TIME_STR = '<?php echo e($checkInTime); ?>';
    const CHECK_OUT_TIME_STR = '<?php echo e($checkOutTime); ?>';
    const HAS_CHECKED_IN = <?php echo $hasCheckedIn ? 'true' : 'false'; ?>;
    const HAS_CHECKED_OUT = <?php echo $hasCheckedOut ? 'true' : 'false'; ?>;
    const IS_PERMITTED = <?php echo $isPermitted ? 'true' : 'false'; ?>;

    var audio = new Audio("<?php echo base_url('assets/audio/success.wav'); ?>");

    let currentLatitude = null;
    let currentLongitude = null;
    let currentStream = null;
    let currentLogType = null;
    let photoDataURL = null;

    function timeToSeconds(timeStr) {
        const parts = timeStr.split(':');
        return parseInt(parts[0]) * 3600 + parseInt(parts[1]) * 60 + parseInt(parts[2] || 0);
    }

    function getGeolocation() {
        const statusElement = $('#gps-status');

        if (!navigator.geolocation) {
            statusElement.removeClass('bg-secondary').addClass('bg-danger').text('GPS NOT SUPPORTED');
            Swal.fire('Error', 'Your browser does not support Geolocation.', 'error');
            return;
        }

        statusElement.removeClass('bg-secondary').addClass('bg-info').text('Trying to get location...');

        navigator.geolocation.getCurrentPosition(
            (position) => {
                currentLatitude = position.coords.latitude;
                currentLongitude = position.coords.longitude;

                $('#lat-display').text(currentLatitude);
                $('#lon-display').text(currentLongitude);
                statusElement.removeClass('bg-info').addClass('bg-success').text('LOCATION FOUND');
            },
            (error) => {
                let errorMessage = 'Failed to get location.';
                if (error.code === error.PERMISSION_DENIED) {
                    errorMessage = 'Location access denied by user. Please allow location access.';
                } else if (error.code === error.TIMEOUT) {
                    errorMessage = 'Location request timed out.';
                }

                statusElement.removeClass('bg-info').addClass('bg-danger').text('GPS FAILED');
                Swal.fire('Warning', errorMessage, 'warning');
            }, {
                enableHighAccuracy: true,
                timeout: 5000,
                maximumAge: 0
            }
        );
    }

    function startCamera() {
        const video = $('#webcamVideo')[0];
        const status = $('#camera-status');

        $('#photoCanvas').hide();
        $('#webcamVideo').show();
        $('#btnCapture').prop('disabled', true).show();
        $('#btnConfirmAttendance, #btnRetake').addClass('d-none');

        navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: "user"
                }
            })
            .then(function(stream) {
                currentStream = stream;
                video.srcObject = stream;
                video.play();

                status.text('Kamera aktif. Silakan ambil foto.');
                $('#btnCapture').prop('disabled', false);

            })
            .catch(function(err) {
                status.text('Gagal mengakses kamera. Mohon berikan izin.').removeClass('text-muted').addClass('text-danger');
                Swal.fire('Kamera Error', 'Anda harus memberikan izin kamera untuk absensi.', 'error');
            });
    }

    function stopCamera() {
        if (currentStream) {
            currentStream.getTracks().forEach(track => track.stop());
            currentStream = null;
        }
    }

    function capturePhoto() {
        const video = $('#webcamVideo')[0];
        const canvas = $('#photoCanvas')[0];
        const context = canvas.getContext('2d');

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;

        context.translate(canvas.width, 0);
        context.scale(-1, 1);

        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        photoDataURL = canvas.toDataURL('image/jpeg', 0.8);

        stopCamera();
        $('#webcamVideo').hide();
        $('#photoCanvas').show();

        $('#btnCapture').hide();
        $('#btnConfirmAttendance, #btnRetake').removeClass('d-none').prop('disabled', false);
        $('#camera-status').text('Foto berhasil diambil. Silakan Konfirmasi.');
    }

    function processAttendance(logType, photoDataURL) {
        if (!currentLatitude || !currentLongitude || !photoDataURL) {
            Swal.fire('Error', 'Data GPS atau foto tidak lengkap.', 'error');
            return;
        }

        const btn = $('#btnConfirmAttendance');
        btn.prop('disabled', true);
        btn.find('.spinner-border').removeClass('d-none');

        $.ajax({
            url: PROCESS_URL,
            type: 'POST',
            data: {
                log_type: logType,
                latitude: currentLatitude,
                longitude: currentLongitude,
                photo_path: photoDataURL
            },
            dataType: 'JSON',
            success: function(res) {
                if (res.success) {
                    audio.play();
                    $('#cameraModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: res.message,
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire('Gagal!', res.message, 'error');
                }
            },
            error: function(xhr) {
                let msg = xhr.responseJSON?.message || 'An server error occurred while processing attendance.';
                Swal.fire('Server Error!', msg, 'error');
            },
            complete: function() {
                btn.prop('disabled', false);
                btn.find('.spinner-border').addClass('d-none');
            }
        });
    }

    function checkTimeBoundaries() {
        const now = new Date();
        const nowTime = now.getHours() * 3600 + now.getMinutes() * 60 + now.getSeconds();

        const checkInStartSeconds = timeToSeconds(CHECK_IN_TIME_STR);
        const checkOutMinSeconds = timeToSeconds(CHECK_OUT_TIME_STR);
        const absensiEndSeconds = checkOutMinSeconds + 3600;

        const startWindowSeconds = checkInStartSeconds - 3600;

        const btnCheckIn = $('#btnCheckIn');
        const btnCheckOut = $('#btnCheckOut');
        const statusDisplay = $('#status-display');
        let message = '';

        function formatSecondsToTime(totalSeconds) {
            const midnight = new Date(now);
            midnight.setHours(0, 0, 0, 0);

            const targetTime = new Date(midnight.getTime() + totalSeconds * 1000);

            return targetTime.toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        if (nowTime < startWindowSeconds || nowTime > absensiEndSeconds) {
            btnCheckIn.prop('disabled', true);
            btnCheckOut.prop('disabled', true);

            const readableStart = formatSecondsToTime(startWindowSeconds);
            const readableEnd = formatSecondsToTime(absensiEndSeconds);

            if (nowTime < startWindowSeconds) {
                message = `<div class="alert alert-danger mt-3"><i class="bi bi-x-octagon-fill me-1"></i> Absensi baru dapat dilakukan mulai pukul ${readableStart}.</div>`;
            } else if (nowTime > absensiEndSeconds && !HAS_CHECKED_OUT) {
                message = `<div class="alert alert-danger mt-3"><i class="bi bi-x-octagon-fill me-1"></i> Waktu absensi telah berakhir pada pukul ${readableEnd}.</div>`;
            } else {
                message = '';
            }

            statusDisplay.find('.alert-danger').remove();
            if (message && !IS_PERMITTED && (!HAS_CHECKED_IN || !HAS_CHECKED_OUT)) {
                statusDisplay.find('.btn-lg').prop('disabled', true);
                statusDisplay.find('.small.text-muted').hide();
                statusDisplay.append(message);
            }
        } else {
            if (HAS_CHECKED_IN && !HAS_CHECKED_OUT && !IS_PERMITTED) {
                const isCheckOutReady = nowTime >= checkOutMinSeconds;
                const checkoutHint = statusDisplay.find('.small.text-muted:contains("You are allowed to check out")');
                
                if (isCheckOutReady) {
                    btnCheckOut.prop('disabled', false);
                    checkoutHint.text('Saatnya untuk Check-out!');
                } else {
                    btnCheckOut.prop('disabled', true);
                    checkoutHint.text(`Anda diizinkan Check-out setelah ${formatSecondsToTime(checkOutMinSeconds)}`);
                }
            }
            
            if (!HAS_CHECKED_IN && !HAS_CHECKED_OUT && !IS_PERMITTED) {
                const isCheckInReady = nowTime >= checkInStartSeconds;
                if (isCheckInReady) {
                    btnCheckIn.prop('disabled', false);
                } else {
                    btnCheckIn.prop('disabled', true);
                }
            }
        }
    }

    $(document).ready(function() {
        getGeolocation();

        $('#cameraModal').on('show.bs.modal', function(event) {
            const nowTime = new Date().getHours() * 3600 + new Date().getMinutes() * 60 + new Date().getSeconds();
            const checkOutMinSeconds = timeToSeconds(CHECK_OUT_TIME_STR);
            
            const button = $(event.relatedTarget);
            currentLogType = button.data('log-type');

            if (currentLogType === 'check_out' && nowTime < checkOutMinSeconds) {
                Swal.fire('Belum Waktunya!', 'Anda belum diizinkan untuk Check-out saat ini.', 'warning');
                return event.preventDefault();
            }
            
            if (!currentLatitude) {
                Swal.fire('GPS Diperlukan', 'Harap tunggu hingga lokasi GPS ditemukan sebelum Check-in/Check-out.', 'warning');
                return event.preventDefault();
            }

            startCamera();
        });

        $('#cameraModal').on('hidden.bs.modal', function() {
            stopCamera();
            photoDataURL = null;
            currentLogType = null;
            $('#camera-status').text('Memuat kamera...');
        });

        $('#btnCapture').on('click', capturePhoto);
        $('#btnRetake').on('click', startCamera);

        $('#btnConfirmAttendance').on('click', function() {
            if (photoDataURL) {
                processAttendance(currentLogType, photoDataURL);
            } else {
                Swal.fire('Peringatan', 'Harap ambil foto terlebih dahulu.', 'warning');
            }
        });

        checkTimeBoundaries();
        setInterval(checkTimeBoundaries, 30000);
    });
</script>
<?php $pageScripts = ob_get_clean(); ?>