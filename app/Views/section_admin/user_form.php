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
    <?= $type ?> User Form
  </div>
  <div class="card-body">
    <form id="formData" action="/admin/user/<?= $action; ?>" method="post" style="display: grid; gap: 5px">
      <input
        type="text"
        id="user_id"
        name="user_id"
        hidden
        value="<?= $user->user_id ?>"
        autofocus />

      <div class="row row-cols-2">
        <div class="col">
          <div class="form-element mb-2">
            <label for="name" class="form-label">Username</label>
            <input
              type="text"
              id="username"
              class="form-control"
              data-pristine-required
              data-pristine-required-message="Username harus diisi!"
              data-pristine-minlength="3"
              data-pristine-minlength-message="Username minimal 3 karakter!"
              value="<?= $user->username  ?>"
              name="username">
          </div>
        </div>
        <div class="col">
          <div class="form-element mb-2"> <label for="password" class="form-label">Password</label>
            <input
              type="password"
              id="password"
              class="form-control"
              data-pristine-required
              data-pristine-required-message="Password harus diisi!"
              data-pristine-minlength="8"
              data-pristine-minlength-message="Password minimal 8 karakter!"
              value="<?= $user->password  ?>"
              name="password">
          </div>
        </div>
        <div class="col">
          <div class="form-element mb-2">
            <label for="full_name" class="form-label">Full Name</label>
            <input
              type="text"
              id="full_name"
              class="form-control"
              data-pristine-required
              data-pristine-required-message="Full name harus diisi!"
              value="<?= $user->full_name  ?>"
              name="full_name">
          </div>
        </div>
        <div class="col">
          <div class="form-element mb-2">
            <label for="email" class="form-label">Email</label>
            <input
              type="email"
              id="email"
              class="form-control"
              data-pristine-required
              data-pristine-required-message="Email harus diisi!"
              data-pristine-email-message="Email harus valid!"
              value="<?= $user->email  ?>"
              name="email">
          </div>
        </div>
        <div class="col">
          <div class="form-element mb-2">
            <label for="role" class="form-label">Role</label>
            <select class="form-select" name="role" id="role">
              <?php foreach (['admin', 'member'] as $row): ?>
                <option value="<?= $row ?>"><?= $row ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <?php if ($type == "Update"): ?>
          <div class="col">
            <div class="mb-2">
              <label for="status" class="form-label">Status</label>
              <select class="form-select" name="status" id="status">
                <?php foreach (['active', 'inactive'] as $row): ?>
                  <option value="<?= $row ?>"><?= $row ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        <?php endif; ?>
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