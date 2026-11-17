<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'public_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'table_number',
        'customer_address',
        'notes',
        'status',
        'payment_status',
        'payment_expires_at',
        'total_amount',
        'total_price',
    ];

    protected $casts = [
        'total_amount' => 'integer',
        'total_price' => 'integer',
        'table_number' => 'integer',
        'payment_expires_at' => 'datetime',
    ];

    public function getRouteKeyName(): string
    {
        return 'public_id';
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
