<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PotentialClient extends Model
{
    use HasFactory;


    protected $fillable = ['name', 'email', 'city', 'payment_method', 'plan_type'];
}