<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReceivedDetail extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable=['received_id','product_id', 'quantity', 'status','received_time'];
    public function product(){
        return $this->belongsTo(Product::class);
    }
    public function received(){
        return $this->belongsTo(Received::class);
    }
}
