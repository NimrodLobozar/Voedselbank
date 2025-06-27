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
        'status',
        'is_actief',
        'opmerking',
        'datum_aangemaakt',
        'datum_gewijzigd'
    ];

    protected $casts = [
        'is_actief' => 'boolean',
        'datum_aangemaakt' => 'datetime',
        'datum_gewijzigd' => 'datetime',
        'temperature_min' => 'decimal:2',
        'temperature_max' => 'decimal:2',
    ];

    // Relatie naar producten die in deze storage liggen
    public function produces()
    {
        return $this->hasMany(Produce::class, 'food_storage_id');
    }

    // Alleen actieve producten
    public function activeProduces()
    {
        return $this->hasMany(Produce::class, 'food_storage_id')->where('is_actief', true);
    }

    // Producten die binnenkort verlopen (binnen 7 dagen)
    public function expiringSoonProduces()
    {
        return $this->hasMany(Produce::class, 'food_storage_id')
            ->where('expiry_date', '<=', now()->addDays(7))
            ->where('is_actief', true);
    }

    // Totaal gewicht van alle producten in deze storage
    public function getTotalWeightAttribute()
    {
        return $this->produces()->sum(\DB::raw('amount * weight_per_unit'));
    }

    // Bezettingspercentage van de storage
    public function getOccupancyPercentageAttribute()
    {
        $totalWeight = $this->total_weight;
        return $this->capacity > 0 ? min(100, ($totalWeight / $this->capacity) * 100) : 0;
    }

    // Check of storage bijna vol is (boven 80%)
    public function isNearlyFullAttribute()
    {
        return $this->occupancy_percentage > 80;
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'onderweg' => 'Onderweg',
            'in_behandeling' => 'In Behandeling',
            'geleverd' => 'Geleverd',
            default => 'Onbekend'
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'onderweg' => 'bg-yellow-100 text-yellow-800',
            'in_behandeling' => 'bg-blue-100 text-blue-800',
            'geleverd' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }
}
