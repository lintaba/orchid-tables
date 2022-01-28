<?php

declare(strict_types=1);

namespace Lintaba\OrchidTables;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Lintaba\OrchidTables\Exceptions\UnknownPermissionException;
use Orchid\Support\Facades\Dashboard;

class DataHelpers
{
    public const NBSP = "\u{00a0}";

    /**
     * Formats a number for given decimals, decimal separator, and appends an optional suffix (with a non-breakable
     * space)
     *
     * @param string|int|float|null $val
     * @param int                   $decimals
     * @param string|null           $suffix
     * @param string                $decimalSeparator
     * @param string                $thousandsSeparator
     *
     * @return string
     */
    public static function formatNum(
        $val,
        int $decimals = 0,
        string $suffix = null,
        string $decimalSeparator = ',',
        string $thousandsSeparator = self::NBSP
    ): string {
        if ($val === null) {
            return 'n/a';
        }
        $val    = round((float)$val, $decimals);
        $num    = number_format($val, $decimals, $decimalSeparator, $thousandsSeparator);
        $suffix = $suffix ? self::NBSP . $suffix : '';

        return $num . $suffix;
    }

    public static function checkAnyPermissions(...$permissions): bool
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }
        $availablePermissions = collect(Dashboard::getPermission())->flatten(1)->pluck('slug');

        return collect($permissions)->flatten()->some(function ($permission) use ($user, $availablePermissions) {
            if (config('app.debug')) {
                throw_unless(
                    $availablePermissions->contains($permission),
                    UnknownPermissionException::class,
                    $permission
                );
            }

            return $user->hasAccess($permission);
        });
    }

    public static function checkAllPermissions(...$permissions): bool
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }
        $availablePermissions = self::getAvailablePermissions();

        return collect($permissions)->flatten()->every(function ($permission) use ($user, $availablePermissions) {
            if (config('app.debug')) {
                throw_unless(
                    $availablePermissions->contains($permission),
                    UnknownPermissionException::class,
                    $permission
                );
            }

            return $user->hasAccess($permission);
        });
    }

    /**
     * @return Collection
     */
    private static function getAvailablePermissions(): Collection
    {
        return collect(Dashboard::getPermission())->flatten(1)->pluck('slug');
    }
}
