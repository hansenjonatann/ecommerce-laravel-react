<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = ['product_name', 'product_price', 'product_description', 'product_image', 'product_category'];

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn ($image) => asset('/storage/products/'. $image),
        );
    }
}
