<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Menu extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'coddoc',
        'text',
        'route',
        'active',
        'icon',
        'order',
        'menu_id',
    ];

    public function dad()
    {
        return $this->belongsTo(self::class, 'menu_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'menu_id');
    }

    public function docs()
    {
        return $this->hasMany(Doc::class, 'menu_id');
    }
}
