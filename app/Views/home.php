<?= $this->extend('layouts/public_layout') ?>

<?= $this->section('title') ?>
Home
<?= $this->endSection(); ?>


<?= $this->section('content') ?>
<div class="d-flex gap-2 fw-bold fs-3">
    <p>Welcome!</p>
    <?= view_cell('UserProfileCell', ['user' => $user ?? '']) ?>
</div>
<?= $this->endSection(); ?>