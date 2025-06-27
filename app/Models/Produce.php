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
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'received_date' => 'date',
        'is_actief' => 'boolean',
        'weight_per_unit' => 'decimal:3',
    ];

    // Relatie naar food storage
    public function foodStorage()
    {
        return $this->belongsTo(FoodStorage::class, 'food_storage_id');
    }

    // Relatie naar supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Check of product binnenkort verloopt
    public function isExpiringSoon($days = 7)
    {
        return $this->expiry_date <= now()->addDays($days);
    }

    // Check of product verlopen is
    public function isExpired()
    {
        return $this->expiry_date < now();
    }
}
