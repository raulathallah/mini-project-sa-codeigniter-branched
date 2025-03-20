<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Product extends Entity
{
    protected $datamap = [];

    protected $attributes = [
        'product_id'    => null,
        'name'          => null,
        'description'   => null,
        'price'         => null,
        'stock'         => null,
        'category_id'   => null,
        'status'        => null,
        'is_new'        => null,
        'is_sale'       => null,
        'created_at'    => null,
        'updated_at'    => null,
        'deleted_at'    => null,
        'created_at_format' => null,
    ];

    protected $dates   = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts   = [
        'product_id'    => 'integer',
        'price'         => 'integer',
        'stock'         => 'integer'
    ];


    public function getFormattedPrice()
    {
        // Check if the price is numeric and greater than 0
        if (is_numeric($this->price) && $this->price > 0) {
            return 'Rp' . number_format($this->price, 0, ',', '.'); // Format the price as expected
        } else {
            return 'Invalid price or zero'; // Handle zero or invalid prices
        }
    }

    public function isInStock()
    {
        if ($this->attributes['stock'] <= 0) {
            return false;
        }

        return true;
    }

    public function getStatus()
    {
        return $this->attributes['status'];
    }

    public function isSale()
    {
        if (!$this->attributes['is_sale']) {
            return false;
        }

        return true;
    }
}
