<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <!-- Option 1: Include in HTML -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
  <script src="<?= base_url('assets/js/pristine.js') ?>"></script>

  <title><?= $this->renderSection('title') ?></title>

  <style>
    .nav-link:hover {
      text-decoration: underline;
    }

    .custom-header {
      background-color: #2c3e50;
    }

    .custom-secondary {
      background-color: #e6e6fa;
      /* Light blue (Bootstrap info accent) */
      color: #2c3e50;
      /* Dark teal text */
    }

    .custom-primary {
      background-color: #2c3e50;
      /* Teal (Bootstrap success accent) */
      color: white;
    }

    .custom-primary:hover,
    .custom-secondary:hover {
      background-color: #bdaef7;
      color: white;
    }


    /* Change the background color of the active page item */
    .page-item.active .page-link {
      background-color: #e6e6fa;
      /* Set your custom active color */
      border-color: #e6e6fa;
      /* Optional: match the border color to the background */
      color: #2c3e50;
      /* Text color inside active page item */
    }

    .page-link {
      color: #2c3e50;
    }
  </style>
</head>

<body class="d-flex flex-column h-100">
  <!-- Header -->
  <header class="">
    <?= $this->include('partials/header') ?>
  </header>

  <!-- Main Content -->
  <div class="h-100 p-3">
    <?= $this->renderSection('content') ?>
  </div>
  <!-- Footer -->
  <?= $this->include('partials/footer') ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <?= $this->renderSection('scripts') ?>

</body>

</html>