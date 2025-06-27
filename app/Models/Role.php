<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'role';

    protected $fillable = [
        'user_id',
        'rol',
        'is_actief',
        'opmerking',
        'datum_aangemaakt',
        'datum_gewijzigd',
    ];

    protected $casts = [
        'is_actief' => 'boolean',
        'datum_aangemaakt' => 'datetime',
        'datum_gewijzigd' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
