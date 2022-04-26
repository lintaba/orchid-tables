<?php

declare(strict_types=1);

namespace Lintaba\OrchidTables;

use Lintaba\OrchidTables\Exceptions\MissingPackageException;
use Lintaba\OrchidTables\Mixins\CellExportFormattableMixin;
use Maatwebsite\Excel\Excel;
use Orchid\Screen\Cell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrchidTables
{
    public function mixinTdExportFormattables(): void
    {
        if (!class_exists(Worksheet::class)) {
            return;
        }
        if (!class_exists(Excel::class)) {
            return;
        }
        $mix = app(CellExportFormattableMixin::class);

        Cell::mixin($mix, true);
    }
}
