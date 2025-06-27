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

    /**
     * Generate a barcode-like format based on the ID
     */
    public function getBarcodeAttribute()
    {
        // Generate a barcode-like number: category code + padded ID + check digit
        $categoryCode = match ($this->category) {
            'Groente' => '01',
            'Fruit' => '02',
            'Vlees' => '03',
            'Zuivel' => '04',
            'Granen' => '05',
            'Conserven' => '06',
            'Diepvries' => '07',
            'Brood' => '08',
            'Overig' => '09',
            default => '00'
        };

        // Pad the ID to 6 digits
        $paddedId = str_pad($this->id, 6, '0', STR_PAD_LEFT);

        // Generate a simple check digit (sum of all digits mod 10)
        $digits = $categoryCode . $paddedId;
        $checkDigit = array_sum(str_split($digits)) % 10;

        return $categoryCode . $paddedId . $checkDigit;
    }

    /**
     * Get formatted barcode with spaces for readability
     */
    public function getFormattedBarcodeAttribute()
    {
        $barcode = $this->barcode;
        return substr($barcode, 0, 2) . ' ' .
            substr($barcode, 2, 3) . ' ' .
            substr($barcode, 5, 3) . ' ' .
            substr($barcode, 8, 1);
    }

    /**
     * Extract ID from barcode input (with or without spaces)
     */
    public static function getIdFromBarcode($barcodeInput)
    {
        // Remove spaces and get just the numbers
        $cleanBarcode = preg_replace('/\s+/', '', $barcodeInput);

        // Extract the ID part (positions 2-7, remove leading zeros)
        if (strlen($cleanBarcode) >= 8) {
            $idPart = substr($cleanBarcode, 2, 6);
            return (int) ltrim($idPart, '0') ?: null;
        }

        return null;
    }

    public function hasStock($amount)
    {
        return $this->amount >= $amount;
    }

    public function decrementStock($amount)
    {
        $this->decrement('amount', $amount);
    }

    public function incrementStock($quantity)
    {
        $this->increment('amount', $quantity);

    }
}
