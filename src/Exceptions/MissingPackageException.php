<?php

declare(strict_types=1);

namespace Lintaba\OrchidTables\Exceptions;

use Facade\IgnitionContracts\BaseSolution;
use Facade\IgnitionContracts\ProvidesSolution;
use Facade\IgnitionContracts\Solution;
use RuntimeException;

class MissingPackageException extends RuntimeException implements ProvidesSolution
{
    public function getSolution(): Solution
    {
        $message     = 'Missing package: ' . $this->getMessage();
        $description = 'run `$ composer install ' . $this->getMessage() . '` ';

        return (new BaseSolution($message))->setSolutionDescription($description);
    }
}
