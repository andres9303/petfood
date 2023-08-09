<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Pet extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'name',
        'race_id',
        'person_id',
        'date',
        'living',
        'sib',
        'diet',
        'exercise',
        'allergy',
        'vaccine',
        'deworming',
        'health',
        'reproductive',
        'weight',
        'text',
        'state',
    ];

    public function getFullPetAttribute()
    {
        return $this->name.' ['.$this->person->name.']';
    }

    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

    public function race()
    {
        return $this->belongsTo(Race::class, 'race_id');
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function docs()
    {
        return $this->hasMany(Doc::class, 'pet_id');
    }
}
