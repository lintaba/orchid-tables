<?php

declare(strict_types=1);

namespace Lintaba\OrchidTables\Mixins;

use Lintaba\OrchidTables\DataHelpers;

class CanMixin
{
    public static function can(): callable
    {
        return function (...$permissions) {
            $this->canSee($this->isSee() && DataHelpers::checkAnyPermissions(...$permissions));

            return $this;
        };
    }

    public static function canAll(): callable
    {
        return function (...$permissions) {
            $this->canSee($this->isSee() && DataHelpers::checkAllPermissions(...$permissions));

            return $this;
        };
    }
}
