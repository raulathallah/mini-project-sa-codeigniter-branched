<nav class="navbar navbar-light custom-header">
  <div class="container-fluid">
    <div class="d-flex">
      <a class="navbar-brand text-white fw-bold" href="/">Online Food Ordering System</a>

      <?php if (logged_in()) : ?>
        <a class="nav-link text-white" aria-current="page" href="/product">Product Catalogue</a>

        <?php if (in_groups('administrator') || in_groups('product_manager')) : ?>
          <a class="nav-link text-white" aria-current="page" href="/admin/dashboard">Dashboard</a>
        <?php endif; ?>
        <?php if (!in_groups('administrator')) : ?>
          <a class="nav-link text-white" aria-current="page" href="/profile">Profile</a>
        <?php endif; ?>
      <?php endif; ?>
      <!--  
      <span class="d-flex gap-2" style="align-items: center;">
        <form action="/login" method="post">
          <button type="submit">Login</button>
        </form>
        <form action="/logout" method="post">
          <button type="submit">Logout</button>
        </form>
      </span>
      <?= view_cell('UserLoginCell'); ?>
      -->

    </div>

    <div class="d-flex">

      <?php if (logged_in()) : ?>

        <a class="nav-link text-white" href="/logout">
          <h5>Logout</h5>
        </a>

      <?php else : ?>

        <a class="nav-link text-white" href="/login">
          <h5>Login</h5>
        </a>

      <?php endif; ?>

    </div>



  </div>
</nav>