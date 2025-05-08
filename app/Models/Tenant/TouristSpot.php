<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TouristSpot extends Model
{
    use HasFactory;

    protected $table = 'touristspot'; // Specify the table name

    protected $fillable = [
        'name',
        'location',
        'description',
        'category',
        'opening_hours',
        'entry_fee',
        'image',
    ];
}
