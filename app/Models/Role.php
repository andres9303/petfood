<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Role extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'name',
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission')
                    ->withPivot('menu_id');
    }

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'role_permission')
                    ->withPivot('permission_id');
    }
}
