<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Cart extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function Products() 
    {
        return $this->belongsToMany(Product::class);
    }
}
