<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    const AVAILABLE_PRODUCTS = 'available';
    const UNAVAILABLE_PRODUCTS = 'unavailable';

    protected $fillable = [
        'name',
        'description',
        'quantity',
        'status',
        'image',
        'seller_id',
    ];

    public function isAvailable()
    {
        return $this->status === self::AVAILABLE_PRODUCTS;
    }

    public function isUnAvailable()
    {
        return $this->status === self::UNAVAILABLE_PRODUCTS;
    }
}
