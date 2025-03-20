<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('admin_title') ?>
Product Details
<?= $this->endSection() ?>

<?= $this->section('admin_content') ?>
<div class="card">
  <div class="card-header">
    Product Details
  </div>
  <div class="card-body">
    <div class="w-100">
      <p>Name : <span class="fw-bold"> <?= $product->name; ?></span></p>
      <p>Price : <span class="fw-bold"> <?= $product->getFormattedPrice(); ?></span></p>
      <p>Category : <span class="fw-bold"> <?= $product->category_id; ?></span></p>
      <p>Stock : <span class="fw-bold"> <?= $product->stock; ?></span></p>
      <p>Status : <span class="fw-bold"> <?= $product->status; ?></span></p>
      <p>isNew Status :
        <?php if ($product->is_new == 't'): ?>
          <span class="badge rounded-pill bg-secondary">TRUE</span>
        <?php else: ?>
          <span class="badge rounded-pill bg-dark">FALSE</span>
        <?php endif; ?>
      </p>
      <p>isSale Status :
        <?php if ($product->is_sale == 't'): ?>
          <span class="badge rounded-pill bg-secondary">TRUE</span>
        <?php else: ?>
          <span class="badge rounded-pill bg-dark">FALSE</span>
        <?php endif; ?>
      </p>
      <p>Description : <span class="fw-bold"> <?= $product->description; ?></span></p>


      <div class="d-flex gap-2">
        <a href="/admin/product/on_update/<?= $product->product_id; ?>" class="btn btn-primary btn-sm">Edit</a>
        <form action="/admin/product/delete/<?= $product->product_id; ?>" method="get">
          <button class="btn btn-danger btn-sm">Delete</button>
        </form>
      </div>

    </div>
  </div>
</div>

<div>




</div>
<?= $this->endSection() ?>