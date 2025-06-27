<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produce extends Model
{
    use HasFactory;

    protected $table = 'produce';

    protected $fillable = [
        'supplier_id',
        'food_storage_id',
        'name',
        'brand',
        'category',
        'expiry_date',
        'received_date',
        'amount',
        'unit',
        'weight_per_unit',
        'is_actief',
        'opmerking',
        'datum_aangemaakt',
        'datum_gewijzigd'
    ];

    protected $casts = [
        'is_actief' => 'boolean',
        'expiry_date' => 'date',
        'received_date' => 'date',
        'datum_aangemaakt' => 'datetime',
        'datum_gewijzigd' => 'datetime',
        'weight_per_unit' => 'decimal:3',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function foodStorage()
    {
        return $this->belongsTo(FoodStorage::class);
    }

    public function foodPackages()
    {
        return $this->belongsToMany(FoodPackage::class, 'food_package_produce')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}