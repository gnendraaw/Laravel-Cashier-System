<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\cart_product;
use App\Models\Cart;

class CartProductController extends Controller
{
    public function addToCart(Request $request)
    {
        $cart = Cart::where('isUsed', 0)->first();

        $cartProduct = new cart_product;
        $cartProduct->cart_id = $cart->id;
        $cartProduct->product_id = $request->id;
        $cartProduct->qty = $request->qty;
        $cartProduct->save();

        return 'added to cart!';
    }
}
