<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\cart_product;
use App\Models\Cart;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::where('isUsed', 0)->first();
        $cartProduct = cart_product::where('cart_id', $cart->id)->get();

        return view('cart.index', [
            'title' => 'Cart',
            'cartProduct' => $cartProduct,
        ]);
    }
}
