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
    <?= $type ?> User Role
  </div>
  <div class="card-body">
    <form id="formData" action="/admin/user/<?= $action; ?>" method="post" style="display: grid; gap: 5px">
      <p>Before: <span class="fw-bold"><?= $userRole ?></span></p>
      <input name="user_id" id="user_id" value="<?= $user->id ?>" hidden />
      <div class="mb-3">
        <label for="group" class="form-label">Group</label>
        <select class="form-select <?= (session('errors.group')) ? 'is-invalid'
                                      : ''; ?>" id="group" name="group" required>
          <option value="" hidden>Choose Group</option>
          <?php foreach ($groups as $group) : ?>

            <?php $selected = false; ?>
            <?php foreach ($userGroups as $userGroup) : ?>
              <?php if ($userGroup['group_id'] == $group->id) : ?>
                <?php $selected = true;
                break; ?>
              <?php endif; ?>
            <?php endforeach; ?>
            <option value="<?= $group->id; ?>" <?= ($selected) ? 'selected'
                                                  : ''; ?>><?= $group->name; ?></option>
          <?php endforeach; ?>
        </select>
        <div class="invalid-feedback">
          <?= session('errors.group'); ?>
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