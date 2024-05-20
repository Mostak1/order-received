<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable=['quantity','total', 'status','orders_time'];

   public function OrderDetail(){
    return $this->hasMany(OrderDetail::class);
   }
}
