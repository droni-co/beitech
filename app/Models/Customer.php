<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
  use HasFactory;
  protected $table = 'customer';
  protected $primaryKey = 'customer_id';
  public $timestamps = false;

  public function products() {
    return $this->belongsToMany(Product::class, 'customer_product', 'customer_id', 'product_id');
  }
  public function orders() {
    return $this->hasMany(Order::class, 'customer_id', 'customer_id');
  }
}
