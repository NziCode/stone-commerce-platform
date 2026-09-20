<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/** Who owns a stone. Back-office data only — never shown to visitors. */
class Owner extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'phone', 'email', 'notes', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
