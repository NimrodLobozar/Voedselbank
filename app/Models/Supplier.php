<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    /** @use HasFactory<\Database\Factories\SupplierFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_person',
        'phone',
        'email',
        'address',
        'supplier_type',
        'is_actief',
        'opmerking',
        'datum_aangemaakt',
        'datum_gewijzigd'
    ];

    protected $casts = [
        'is_actief' => 'boolean',
    ];

    /**
     * Get the produce associated with the supplier.
     */
    public function produce()
    {
        return $this->hasMany(Produce::class);
    }

    /**
     * Get food storages that have produce from this supplier.
     */
    public function foodStorages()
    {
        return $this->hasManyThrough(
            \App\Models\FoodStorage::class,
            Produce::class,
            'supplier_id',      // Foreign key on produce table
            'id',               // Foreign key on food_storage table
            'id',               // Local key on suppliers table
            'food_storage_id'   // Local key on produce table
        );
    }

    /**
     * Get food storages with active orders (onderweg or in_behandeling).
     */
    public function activeFoodStorages()
    {
        return $this->foodStorages()->whereIn('status', ['onderweg', 'in_behandeling']);
    }

    /**
     * Check if supplier has any active orders.
     */
    public function hasActiveOrders()
    {
        return $this->activeFoodStorages()->exists();
    }
}
