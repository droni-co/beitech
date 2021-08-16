<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use App\Models\OrderDetail;
use Carbon\Carbon;

class OrderController extends Controller
{
  public function index(Request $request) {
    $orders = Order::with('customer')->orderBy('creation_date', 'desc');
    if($request->from_date) {
      $orders = $orders->where('creation_date', '>=', $request->from_date);
    }
    if($request->to_date) {
      $orders = $orders->where('creation_date', '<=', $request->to_date);
    }
    if($request->customer_id > 0) {
      $orders = $orders->where('customer_id', $request->customer_id);
    }
    $orders = $orders->paginate(20);
    return response()->json($orders);
  }
  public function show(Request $request, $order_id) {
    $order = Order::with('customer', 'order_detail')->findOrFail($order_id);
    return response()->json($order);
  }
  public function store(Request $request) {
    $validated = $request->validate([
      'customer_id' => 'required',
      'products' => 'required|array',
      'delivery_address' => 'required'
    ]);
    $customer = Customer::findOrFail($request->customer_id);

    /* Validate Product on user */
    if(!$this->checkProducts($customer, $request->products)) {
      return response()->json(['code' => 401, 'message'   =>  'You have no access to this action.'], 401);
    }
  

    /* Create order */
    $order = new Order;
    $order->customer_id = $customer->customer_id;
    $order->total = 0;
    $order->creation_date = Carbon::now();
    $order->delivery_address = $request->delivery_address;
    $order->save();

    /* Add Order Details */
    foreach($request->products as $rowProduct) {
      $product = Product::findOrFail($rowProduct['product_id']);
      $orderDetail = new OrderDetail;
      $orderDetail->order_id = $order->order_id;
      $orderDetail->product_id = $product->product_id;
      $orderDetail->product_description = $product->product_description;
      $orderDetail->price = $product->price;
      $orderDetail->quantity = $rowProduct['quantity'];
      $orderDetail->save();
      // recalculate total
      $order->total += $orderDetail->price * $orderDetail->quantity;
    }
    return response()->json($order);
  }

  private function checkProducts($customer, $products) {
    $totalQty = 0;
    foreach($products as $rowProduct) {
      /* Check if isset product id */
      if(!isset($rowProduct['product_id']) || intval($rowProduct['product_id']) < 1) {
        return false;
      }
      $product = $customer->products->where('product_id', $rowProduct['product_id'])->first();
      /* Check:
        if product exist on user
        if quntity exist and is a positive number
        if sum of product are less than six
       */
      if(!$product || 
        !isset($rowProduct['quantity']) ||
        intval($rowProduct['quantity']) < 1 ||
        ($totalQty + intval($rowProduct['quantity'])) > 5
        ) { return false; }
      $totalQty += intval($rowProduct['quantity']);
    }
    return true;
  }
}
