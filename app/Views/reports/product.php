<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('admin_title') ?>
Products Reports
<?= $this->endSection() ?>

<?= $this->section('admin_content') ?>

<div>

  <div class="card mb-4">
    <div class="card-body">
      <form class="row" method="get" action="<?= site_url('admin/reports/product-report') ?>">
        <div class="col-md-6">

          <select class="form-control" name="categories" required>
            <option value="">Choose Category</option>
            <?php foreach ($categories as $row): ?>
              <option value="<?= $row ?>" <?= $params == $row ? 'selected' : '' ?>> <?= $row ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-6 d-flex align-items-end">
          <button type="submit" class="btn btn-primary me-2">Preview</button>
          <a href="<?= site_url('admin/reports/product-report') ?>" class="btn btn-secondary">Reset</a>
        </div>

      </form>
      <div class="d-flex my-3">

        <a href="<?= site_url('admin/reports/product-excel') . (!empty($params) ? '?params=' . $params : '') ?>" class="btn btn-success">

          <i class="bi bi-file-excel me-1"></i> Export Excel

        </a>

      </div>
    </div>

  </div>

  <div class="card">

    <div class="card-body">

      <div class="table-responsive">

        <table class="table table-striped table-bordered">

          <thead>

            <tr>

              <th>No</th>

              <th>Product Name</th>

              <th>Category</th>

              <th>Price</th>

              <th>Stock</th>

              <th>Created At</th>

            </tr>

          </thead>

          <tbody>

            <?php if (empty($products)): ?>

              <tr>

                <td colspan="10" class="text-center">Tidak ada data yang ditemukan</td>

              </tr>
            <?php else: ?>

              <?php $no = 1;
              foreach ($products as $row): ?>
                <tr>
                  <td><?= $no++ ?></td>
                  <td><?= $row->productName ?></td>
                  <td><?= $row->categoryName ?></td>
                  <td><?= $row->getFormattedPrice() ?></td>
                  <td><?= $row->stock ?></td>
                  <td><?= $row->created_at ?></td>
                </tr>

              <?php endforeach; ?>

            <?php endif; ?>

          </tbody>

        </table>

      </div>

    </div>

  </div>
</div>
<?= $this->endSection() ?>