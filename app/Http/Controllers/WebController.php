<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;

class WebController extends Controller
{
  public function index() {
    $order = Order::with('customer', 'order_detail')->where('customer_id', '>', 2)->limit(50)->get();
    return response()->json($order);
    $product = Product::first();    
    $products = Product::limit(10)->get();    
    $customers = Customer::limit(10)->get();
    $orders = Order::limit(10)->get();
    $ordersDetail = OrderDetail::limit(10)->get();
  }
}
