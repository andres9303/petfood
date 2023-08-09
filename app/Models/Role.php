<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

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
