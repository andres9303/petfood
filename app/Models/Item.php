<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Item extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['id', 'name', 'text', 'order', 'factor', 'catalog_id', 'item_id'];

    public function catalog()
    {
        return $this->belongsTo(Catalog::class);
    }

    public function ref()
    {
        return $this->belongsTo(Item::class);
    }

    public function docs()
    {
        return $this->hasMany(Doc::class, 'item_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'class');
    }
}
