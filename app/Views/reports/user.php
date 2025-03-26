<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('admin_title') ?>
Users Report
<?= $this->endSection() ?>

<?= $this->section('admin_content') ?>
<div class="card-body">
  <h4 class="mb-4"><?= $title ?></h4>
  <form class="row" action="<?= base_url('admin/reports/user-pdf') ?>"
    method="post" target="_blank">
    <div class="col-md-6 d-flex align-items-end">
      <button type="submit" class="btn btn-primary me-2">
        Generate Report</button>
    </div>
  </form>
</div>
<?= $this->endSection() ?>