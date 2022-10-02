<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Cart extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function Product() 
    {
        return $this->belongsTo(Product::class);
    }

    public function Order()
    {
        return $this->belongsTo(Order::class);
    }
}
