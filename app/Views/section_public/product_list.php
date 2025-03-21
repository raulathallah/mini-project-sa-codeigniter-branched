<?= $this->extend('layouts/public_layout') ?>

<?= $this->section('title') ?>
Product
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div>
  <form action="<?= $baseUrl ?>" method="get" class="form-inline mb-4">
    <div class="row mb-2">
      <div class="col-md-4">
        <div class="input-group mr-2">
          <input type="text" class="form-control" name="search"
            value="<?= $params->search ?>" placeholder="Search name or category...">
          <div class="input-group-append">
            <button class="btn custom-secondary" type="submit">Search</button>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="input-group ml-2">
          <select name="price_range" class="form-control" onchange="this.form.submit()">
            <option value="">All Price</option>
            <?php foreach ($price_range as $row): ?>
              <option value="<?= $row ?>" <?= (urldecode($params->price_range) == $row) ? 'selected' : '' ?>>

                <?php
                // Split the row by comma
                $price_parts = explode(',', $row);

                // Check if both parts are numeric
                $price1 = is_numeric($price_parts[0]) ? number_format($price_parts[0]) : $price_parts[0];
                $price2 = isset($price_parts[1]) && is_numeric($price_parts[1]) ? number_format($price_parts[1]) : $price_parts[1];

                // If the second part is 'unlimited', handle it differently
                if (strtolower($price2) == 'unlimited') {
                  $price2 = 'Unlimited';
                }

                echo 'Rp' . $price1 . ' - Rp' . $price2;
                ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="col">
        <div class="input-group ml-2">
          <select name="categories" class="form-control" onchange="this.form.submit()">
            <option value="">All Categories</option>
            <?php foreach ($categories as $row): ?>
              <option value="<?= $row ?>"
                <?= ($params->categories == $row) ? 'selected' : '' ?>><?= ucfirst($row);  ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="col">
        <div class="input-group ml-2">
          <select name="sort" class="form-control" onchange="this.form.submit()">
            <option value="">Sort By</option>
            <option value="name" <?= ($params->sort == 'name') ? 'selected' : '' ?>>Name</option>
            <option value="price" <?= ($params->sort == 'price') ? 'selected' : '' ?>>Price</option>
            <option value="created_at" <?= ($params->sort == 'created_at') ? 'selected' : '' ?>>Date</option>
          </select>
        </div>
      </div>

      <div class="col">
        <div class="input-group ml-2">
          <select name="order" class="form-control" onchange="this.form.submit()">
            <option value="asc" <?= ($params->order == 'asc') ? 'selected' : '' ?>>Ascending</option>
            <option value="desc" <?= ($params->order == 'desc') ? 'selected' : '' ?>>Descending</option>
          </select>
        </div>
      </div>

      <div class="col">
        <a href="<?= $params->getResetUrl($baseUrl) ?>" class="btn custom-secondary ml-2">
          Reset
        </a>
      </div>
    </div>

  </form>
  <div class="d-flex flex-wrap gap-3">
    <?php foreach ($products as $row): ?>
      <div class="card" style="width: 20rem;">
        <img src="<?= base_url('productImage/' . $row->id . '/' . $row->image_path) ?>"
          alt="<?= esc($row->image_path) ?>" class="w-20 h-20">
        <div class="card-body">
          <h5 class="card-title"><?= $row->productName ?></h5>
          <h6 class="card-subtitle mb-2 text-muted"><?= $row->getFormattedPrice() ?></h6>
          <span class="badge bg-secondary my-2"><?= $row->categoryName ?></span>
          <?php if ($row->is_sale == 't'): ?>
            <span class="badge bg-danger my-2">On Sale!</span>
          <?php endif; ?>
          <?php if ($row->is_new == 't'): ?>
            <span class="badge bg-success my-2">New!</span>
          <?php endif; ?>
          <!-- STOCK -->
          <h6 class="mb-2">Stock : <?= $row->stock ?></h6>
          <p class="mb-2 text-muted" style="text-align: justify;"><?= $row->created_at_format ?></p>
          <p class="mb-2" style="text-align: justify;"><?= $row->description ?></p>
        </div>

      </div>
    <?php endforeach; ?>
  </div>
</div>


<?= $this->endSection() ?>