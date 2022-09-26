<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\cart_product;
use App\Models\Cart;
use App\Models\Product;

class CartProductController extends Controller
{
    public function addToCart(Request $request)
    {
        // verify
        $product = Product::where('id', $request->id)->first();
        if($product->stock < $request->qty)
            return 'invalid quantity';

        // update product stock
        $product->stock -= $request->qty;
        $product->save();

        $cart = Cart::where('isUsed', 0)->first();

        $cartProduct = new cart_product;
        $cartProduct->cart_id = $cart->id;
        $cartProduct->product_id = $request->id;
        $cartProduct->qty = $request->qty;
        $cartProduct->save();

        return 'added to cart!';
    }
}
