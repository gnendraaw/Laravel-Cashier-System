<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\order;
use App\Models\Cart;

class OrderController extends Controller
{
    protected $order;
    protected $cart;

    public function __construct()
    {
        $this->order = Order::where('isUsed', false)->first();
        $this->cart = new Cart();
    }

    public function index()
    {
        $products = Product::orderBy('name')->get();

        return view('order.index', [
            'title' => 'Order',
            'products' => $products,
        ]);
    }

    public function getProduct($id)
    {
        return Product::where('id', $id)->first();;
    }

    public function addProd(Request $request)
    {
        $product = $this->getProduct($request->id);
        $prodInCart = Cart::where('product_id', $product->id)->first();

        // validate
        if(!$prodInCart)
        {
            $this->cart->product_id = $product->id;
            $this->cart->order_id = $this->order->id;
            $this->cart->qty = 1;
            $this->cart->save();

            return 'added to cart';
        }

        $prodInCart->qty++;
        $prodInCart->save();

        return 'order updated';
    }
}
