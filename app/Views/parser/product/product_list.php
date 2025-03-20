<div class="d-flex flex-wrap gap-3">
  {products}
  <div class="card" style="width: 20rem;">
    <img src="{image_path}" alt="{productName}" class="card-img-top" width="100" height="200">
    <div class="card-body">
      <h5 class="card-title">{productName}</h5>
      <h6 class="card-subtitle mb-2 text-muted"> Rp.{price}</h6>
      <span class="badge bg-secondary my-2">{categoryName}</span>
      <!-- STOCK -->
      <h6 class="mb-2">Stock : {stock}</h6>
      <p class="mb-2 text-muted" style="text-align: justify;">{created_at_format}</p>
      <p class="mb-2" style="text-align: justify;">{description}</p>
    </div>

  </div>
  {/products}
</div>