<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
  use HasFactory;
  protected $table = 'product';
  protected $primaryKey = 'product_id';
  public $timestamps = false;

  public function customers() {
    return $this->belongsToMany(Customer::class, 'customer_product', 'product_id', 'customer_id');;
  }
}
