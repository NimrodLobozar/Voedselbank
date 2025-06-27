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

    public function produces()
    {
        return $this->belongsToMany(Produce::class, 'food_package_produce', 'food_package_id', 'produce_id')
            ->withPivot('quantity', 'created_at', 'updated_at');
    }

    public static function generatePackageName($packageName, $produceItems)
    {
        if (!empty($packageName)) {
            return $packageName;
        }
        $shortCodes = [];
        foreach ($produceItems as $item) {
            $produce = \App\Models\Produce::find($item['id']);
            if ($produce) {
                $prodShort = ucfirst(mb_substr($produce->name, 0, 3));
                $catShort = strtoupper(mb_substr($produce->category, 0, 2));
                $shortCodes[] = "{$prodShort}-{$catShort}";
            }
        }
        $max = 3;
        $nameList = array_slice($shortCodes, 0, $max);
        $generated = implode(' ', $nameList);
        if (count($shortCodes) > $max) {
            $generated .= ' ...';
        }
        return $generated;
    }
}
