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
        'details',
        'price',
    ];
    public function orders()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function receiveds()
    {
        return $this->hasMany(ReceivedDetail::class);
    }
    public function notZero()
    {
        $pid = $this->orders()->sum('quantity') - $this->receiveds()->sum('quantity');
        if ($pid !== 0) {
            return $this;
        } else {
            return 0;
        }
    }
    public function getTotalOrderedAttribute()
    {
        return $this->orders()->sum('quantity');
    }

    public function getTotalReceivedAttribute()
    {
        return $this->receiveds()->sum('quantity');
    }

    public function getRemainingAttribute()
    {
        return $this->total_ordered - $this->total_received;
    }
}
