<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'details'
    ];
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function received()
    {
        return $this->hasMany(Received::class);
    }

    public function getTotalOrderedAttribute()
    {
        return $this->orders()->sum('quantity');
    }

    public function getTotalReceivedAttribute()
    {
        return $this->received()->sum('quantity');
    }

    public function getRemainingAttribute()
    {
        return $this->total_ordered - $this->total_received;
    }
}
