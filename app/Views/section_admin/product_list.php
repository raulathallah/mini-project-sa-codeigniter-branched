<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('admin_title') ?>
Product List
<?= $this->endSection() ?>

<?= $this->section('admin_content') ?>
<div class="card">
  <div class="card-header">
    Product List
  </div>
  <div class="card-body">
    <div class="mb-2">
      <a href="/admin/product/on_create"><button class="btn custom-primary ">Add Product</button></a>
    </div>
    <form action="<?= $baseUrl ?>" method="get" class="form-inline">
      <div class="row mb-4">
        <div class="col-md-4">
          <div class="input-group mr-2">
            <input type="text" class="form-control" name="search"
              value="<?= $params->search ?>" placeholder="Search...">
            <div class="input-group-append">
              <button class="btn custom-secondary" type="submit">Search</button>
            </div>
          </div>
        </div>

        <div class="col-md-2">
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

        <div class="col-md-2">
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

        <div class="col-md-2">
          <div class="input-group ml-2">
            <select name="perPage" class="form-control" onchange="this.form.submit()">
              <option value="5" <?= ($params->perPage == 5) ? 'selected' : '' ?>>
                5 per halaman
              </option>
              <option value="10" <?= ($params->perPage == 10) ? 'selected' : '' ?>>
                10 per halaman
              </option>
              <option value="20" <?= ($params->perPage == 20) ? 'selected' : '' ?>>
                20 per halaman
              </option>
            </select>
          </div>
        </div>
        <div class="col-md-1">
          <a href="<?= $params->getResetUrl($baseUrl) ?>" class="btn custom-secondary ml-2">
            Reset
          </a>
        </div>



        <input type="hidden" name="sort" value="<?= $params->sort; ?>">
        <input type="hidden" name="order" value="<?= $params->order; ?>">

    </form>
  </div>
  <table class="table table-striped table-hover">
    <thead class="custom-header text-white">
      <th scope="col">ID</th>
      <th scope="col"><a class="text-decoration-none text-white" href="<?= $params->getSortUrl('products.name', $baseUrl) ?>">
          Name <?= $params->isSortedBy('products.name') ? ($params->getSortDirection() == 'asc' ?
                  '↑' : '↓') : '↕' ?>
        </a></th>
      <th scope="col">Description</th>
      <th scope="col"><a class="text-decoration-none text-white" href="<?= $params->getSortUrl('price', $baseUrl) ?>">
          Price <?= $params->isSortedBy('price') ? ($params->getSortDirection() == 'asc' ?
                  '↑' : '↓') : '↕' ?>
        </a></th>
      <th scope="col">Category</th>
      <th scope="col">Product Status</th>
      <th scope="col">isNew Status</th>
      <th scope="col">isSale Status</th>
      <th scope="col">Action</th>
    </thead>
    <tbody>
      <?php foreach ($products as $row): ?>

        <tr class="align-middle">
          <td scope="row"><?= $row->id; ?></td>
          <td><?= $row->productName; ?></td>
          <td><?= $row->description; ?></td>
          <td><?= $row->getFormattedPrice(); ?></td>
          <td><?= $row->categoryName; ?></td>
          <td><?= $row->status; ?></td>
          <td>
            <?php if ($row->is_new == 't'): ?>
              <span class="badge rounded-pill bg-secondary">TRUE</span>
            <?php else: ?>
              <span class="badge rounded-pill bg-dark">FALSE</span>
            <?php endif; ?>
          </td>
          <td>
            <?php if ($row->is_sale == 't'): ?>
              <span class="badge rounded-pill bg-secondary">TRUE</span>
            <?php else: ?>
              <span class="badge rounded-pill bg-dark">FALSE</span>
            <?php endif; ?>
          </td>
          <td class="d-flex gap-2">
            <a href="/admin/product/detail/<?= $row->id; ?>" class="btn btn-dark btn-sm">Detail</a>
            <a href="/admin/product/add_photo/<?= $row->id; ?>" class="btn btn-info btn-sm">Add Photo</a>
            <a href="/admin/product/on_update/<?= $row->id; ?>" class="btn btn-primary btn-sm">Edit</a>
            <form action="/admin/product/delete/<?= $row->id; ?>" method="get">
              <button class="btn btn-danger btn-sm">Delete</button>
            </form>
          </td>
        </tr>

      <?php endforeach; ?>
    </tbody>
  </table>
  <?= $pager->links('products', 'custom_pager') ?>

</div>

<?= $this->endSection() ?>