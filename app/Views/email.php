<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Email Add Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
        }

        .header {
            background-color: #f5f5f5;
            padding: 10px;
            text-align: center;
        }

        .content {
            padding: 20px;
        }

        .footer {
            background-color: #f5f5f5;
            padding: 10px;
            text-align: center;
            font-size: 12px;
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
                    <strong><?= $title ?></strong>
                </div>
                <h5>Product ID: <?= $productId ?></h5>
                <h5>Product Name: <?= $productName ?></h5>
                <h5>Product Price: <?= $productPrice ?></h5>
                <h5>Product Description: <?= $productDesc ?></h5>
            </div>
            <div>
                <p>Email ini dikirim otomatis. Mohon jangan membalas email ini.</p>
                <p>&copy; <?= date('Y') ?> Nama Perusahaan Anda</p>
            </div>
        </div>
    </div>
</body>

</html>