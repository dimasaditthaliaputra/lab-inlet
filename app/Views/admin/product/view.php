<?php
$product = $data['product'] ?? null;

$features = [];
$specifications = [];

if ($product) {
    if ($product->feature) {
        $decoded = json_decode($product->feature, true);
        if (is_array($decoded)) {
            $features = $decoded;
        }
    }

    if ($product->specification) {
        $decoded = json_decode($product->specification, true);
        if (is_array($decoded)) {
            $specifications = $decoded;
        }
    }
}
?>

<?php
ob_start();
?>
<style>
    .img-fluid.rounded {
        border: 3px solid #f0f0f0;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .img-fluid.rounded:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 16px rgba(0,0,0,0.2);
    }
    
    .card {
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: transform 0.2s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    .list-group-item {
        padding: 0.75rem 0;
        font-size: 0.95rem;
    }
    
    .table-borderless td {
        padding: 0.5rem 0.75rem;
        vertical-align: top;
    }
    
    .badge {
        font-size: 0.85rem;
        padding: 0.5rem 0.75rem;
    }
</style>
<?php
$pageStyle = ob_get_clean();
echo $pageStyle;
?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Product Detail</h3>
                <p class="text-subtitle text-muted">View product information.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/product') ?>">Product</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="row">
        <div class="col-12">
            <?php if ($product): ?>
                <!-- Product Header Card -->
                <div class="card border mb-4">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Product Information</h4>
                            <span class="badge bg-primary">
                                <i class="fas fa-box"></i> Product
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Product Image -->
                            <div class="col-md-4 text-center mb-4 mb-md-0">
                                <?php if (!empty($product->image_name)): ?>
                                    <img src="<?= base_url('uploads/product/' . $product->image_name) ?>" 
                                         alt="<?= htmlspecialchars($product->product_name) ?>" 
                                         class="img-fluid rounded shadow-sm" 
                                         style="max-height: 350px; width: auto; cursor: pointer;"
                                         data-bs-toggle="modal" 
                                         data-bs-target="#imageModal">
                                <?php else: ?>
                                    <div class="alert alert-light" role="alert">
                                        <i class="fas fa-image fa-4x text-muted"></i>
                                        <p class="mt-3 mb-0">No image available</p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Product Info -->
                            <div class="col-md-8">
                                <h2 class="text-primary mb-3"><?= htmlspecialchars($product->product_name) ?></h2>
                                
                                <div class="mb-4">
                                    <h5 class="mb-2">
                                        <i class="fas fa-align-left text-info"></i> Description
                                    </h5>
                                    <p class="text-muted" style="text-align: justify; line-height: 1.8;">
                                        <?= $product->description ? nl2br(htmlspecialchars($product->description)) : '<em class="text-muted">No description available</em>' ?>
                                    </p>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <small class="text-muted d-block mb-1">
                                                <i class="fas fa-calendar-alt"></i> Release Date
                                            </small>
                                            <h6 class="mb-0">
                                                <?= $product->release_date ? date('d F Y', strtotime($product->release_date)) : '-' ?>
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <small class="text-muted d-block mb-1">
                                                <i class="fas fa-box"></i> Product ID
                                            </small>
                                            <h6 class="mb-0">#<?= str_pad($product->id, 5, '0', STR_PAD_LEFT) ?></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Features & Specifications -->
                <div class="row">
                    <!-- Features Card -->
                    <div class="col-md-6 mb-4">
                        <div class="card border h-100">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-star text-warning"></i> Features
                                </h5>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($features)): ?>
                                    <ul class="list-group list-group-flush">
                                        <?php foreach ($features as $feature): ?>
                                            <li class="list-group-item border-0 px-0">
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                <?= htmlspecialchars($feature) ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <div class="alert alert-light mb-0" role="alert">
                                        <i class="fas fa-info-circle"></i> No features listed
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Specifications Card -->
                    <div class="col-md-6 mb-4">
                        <div class="card border h-100">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-cog text-primary"></i> Specifications
                                </h5>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($specifications)): ?>
                                    <table class="table table-borderless table-sm mb-0">
                                        <tbody>
                                            <?php foreach ($specifications as $specName => $specValue): ?>
                                                <tr>
                                                    <td width="45%" class="fw-bold text-muted">
                                                        <?= htmlspecialchars($specName) ?>
                                                    </td>
                                                    <td width="5%" class="text-center">:</td>
                                                    <td width="50%">
                                                        <?= htmlspecialchars($specValue) ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php else: ?>
                                    <div class="alert alert-light mb-0" role="alert">
                                        <i class="fas fa-info-circle"></i> No specifications listed
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card border">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <a href="<?= base_url('admin/product') ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                            <a href="<?= base_url('admin/product/' . $product->id . '/edit') ?>" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Edit Product
                            </a>
                        </div>
                    </div>
                </div>

            <?php else: ?>
                <div class="card border">
                    <div class="card-body">
                        <div class="alert alert-warning mb-0" role="alert">
                            <i class="fas fa-exclamation-triangle"></i> Product not found.
                        </div>
                        <div class="mt-3">
                            <a href="<?= base_url('admin/product') ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Modal for Image Preview -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Product Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-0">
                    <?php if (!empty($product->image_name)): ?>
                        <img src="<?= base_url('uploads/product/' . $product->image_name) ?>" 
                             class="img-fluid" 
                             alt="<?= htmlspecialchars($product->product_name) ?>">
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
ob_start();
?>
<script>
    $(document).ready(function() {
        
    });
</script>
<?php
$pageScripts = ob_get_clean();
?>