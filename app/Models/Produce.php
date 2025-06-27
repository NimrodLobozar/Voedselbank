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

    public function foodPackages()
    {
        return $this->belongsToMany(FoodPackage::class, 'food_package_produce', 'produce_id', 'food_package_id')
            ->withPivot('quantity', 'created_at', 'updated_at');
    }

    public function hasStock($quantity)
    {
        return $this->amount >= $quantity;
    }

    public function decrementStock($quantity)
    {
        $this->decrement('amount', $quantity);
    }

    public function incrementStock($quantity)
    {
        $this->increment('amount', $quantity);
    }
}