<?php

namespace App\Models;

use Carbon\Carbon;
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
        'code',
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
        'user_id',
        'ref',
        'cant',
        'saldo',
        'item_id',
        'doc_id',
        'pet_id'
    ];

    public function getFormattedDateAttribute()
    {
        return Carbon::parse($this->attributes['date'])->format('d/m/Y');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
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
        return $this->hasMany(Mvto::class, 'doc_id', 'id');
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }
}
