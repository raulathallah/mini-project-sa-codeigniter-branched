<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Account Activation</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      line-height: 1.6;
      color: #333;
    }
  </style>
</head>

<body>
  <div>
    <div>
      <h1>Pesan untuk Anda</h1>
    </div>
    <div>
      <p>Terima kasih telah membaca email ini.</p>
      <p>Akun berhasil terdaftar, lakukan aktivasi terlebih dahulu sebelum menggunakan akun.</p>

      <p><a style="color: blue;" href="<?= url_to('activate-account') . '?token=' . $hash ?>">Click to activate account</a>.</p>

      <div>
        <p>Email ini dikirim otomatis. Mohon jangan membalas email ini.</p>
        <p>&copy; <?= date('Y') ?> Nama Perusahaan Anda</p>
      </div>
    </div>
  </div>
</body>

</html>