<?= $this->extend('layouts/public_layout') ?>

<?= $this->section('title') ?>
<?= $this->renderSection('admin_title') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Main Content -->
<div class="d-flex h-100">
    <?= $this->include('partials/sidebar') ?>
    <div class="mx-4 w-100">
        <?= $this->renderSection('admin_content') ?>
    </div>
</div>
<?= $this->endSection() ?>