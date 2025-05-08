<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'touristspot_id',
        'name',
        'phone',
        'quantity',
        'order_type',
        'subscriptionType',
        'total_price',
        'status',
    ];


    // Define relationship with TouristSpot
    public function touristspot()
    {
        return $this->belongsTo(TouristSpot::class);
    }
}