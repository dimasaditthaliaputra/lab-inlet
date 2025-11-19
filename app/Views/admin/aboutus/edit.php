<div class="page-heading">
    <h3>Detail About Us</h3>
</div>

<div class="page-content">
    <section class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <form action="<?= base_url('admin/aboutus/' . $about->id) ?>" method="POST">
                        <input type="hidden" name="_method" value="PUT">

                        <div class="mb-3">
                            <label>Title</label>
                            <input class="form-control" type="text" name="title" value="<?= $about->title ?>">
                        </div>

                        <div class="mb-3">
                            <label>Description</label>
                            <input class="form-control" type="text" name="description" value="<?= $about->description ?>">
                        </div>

                        <div class="mb-3">
                            <label>Vision</label>
                            <input class="form-control" type="text" name="vision" value="<?= $about->vision ?>">
                        </div>

                        <div class="mb-3">
                            <label>Mission</label>
                            <input class="form-control" type="text" name="mission" value="<?= $about->mission ?>">
                        </div>

                        <button class="btn btn-primary">Update</button>
                        <a href="<?= base_url('admin/aboutus') ?>" class="btn btn-secondary">Kembali</a>
                    </form>

                </div>
            </div>  
        </div>
    </section>
</div>
