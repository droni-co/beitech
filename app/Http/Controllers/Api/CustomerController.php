<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
  public function index(Request $request) {
    $customers = Customer::all();
    return response()->json($customers);
  }
  public function show(Request $request, $customer_id) {
    $customer = Customer::with('orders', 'products')->findOrFail($customer_id);
    return response()->json($customer);
  }
}
