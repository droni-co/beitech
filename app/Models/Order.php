<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
  use HasFactory;
  protected $table = 'order';
  protected $primaryKey = 'order_id';
  protected $dates = ['creation_date'];
  public $timestamps = false;

  public function customer() {
    return $this->belongsTo(Customer::class, 'customer_id');
  }
  public function order_detail() {
    return $this->hasMany(OrderDetail::class, 'order_id', 'order_id');
  }
}
