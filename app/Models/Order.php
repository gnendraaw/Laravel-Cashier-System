<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cart;

class Order extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function Cart()
    {
        return $this->hasMany(Cart::class);
    }
}
