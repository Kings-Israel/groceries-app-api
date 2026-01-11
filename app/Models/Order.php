<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id', 'order_number', 'total_amount', 'status',
        'delivery_location', 'delivery_latitude', 'delivery_longitude',
        'notes', 'rating', 'review'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'delivery_latitude' => 'decimal:8',
        'delivery_longitude' => 'decimal:8',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
