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
        'active' => 'boolean',
    ];
}
