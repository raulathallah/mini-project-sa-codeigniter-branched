<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class ProductImage extends Entity
{
    protected $datamap = [];

    protected $attributes = [
        'product_image_id'  => null,
        'product_id'        => null,
        'image_path'        => null,
        'is_primary'        => null,
        'created_at'        => null,
        'updated_at'        => null,
    ];

    protected $dates   = [
        'created_at',
        'updated_at',
    ];

    protected $casts   = [];
}
