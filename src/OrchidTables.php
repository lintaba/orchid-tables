<?php

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
            throw new MissingPackageException('PHPOffice/PhpSpreadsheet');
        }
        if (!class_exists(Excel::class)) {
            throw new MissingPackageException('maatwebsite/excel');
        }
        $mix = app(CellExportFormattableMixin::class);

        Cell::mixin($mix, true);
    }
}
