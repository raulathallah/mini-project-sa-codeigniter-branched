<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Category extends Entity
{
    protected $datamap = [];

    protected $attributes = [
        'category_id'   => null,
        'name'          => null,
        'description'   => null,
        'status'        => null,
        'created_at'    => null,
        'updated_at'    => null,
    ];

    protected $dates   = [
        'created_at',
        'updated_at',
    ];

    protected $casts   = [
        'category_id' => 'integer'
    ];
}
