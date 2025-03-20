<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>
Product
<?= $this->endSection() ?>

<?= $this->section('admin_content') ?>
<?php if (session('errors')) : ?>
  <div class="alert alert-danger">
    <ul>
      <?php foreach (session('errors') as $error) : ?>
        <li><?= $error ?></li>
      <?php endforeach ?>
    </ul>
  </div>
<?php endif ?>

<div class="card">
  <div class="card-header">
    <?= $type ?> Product Form
  </div>
  <div class="card-body">
    <form id="formData" action="/admin/product/<?= $action; ?>" method="post" style="display: grid; gap: 5px">
      <input
        type="text"
        id="product_id"
        name="product_id"
        hidden
        value="<?= $product->product_id ?>"
        autofocus />

      <div class="row row-cols-2">
        <div class="col">
          <div class="form-element mb-2">
            <label for="name" class="form-label">Name</label>
            <input
              type="text"
              id="name"
              class="form-control"
              data-pristine-required
              data-pristine-required-message="Nama harus diisi!"
              value="<?= $product->name  ?>"
              name="name">
          </div>
          <div class="form-element mb-2">
            <label for="price" class="form-label">Price</label>
            <input
              type="number"
              id="price"
              class="form-control"
              value="<?= $product->price  ?>"
              data-pristine-required
              data-pristine-required-message="Price harus diisi!"
              data-pristine-min="0"
              data-pristine-min-message="Price harus lebih dari atau sama dengan 0(nol)!"
              name="price">
          </div>

        </div>
        <div class="col">
          <div class="form-element mb-2">
            <label for="stock" class="form-label">Stock</label>
            <input
              type="number"
              id="stock"
              class="form-control"
              data-pristine-required
              data-pristine-required-message="Stock harus diisi!"
              data-pristine-min="0"
              data-pristine-min-message="Price harus lebih dari atau sama dengan 0(nol)!"
              value="<?= $product->stock  ?>"
              name="stock">
          </div>
          <div class="mb-2">
            <label for="category_id" class="form-label">Category</label>
            <select class="form-select" name="category_id" id="category_id">
              <option value="" hidden>--Please choose an option--</option>
              <?php foreach ($categories as $row): ?>
                <option
                  value="<?= $row->category_id; ?>"
                  selected=<?php if ($row->category_id == $product->category_id): ?>
                  true
                  <?php else: ?> false
                  <?php endif ?>>
                  <?= $row->name ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="form-element mb-2">
          <label for="description" class="form-label">Description</label>
          <textarea
            class="form-control"
            id="description"
            name="description"
            rows="3"
            data-pristine-required
            data-pristine-required-message="Description harus diisi!"><?= $product->description  ?></textarea>
        </div>
      </div>
      <?php if ($type == "Update"): ?>
        <div class="row">
          <div class="col">
            <div class="mb-2">
              <label for="status" class="form-label">Status</label>
              <select class="form-select" name="status" id="status">
                <?php foreach (['active', 'inactive'] as $row): ?>
                  <option value="<?= $row ?>"><?= $row ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-2">
              <label for="status" class="form-label">isSale Status</label>
              <select class="form-select" name="is_sale" id="is_sale" value>
                <option value="t">TRUE</option>
                <option value="f">FALSE</option>
              </select>
            </div>
          </div>
          <div class="col">
            <div class="mb-2">
              <label for="status" class="form-label">isNew Status</label>
              <select class="form-select" name="is_new" id="is_new" value>
                <option value="t">TRUE</option>
                <option value="f">FALSE</option>
              </select>
            </div>
          </div>
        </div>
      <?php endif; ?>

      <button type=" submit" class="btn custom-primary mt-3">Save</button>
    </form>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  let pristine;
  window.onload = function() {
    let form = document.getElementById("formData");
    var pristine = new Pristine(form, {
      classTo: 'form-element',
      errorClass: 'is-invalid',
      successClass: 'is-valid',
      errorTextParent: 'form-element',
      errorTextTag: 'div',
      errorTextClass: 'text-danger'
    });

    form.addEventListener('submit', function(e) {
      var valid = pristine.validate();
      if (!valid) {
        e.preventDefault();
      }
    });

  };
</script>
<?= $this->endSection() ?>