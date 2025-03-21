<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>
Add Product Photo
<?= $this->endSection() ?>

<?= $this->section('admin_content') ?>


<div class="card">
  <div class="card-header fs-4">
    Upload Photo
  </div>
  <div class="card-body">

    <div class="my-2">

      <?php if ($errors): ?>
        <?php foreach ($errors as $error): ?>
          <span style="color: red;"><?= esc($error) ?></span>
        <?php endforeach ?>
      <?php endif; ?>
    </div>


    <?= form_open_multipart('/admin/product/additional_photo', ['id' => 'formData', 'class' =>
    'pristine-validate']) ?>
    <div class="form-group d-flex flex-column gap-4">

      <input type="text" name="product_id" id="product_id" value="<?= $product_id; ?>"
        hidden />

      <div class="d-flex flex-column">
        <label for="userFile">Choose Photo (.jpg, .jpeg, .png, .webp - Max 5MB):</label>
        <input type="file" name="userfile" id="userfile" required
          data-pristine-required-message="Silakan pilih file untuk diunggah" accept=".jpg, .jpeg, .png, .webp" />

        <div id="file-type-error" class="text-danger mt-2" style="display: none;">
          File harus berupa .jpg .jpef .png .webp (Max 5MB)
        </div>
        <div id="file-size-error" class="text-danger mt-2" style="display: none;">
          Ukuran file tidak boleh melebihi 5MB
        </div>
      </div>

      <div id="image-preview-container" style="display:none;"></div>

    </div>
    <button type="submit" class="my-4 custom-primary btn">Upload</button>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    var form = document.getElementById("formData");
    var fileInput = document.getElementById('userfile');
    var fileTypeError = document.getElementById('file-type-error');
    var fileSizeError = document.getElementById('file-size-error');
    var imagePreviewContainer = document.getElementById('image-preview-container');
    var maxSize = 5 * 1024 * 1024;
    var allowedExtensions = ['.jpeg', '.jpg', '.png', '.webp'];

    fileInput.addEventListener('change', function() {
      fileTypeError.style.display = 'none';
      fileSizeError.style.display = 'none';
      imagePreviewContainer.style.display = 'none';

      if (fileInput.files.length === 0) {
        return;
      }

      var file = fileInput.files[0]; // Get the first file
      var fileName = file.name.toLowerCase();
      var validExtension = allowedExtensions.some(function(ext) {
        return fileName.endsWith(ext);
      });

      if (!validExtension) {
        fileTypeError.style.display = 'block';
        fileSizeError.style.display = 'none';
        return;
      }

      if (file.size > maxSize) {
        fileSizeError.style.display = 'block';
        fileTypeError.style.display = 'none';
        return;
      }

      var reader = new FileReader();

      reader.onload = function(e) {
        var img = document.createElement('img');
        img.src = e.target.result;
        img.style.width = "auto";
        img.style.height = "250px";
        imagePreviewContainer.innerHTML = "";
        imagePreviewContainer.appendChild(img);
        imagePreviewContainer.style.display = 'block';
      }

      reader.readAsDataURL(file);
    });

    var pristine = new Pristine(form);
    form.addEventListener('submit', function(e) {
      var valid = pristine.validate();

      if (!valid) {
        e.preventDefault();
      }
    });
  });
</script>
</script>
<?= $this->endSection() ?>