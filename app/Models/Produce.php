<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'weight_per_unit'
    ];

    protected $casts = [
        'expiry_date' => 'datetime',
        'received_date' => 'datetime',
        'datum_aangemaakt' => 'datetime',
        'datum_gewijzigd' => 'datetime'
    ];

    public function foodStorage()
    {
        return $this->belongsTo(FoodStorage::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function isExpired()
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    public function isExpiringSoon($days = 3)
    {
        return $this->expiry_date && $this->expiry_date->diffInDays(now()) <= $days;
    }
}