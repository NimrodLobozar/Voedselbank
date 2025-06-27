<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

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
    public function getIsNearlyFullAttribute()
    {
        return $this->occupancy_percentage > 80;
    }
}
