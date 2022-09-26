<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cart;
use App\Models\cart_product;

class Product extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function cart_product()
    {
        return $this->hasMany(cart_product::class);
    }
}
