<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Race extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'name',
        'animal_id',
        'state',
    ];

    protected $casts = [
        'state' => 'boolean',
    ];

    public function getFullRaceAttribute()
    {
        return $this->animal->name . ' - ' . $this->name;
    }

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }

    public function pets()
    {
        return $this->hasMany(Pet::class);
    }
}
