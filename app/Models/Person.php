<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Person extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'code',
        'name',
        'email',
        'phone',
        'address',
        'birth',
        'isclient',
        'issupplier',
        'isoperator',
        'state'
    ];

    protected $casts = [
        'isclient' => 'boolean',
        'issupplier' => 'boolean',
        'isoperator' => 'boolean',
        'state' => 'boolean'
    ];

    public function docs()
    {
        return $this->hasMany(Doc::class, 'person_id');
    }
}
