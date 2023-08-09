<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Unit extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'name',
        'factor',
        'unit_id',
        'state',
    ];

    protected $casts = [
        'state' => 'boolean',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }

    public function mvtos()
    {
        return $this->hasMany(Mvto::class, 'unit_id');
    }

    public function mvtos2()
    {
        return $this->hasMany(Mvto::class, 'unit2_id');
    }
}
