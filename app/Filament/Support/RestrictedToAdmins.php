<?php

namespace App\Filament\Support;

use Illuminate\Database\Eloquent\Model;

/**
 * The stone inventory (owners, mines, warehouses, sales, reports) is business-sensitive: only
 * administrators and the super user may see or change it — editors and sales staff manage the catalogue.
 * Put this trait on a Filament resource / page.
 */
trait RestrictedToAdmins
{
    public static function userIsAdmin(): bool
    {
        $user = auth()->user();

        return $user && ($user->isAdmin() || $user->isSuperUser());
    }

    public static function canViewAny(): bool
    {
        return static::userIsAdmin();
    }

    public static function canCreate(): bool
    {
        return static::userIsAdmin();
    }

    public static function canEdit(Model $record): bool
    {
        return static::userIsAdmin();
    }

    public static function canDelete(Model $record): bool
    {
        return static::userIsAdmin();
    }

    public static function canDeleteAny(): bool
    {
        return static::userIsAdmin();
    }
}
