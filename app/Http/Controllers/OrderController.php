<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\order;
use App\Models\Cart;

class OrderController extends Controller
{
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

    public function addOrder(Request $request)
    {
        $order = Order::where('isUsed', 0)->first();
        $items = $request->orderItems;

        foreach($items as $item)
        {
            $this->updateProdStock($item['id'], $item['qty']);

            $cart = new Cart;
            $cart->order_id = $order->id;
            $cart->product_id = $item['id'];
            $cart->qty = $item['qty'];
            $cart->save();
        }

        $this->addNewOrderTable($order);

        return 'success';
    }

    public function addNewOrderTable($order)
    {
        $order->isUsed = 1;
        $order->save();

        $order = new Order;
        $order->save();
    }

    public function updateProdStock($id, $qty)
    {
        $prod = Product::where('id', $id)->first();
        $prod->stock -= $qty;
        $prod->save();
    }
}
