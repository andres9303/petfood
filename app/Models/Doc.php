<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Doc extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'type',
        'menu_id',
        'person_id',
        'cod',
        'num',
        'date',
        'date2',
        'subtotal',
        'iva',
        'total',
        'state',
        'text',
        'concept',
        'value',
        'person2_id',
        'ref',
        'cant',
        'saldo',
        'item_id',
        'doc_id',
        'pet_id'
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

    public function person2()
    {
        return $this->belongsTo(Person::class, 'person2_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function ref()
    {
        return $this->belongsTo(Doc::class, 'doc_id');
    }

    public function mvtos()
    {
        return $this->hasMany(Mvto::class);
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }
}
