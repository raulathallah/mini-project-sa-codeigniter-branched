<div class="mh-100" style="width: 10%;">
  <ul class="list-group list-group-flush">
    <li class="list-group-item text-center">
      <h6>Menus</h6>
    </li>
    <li class="list-group-item"><a class="btn custom-secondary btn-sm w-100" href="/admin/dashboard">Dashboard</a></li>

    <?php if (in_groups('administrator')): ?>
      <li class="list-group-item"><a class="btn custom-secondary btn-sm w-100" href="/admin/user">Users</a></li>
      <li class="list-group-item"><a class="btn custom-secondary btn-sm w-100" href="/admin/product">Products</a></li>
      <li class="list-group-item"><a class="btn custom-secondary btn-sm w-100" href="/admin/role">Roles</a></li>
    <?php endif; ?>

    <?php if (!in_groups('administrator')): ?>
      <li class="list-group-item"><a class="btn custom-secondary btn-sm w-100" href="/profile">Profile</a></li>
    <?php endif; ?>

    <?php if (in_groups('product_manager')): ?>
      <li class="list-group-item"><a class="btn custom-secondary btn-sm w-100" href="/admin/product">Products</a></li>
    <?php endif; ?>
  </ul>
</div>