<?php declare(strict_types=1);

namespace Lintaba\OrchidTables\Exports;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Iterator;
use Lintaba\OrchidTables\ExportStyles;
use Maatwebsite\Excel\Concerns;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use ReflectionClass;
use ReflectionMethod;

class QuickExport extends ExportWithFormats implements Concerns\FromIterator, Concerns\ShouldAutoSize
{
    protected $builder;
    /** @var Collection|TD[] $columns */
    private $columns;

    public function __construct(Builder $builder, Table $table)
    {
        $this->builder = $builder;

        $this->columns = $this->hackyGetColumns($table);
    }

    public function iterator(): Iterator
    {
        $rowNum = 1;

        $columns = collect($this->columns)->filter(static function (TD $column) {
            return $column->isSee() && $column->isExportable();
        })->values();

        $headers = $this->getHeaders($columns);
        yield array_values($headers);
        $rowNum++;

        foreach ($this->builder->lazy() as $entry) {
            yield $columns->map(function ($col, $colIndex) use ($entry, $rowNum) {
                return $this->exportField($colIndex, $rowNum, $col, $entry);
            })->toArray();
            $rowNum++;
        }

        $this->computedStyles['A1:' . Coordinate::stringFromColumnIndex(count($headers)) . '1'] = ExportStyles::header();
    }

    private function getHeaders(Collection $columns): array
    {
        return $columns->map(function ($e) { return $e->getTitle(); })->toArray();
    }

    private function hackyGetColumns(Table $table)
    {

        $method = new ReflectionMethod($table, 'columns');
        $method->setAccessible(true);

        return $method->invoke($table);
    }

    protected function isDate($value, $entry, $fieldName): bool
    {
        return $value instanceof DateTimeInterface || $entry->hasCast($fieldName,
                ['date', 'datetime', 'immutable_date', 'immutable_datetime']) || Str::of($fieldName)->endsWith('_at');
    }

    protected function getName(): string
    {
        return Str::slug((new ReflectionClass($this->builder->getModel()))->getShortName());
    }

    protected function exportField(int $colIndex, int $rowNum, $col, $entry)
    {
        $this->computedStyles[Coordinate::stringFromColumnIndex($colIndex + 1) . $rowNum] = $col->getStyle($entry);

        $fieldName = $col->getName();
        $value     = $entry->getContent($fieldName) ?? data_get($entry, $fieldName);

        $value = $col->exportGetValue($value, $entry, $rowNum);

        if ($value instanceof Collection) {
            $value = $value->map(function ($val) { return $val instanceof Model ? $val->id : (string)$val; })
                           ->implode('| ');
        }
        if ($this->isDate($value, $entry, $fieldName)) {
            $value = $entry->{$fieldName} ? Date::dateTimeToExcel($entry->{$fieldName}) : '';
        }
        if ($value instanceof Model) {
            $value = (string)$value;
        }
        if (is_array($value)) {
            $value = implode(', ', array_map(static function ($v) {
                if (is_array($v)) {
                    $v = $v['name'] ?? Arr::first($v);
                }

                return (string)$v;
            }, $value));
        }

        return $value;
    }
}
