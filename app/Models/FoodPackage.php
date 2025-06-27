<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FoodPackage extends Model
{
    use HasFactory;

    protected $table = 'food_package';

    protected $fillable = [
        'customer_id',
        'prepared_by',
        'package_name',
        'assembled_at',
        'distribution_date',
        'pickup_time',
        'status',
        'is_actief',
        'opmerking',
        'datum_aangemaakt',
        'datum_gewijzigd',
    ];
}
