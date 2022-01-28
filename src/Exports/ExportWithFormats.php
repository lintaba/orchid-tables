<?php

declare(strict_types=1);

namespace Lintaba\OrchidTables\Exports;

use Maatwebsite\Excel\Concerns;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

abstract class ExportWithFormats implements Concerns\WithStyles, Concerns\WithProperties, ExportStyles
{
    use Concerns\Exportable;

    protected $computedStyles = [];

    /**
     * @param Worksheet $sheet
     *
     * @return array
     */
    final public function styles(Worksheet $sheet): array
    {
        $a1 = $this->computedStyles['A1'] ?? [];
        unset($this->computedStyles['A1']);
        $this->computedStyles['A1'] = $a1;

        return $this->computedStyles;
    }

    public function properties(): array
    {
        return [
            'creator' => optional(optional(request())->user())->name,
            $this->getName(),
        ];
    }

    abstract protected function getName(): string;

    protected function indexToLetter(int $num)
    {
        return Coordinate::stringFromColumnIndex($num);
    }
}
