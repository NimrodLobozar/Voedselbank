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
}
