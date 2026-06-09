<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class User extends Authenticatable implements HasMedia, FilamentUser
{
    use HasFactory, Notifiable, HasRoles, InteractsWithMedia;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'company', 'country',
        'preferred_language', 'is_active', 'last_login_at', 'last_login_ip',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
            'last_login_at'     => 'datetime',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(150)->height(150)
            ->performOnCollections('avatar');
    }

    public function orders(): HasMany { return $this->hasMany(Order::class); }
    public function posts(): HasMany  { return $this->hasMany(Post::class); }
    public function events(): HasMany { return $this->hasMany(Event::class); }
    public function reviews(): HasMany { return $this->hasMany(Review::class); }
    public function wishlists(): HasMany { return $this->hasMany(Wishlist::class); }
    public function cart() { return $this->hasOne(Cart::class)->latest(); }

    public function scopeActive($q) { return $q->where('is_active', true); }
    public function scopeCustomers($q) { return $q->role('customer'); }

    public function getAvatarUrlAttribute(): string
    {
        return $this->getFirstMediaUrl('avatar', 'thumb')
            ?: asset('images/default-avatar.png');
    }

    public function isAdmin(): bool    { return $this->hasRole('admin'); }
    public function isEditor(): bool   { return $this->hasRole('editor'); }
    public function isSales(): bool    { return $this->hasRole('sales'); }
    public function isCustomer(): bool { return $this->hasRole('customer'); }

    public function recordLogin(string $ip): void
    {
        $this->update(['last_login_at' => now(), 'last_login_ip' => $ip]);
    }

    public function hasWishlisted(int $productId): bool
    {
        return $this->wishlists()->where('product_id', $productId)->exists();
    }

    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        return $this->hasAnyRole(['admin', 'editor', 'sales']);
    }
}
