<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Mvto extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'doc_id',
        'product_id',
        'unit_id',
        'cant',
        'saldo',
        'valueu',
        'iva',
        'valuet',
        'costu',
        'text',
        'state',
        'product2_id',
        'unit2_id',
        'cant2',
        'saldo2',
        'valueu2',
        'iva2',
        'valuet2',
        'text2',
        'mvto_id'
    ];

    public function doc()
    {
        return $this->belongsTo(Doc::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function product2()
    {
        return $this->belongsTo(Product::class);
    }

    public function unit2()
    {
        return $this->belongsTo(Unit::class);
    }

    public function mvto()
    {
        return $this->belongsTo(Mvto::class);
    }
}
