<div class="page-content">
    <section class="row">
        <div class="col-12">

            <div id="loading-skeleton" class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 text-muted">Memuat data...</p>
            </div>

            <div id="about-content" class="card border-0 shadow-sm" style="display: none;">
                <div class="card-header bg-white d-flex justify-content-between align-items-center border-bottom py-3">
                    <h5 class="card-title m-0 text-primary"><i class="bi bi-info-circle me-2"></i>About Us Information</h5>
                    <a href="#" id="btn-edit" class="btn btn-warning btn-sm px-4">
                        <i class="bi bi-pencil-square"></i> Edit Data
                    </a>
                </div>

                <div class="card-body p-4">
                    <div class="m-4">
                        <div class="text-center mb-5">
                            <h2 class="fw-bold mb-3" id="view-title">...</h2>
                            <p class="lead text-dark mx-auto" id="view-desc" style="max-width: 800px;">...</p>
                        </div>
                    </div>

                    <div id="gallery-container" class="row justify-content-center g-3 mb-5">
                    </div>

                    <hr class="my-4 opacity-10">

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="p-4 h-100 rounded-3 bg-dark border">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center me-3"
                                        style="width: 40px; height: 40px; min-width: 40px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list-check" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M5 11.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5M3.854 2.146a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708L2 3.293l1.146-1.147a.5.5 0 0 1 .708 0m0 4a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708L2 7.293l1.146-1.147a.5.5 0 0 1 .708 0m0 4a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0" />
                                        </svg>
                                    </div>
                                    <h4 class="fw-bold m-0 text-primary">Visi</h4>
                                </div>
                                <p class="text-white mb-0 text-justify" id="view-vision" style="font-size: 1.1rem;">...</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-4 h-100 rounded-3 bg-dark border">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center me-3"
                                        style="width: 40px; height: 40px; min-width: 40px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z" />
                                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0" />
                                        </svg>
                                    </div>
                                    <h4 class="fw-bold m-0 text-primary">Misi</h4>
                                </div>
                                <p class="text-white mb-0 text-justify" id="view-mission"
                                    style="white-space: pre-line; font-size: 1.1rem;">...</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white text-muted text-center py-3">
                    <small>This data will be displayed on the website.</small>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
ob_start();
?>
<script>
    $(document).ready(function() {
        const baseUrl = "<?php echo base_url(); ?>";
        const apiUrl = "<?php echo base_url('admin/aboutus/data'); ?>";

        $.ajax({
            url: apiUrl,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                let data = null;

                if (response.data && response.data.length > 0) {
                    data = response.data[0];
                } else if (Array.isArray(response) && response.length > 0) {
                    data = response[0];
                }

                if (data) {
                    $('#view-title').text(data.title);
                    $('#view-desc').text(data.description);
                    $('#view-vision').text(data.vision);
                    $('#view-mission').text(data.mision);

                    let editUrl = baseUrl + 'admin/aboutus/' + data.id + '/edit';
                    $('#btn-edit').attr('href', editUrl);

                    $('#loading-skeleton').fadeOut(300, function() {
                        $('#about-content').fadeIn(300);
                    });

                    let imagesHtml = '';

                    if (data.images && data.images.length > 0) {
                        data.images.forEach(function(img) {

                            let imageUrl = img.image_name;

                            imagesHtml += `
                                <div class="col-md-4 col-sm-6">
                                    <div class="card border-0 shadow-sm h-100">
                                        <img src="${imageUrl}" class="card-img-top rounded-3" 
                                            alt="About Image" style="object-fit: cover; width: 100%; aspect-ratio: 5/4;">
                                        </div>
                                </div>
                            `;
                        });
                    } else {
                        imagesHtml = '<div class="col-12 text-center text-muted"><small>Tidak ada gambar tersedia.</small></div>';
                    }

                    $('#gallery-container').html(imagesHtml);
                } else {
                    $('#loading-skeleton').html('<div class="alert alert-warning">Data About Us belum tersedia.</div>');
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
                $('#loading-skeleton').html('<div class="alert alert-danger">Gagal mengambil data server.</div>');
            }
        });

        <?php if (isset($_SESSION['success_message'])): ?>
            var audio = new Audio("<?php echo base_url('assets/audio/success.wav'); ?>");
            audio.play().catch(function(error) {
                console.log("Audio play failed: " + error);
            });

            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '<?php echo $_SESSION['success_message']; ?>',
                showConfirmButton: false,
                timer: 1500
            });
        <?php
            unset($_SESSION['success_message']);
        endif;
        ?>
    });
</script>
<?php
$pageScripts = ob_get_clean();
?>