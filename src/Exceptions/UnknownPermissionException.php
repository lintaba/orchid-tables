<?php

declare(strict_types=1);

namespace Lintaba\OrchidTables\Exceptions;

use Facade\IgnitionContracts\BaseSolution;
use Facade\IgnitionContracts\ProvidesSolution;
use Facade\IgnitionContracts\Solution;
use RuntimeException;

class UnknownPermissionException extends RuntimeException implements ProvidesSolution
{
    public function getSolution(): Solution
    {
        $description = sprintf(
            "Add it to your orchid service provider: `%s@registerPermissions` `->addPermission('%s', __('roles.%s'))`",
            config('platform.provider'),
            $this->getMessage(),
            $this->getMessage()
        );

        return BaseSolution::create("Role {$this->getMessage()} was not defined. ")
                           ->setSolutionDescription($description);
    }
}
