<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>
User
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
    <?= $type ?> Role Form
  </div>
  <div class="card-body">
    <form id="formData" action="/admin/role/<?= $action; ?>" method="post" style="display: grid; gap: 5px">
      <input
        type="text"
        id="id"
        name="id"
        hidden
        value="<?= $role->id ?>"
        autofocus />

      <div class="row row-cols-2">
        <div class="col">
          <div class="form-element mb-2">
            <label for="name" class="form-label">Name</label>
            <input
              type="text"
              id="name"
              class="form-control"
              value="<?= $role->name ?>"
              name="name">
          </div>
        </div>
        <div class="col">
          <div class="form-element mb-2">
            <label for="name" class="form-label">Description</label>
            <input
              type="text"
              id="description"
              class="form-control"
              value="<?= $role->description ?>"
              name="description">
          </div>
        </div>
      </div>
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