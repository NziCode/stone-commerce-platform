<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SeoMeta extends Model
{
    protected $fillable = [
        'seoable_type', 'seoable_id', 'locale',
        'meta_title', 'meta_description', 'meta_keywords',
        'og_title', 'og_description', 'og_image', 'og_type',
        'canonical_url', 'schema_org', 'robots',
    ];

    protected $casts = ['schema_org' => 'array'];

    public function seoable(): MorphTo
    {
        return $this->morphTo();
    }
}
