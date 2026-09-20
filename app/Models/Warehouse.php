<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/** Where a stone is stored. Back-office data only — never shown to visitors. */
class Warehouse extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'location', 'notes', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
