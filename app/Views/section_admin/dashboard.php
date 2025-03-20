<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('admin_title') ?>
Dashboard Admin
<?= $this->endSection() ?>

<?= $this->section('admin_content') ?>
<div>
  <?= $content ?? '' ?>
</div>
<?= $this->endSection() ?>