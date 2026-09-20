<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/** The quarry a stone comes from. Back-office data only — never shown to visitors. */
class Mine extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'location', 'notes', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
