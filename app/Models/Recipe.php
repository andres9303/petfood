<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Recipe extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'product_id',
        'ref_id',
        'unit_id',
        'cant',
        'text',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function ref()
    {
        return $this->belongsTo(Product::class, 'ref_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
