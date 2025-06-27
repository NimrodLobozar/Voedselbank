<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodStorage extends Model
{
    use HasFactory;

    protected $table = 'food_storage';

    protected $fillable = [
        'name',
        'location',
        'capacity',
        'temperature_min',
        'temperature_max',
        'storage_type',
        'is_actief',
        'opmerking',
    ];
}
