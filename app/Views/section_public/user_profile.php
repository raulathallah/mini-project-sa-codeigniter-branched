<?php if (!in_groups('customer')): ?>

  <?= $this->extend('layouts/admin_layout') ?>

<?php else: ?>

  <?= $this->extend('layouts/public_layout') ?>

<?php endif; ?>

<?= $this->section('title') ?>
User Profile
<?= $this->endSection() ?>


<?php if (!in_groups('customer')): ?>

  <?= $this->section('admin_content') ?>


<?php else: ?>

  <?= $this->section('content') ?>

<?php endif; ?>
<?= $content ?? '' ?>
<?= $this->endSection() ?>