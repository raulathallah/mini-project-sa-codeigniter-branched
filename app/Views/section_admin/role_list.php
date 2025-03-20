<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('admin_title') ?>
Role List
<?= $this->endSection() ?>

<?= $this->section('admin_content') ?>
<div class="card">
  <div class="card-header">
    Role List
  </div>
  <div class="card-body">
    <div class="mb-2">
      <a href="/admin/role/on_create"><button class="btn custom-primary ">Add Role</button></a>
    </div>
    <table class="table table-striped table-hover">
      <thead class="custom-header text-white">
        <th scope="col">ID</th>
        <th scope="col">Name</th>
        <th scope="col">Description</th>
        <th scope="col">Action</th>
      </thead>
      <tbody>
        <?php foreach ($roles as $row): ?>

          <tr class="align-middle">
            <td scope="row"><?= $row->id; ?></td>
            <td scope="row"><?= $row->name; ?></td>
            <td scope="row"><?= $row->description; ?></td>
            <td class="d-flex gap-2">
              <a href="/admin/role/on_update/<?= $row->id; ?>" class="btn btn-primary btn-sm">Edit</a>
              <form action="/admin/role/delete/<?= $row->id; ?>" method="get">
                <button class="btn btn-danger btn-sm">Delete</button>
              </form>
            </td>
          </tr>

        <?php endforeach; ?>
      </tbody>
    </table>
  </div>



</div>

<?= $this->endSection() ?>