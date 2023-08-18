<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Animal extends Model implements Auditable
{
    use HasFactory;    
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'name',
        'state',
    ];

    public function races()
    {
        return $this->hasMany(Race::class);
    }
}
