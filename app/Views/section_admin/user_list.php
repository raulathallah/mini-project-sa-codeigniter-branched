<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('admin_title') ?>
User List
<?= $this->endSection() ?>

<?= $this->section('admin_content') ?>
<div class="card">
  <div class="card-header">
    User List
  </div>
  <div class="card-body">
    <div class="mb-2">
      <a href="/admin/user/on_create"><button class="btn custom-primary">Add User</button></a>
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
            <select name="role" class="form-control" onchange="this.form.submit()">
              <option value="">All Role</option>
              <?php foreach ($role as $row): ?>
                <option value="<?= $row ?>" <?= ($params->role == $row) ? 'selected' : '' ?>><?= ucfirst($row) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="col-md-2">
          <div class="input-group ml-2">
            <select name="status" class="form-control" onchange="this.form.submit()">
              <option value="">All Status</option>
              <?php foreach ($status as $row): ?>
                <option value="<?= $row ?>" <?= ($params->status == $row) ? 'selected' : '' ?>><?= ucfirst($row) ?></option>
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
      <th scope="col">Name</th>
      <th scope="col"><a class="text-decoration-none text-white" href="<?= $params->getSortUrl('username', $baseUrl) ?>">
          Username <?= $params->isSortedBy('username') ? ($params->getSortDirection() == 'asc' ?
                      '↑' : '↓') : '↕' ?>
        </a></th>
      <th scope="col"><a class="text-decoration-none text-white" href="<?= $params->getSortUrl('email', $baseUrl) ?>">
          Email <?= $params->isSortedBy('email') ? ($params->getSortDirection() == 'asc' ?
                  '↑' : '↓') : '↕' ?>
        </a></th>
      <th scope="col">Role</th>
      <th scope="col">Status</th>
      <th scope="col"><a class="text-decoration-none text-white" href="<?= $params->getSortUrl('last_login', $baseUrl) ?>">
          Last login <?= $params->isSortedBy('last_login') ? ($params->getSortDirection() == 'asc' ?
                        '↑' : '↓') : '↕' ?>
        </a></th>
      <th scope="col">Action</th>
    </thead>
    <tbody>
      <?php foreach ($accounts as $row): ?>
        <tr class="align-middle">
          <td scope="row"><?= $row->account_id; ?></td>
          <td scope="row"><?= $row->full_name; ?></td>
          <td scope="row"><?= $row->username; ?></td>
          <td scope="row"><?= $row->email; ?></td>
          <td>
            <?php
            $groupModel = new \Myth\Auth\Models\GroupModel();
            $groups = $groupModel->getGroupsForUser($row->user_id);
            foreach ($groups as $group) {
              echo '<span class="badge bg-info me-1">' . $group['name'] . '</span>';
            }
            ?>
          </td>
          <td scope="row"><?= $row->status; ?></td>
          <td scope="row"><?= $row->getFormattedLastLogin(); ?></td>
          <td class="d-flex gap-2">
            <a href="/admin/user/detail/<?= $row->user_id; ?>" class="btn btn-dark btn-sm">Detail</a>
            <a href="/admin/user/on_update/<?= $row->account_id; ?>" class="btn btn-primary btn-sm">Edit</a>
            <a href="/admin/user/on_update_role/<?= $row->user_id; ?>" class="btn btn-info btn-sm">Change Role</a>
            <form action="/admin/user/delete/<?= $row->account_id; ?>" method="get">
              <button class="btn btn-danger btn-sm">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?= $pager->links('accounts', 'custom_pager') ?>
</div>

<?= $this->endSection() ?>