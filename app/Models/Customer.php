<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Customer extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory;

    protected $table = 'customer';

    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'birth_date',
        'street',
        'house_number',
        'addition',
        'postal_code',
        'city',
        'mobile',
        'email',
        'household_size',
        'income',
        'registration_date',
        'is_actief',
        'opmerking',
        'datum_aangemaakt',
        'datum_gewijzigd'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'registration_date' => 'date',
        'is_actief' => 'boolean',
        'income' => 'decimal:2',
        'datum_aangemaakt' => 'datetime',
        'datum_gewijzigd' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function allergies()
    {
        return $this->belongsToMany(Allergy::class, 'customer_allergy')
                    ->withPivot('severity', 'is_actief', 'opmerking')
                    ->withTimestamps();
    }

    public function foodPackages()
    {
        return $this->hasMany(FoodPackage::class);
    }

    public function families()
    {
        return $this->hasMany(Family::class);
    }

    public function getFullNameAttribute()
    {
        $middleName = $this->middle_name ? ' ' . $this->middle_name : '';
        return $this->first_name . $middleName . ' ' . $this->last_name;
    }

    public function getFullAddressAttribute()
    {
        $addition = $this->addition ? ' ' . $this->addition : '';
        return $this->street . ' ' . $this->house_number . $addition . ', ' . $this->postal_code . ' ' . $this->city;
    }

    public static function getAllCustomers()
    {
        return DB::select('CALL sp_GetCustomers()');
    }

    public static function getCustomerById($id)
    {
        $result = DB::select('CALL sp_GetCustomerById(?)', [$id]);
        return !empty($result) ? $result[0] : null;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($customer) {
            $customer->datum_aangemaakt = now();
            $customer->datum_gewijzigd = now();
        });

        static::updating(function ($customer) {
            $customer->datum_gewijzigd = now();
        });
    }
}
