<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Builder;

/**
 * Supplier Model
 * 
 * Represents a supplier (leverancier) in the food bank system.
 * Suppliers provide produce that gets stored in food storages.
 *
 * @package App\Models
 * @author Voedselbank Development Team
 * @version 1.0
 * 
 * @property int $id
 * @property string $name
 * @property string $contact_person
 * @property string $phone
 * @property string $email
 * @property string $address
 * @property string $supplier_type
 * @property bool $is_actief
 * @property string|null $opmerking
 * @property \Carbon\Carbon $datum_aangemaakt
 * @property \Carbon\Carbon $datum_gewijzigd
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Supplier extends Model
{
    /** @use HasFactory<\Database\Factories\SupplierFactory> */
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'suppliers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
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

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_actief' => 'boolean',
        'datum_aangemaakt' => 'datetime',
        'datum_gewijzigd' => 'datetime',
    ];

    /**
     * Valid supplier types constant for validation and consistency.
     *
     * @var array<string>
     */
    const SUPPLIER_TYPES = [
        'Supermarket',
        'Farmer',
        'Wholesaler',
        'Individual'
    ];

    /**
     * Active order statuses that prevent supplier deletion.
     *
     * @var array<string>
     */
    const ACTIVE_ORDER_STATUSES = ['onderweg', 'in_behandeling'];

    /**
     * Get the produce associated with the supplier.
     *
     * @return HasMany
     */
    public function produce(): HasMany
    {
        return $this->hasMany(Produce::class);
    }

    /**
     * Get food storages that have produce from this supplier.
     *
     * @return HasManyThrough
     */
    public function foodStorages(): HasManyThrough
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
     *
     * @return HasManyThrough
     */
    public function activeFoodStorages(): HasManyThrough
    {
        return $this->foodStorages()->whereIn('status', self::ACTIVE_ORDER_STATUSES);
    }

    /**
     * Check if supplier has any active orders.
     * 
     * Active orders are those with status 'onderweg' or 'in_behandeling'.
     * Suppliers with active orders cannot be deleted for data integrity.
     *
     * @return bool
     */
    public function hasActiveOrders(): bool
    {
        return $this->activeFoodStorages()->exists();
    }

    /**
     * Get the supplier's full contact information as a formatted string.
     *
     * @return string
     */
    public function getFullContactAttribute(): string
    {
        return "{$this->contact_person} - {$this->phone} - {$this->email}";
    }

    /**
     * Get a human-readable supplier type.
     *
     * @return string
     */
    public function getSupplierTypeDisplayAttribute(): string
    {
        $translations = [
            'Supermarket' => 'Supermarkt',
            'Farmer' => 'Boer',
            'Wholesaler' => 'Groothandel',
            'Individual' => 'Particulier'
        ];

        return $translations[$this->supplier_type] ?? $this->supplier_type;
    }

    /**
     * Scope a query to only include active suppliers.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_actief', true);
    }

    /**
     * Scope a query to only include suppliers of a specific type.
     *
     * @param Builder $query
     * @param string $type
     * @return Builder
     */
    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('supplier_type', $type);
    }

    /**
     * Scope a query to search suppliers by name, contact person, or email.
     *
     * @param Builder $query
     * @param string $search
     * @return Builder
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
                ->orWhere('contact_person', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%");
        });
    }

    /**
     * Check if the supplier type is valid.
     *
     * @param string $type
     * @return bool
     */
    public static function isValidSupplierType(string $type): bool
    {
        return in_array($type, self::SUPPLIER_TYPES, true);
    }

    /**
     * Boot the model and set up event listeners.
     *
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();

        // Automatically update datum_gewijzigd on model updates
        static::updating(function ($supplier) {
            $supplier->datum_gewijzigd = now();
        });
    }
}
