<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Product Registration</title>
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
            <div>
                <div>
                    <p><?= esc($title) ?></p>
                </div>
                <p><img src="cid:product_image.jpg" alt="Product Image" style="max-width: 600px; height: auto;"></p>
                <ul>
                    <li>
                        ID: <?= esc($productId) ?>
                    </li>
                    <li>
                        Name: <?= esc($productName) ?>
                    </li>
                    <li>
                        Price: <?= esc($productPrice) ?>
                    </li>
                    <li>
                        Description: <?= esc($productDesc) ?>
                    </li>
                </ul>

                <a href="<?= $link ?>" style="color: blue;">Product Link</a>

                <div>
                    <p>Email ini dikirim otomatis. Mohon jangan membalas email ini.</p>
                    <p>&copy; <?= date('Y') ?> Nama Perusahaan Anda</p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>