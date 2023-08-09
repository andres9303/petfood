<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Product extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'code',
        'name',
        'factor',
        'unit_id',
        'state',
        'isinventory',
        'class',
        'type',
        'product_id'
    ];

    protected $casts = [
        'state' => 'boolean',
        'isinventory' => 'boolean'
    ];

    public function getFullNameAttribute()
    {
        return $this->name.' ('.$this->unit->name.')';
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function ref()
    {
        return $this->belongsTo(Product::class);
    }

    public function subs()
    {
        return $this->hasMany(Product::class, 'product_id');
    }

    public function category()
    {
        return $this->belongsTo(Item::class, 'class', 'id');
    }

    public function mvtos()
    {
        return $this->hasMany(Mvto::class);
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }
}
