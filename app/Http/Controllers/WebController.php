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
    return redirect('/app/');
  }
}
