<?php

declare(strict_types=1);

namespace Lintaba\OrchidTables\Exports;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Iterator;
use Maatwebsite\Excel\Concerns;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use ReflectionClass;
use ReflectionMethod;

class QuickExport extends ExportWithFormats implements Concerns\FromIterator, Concerns\ShouldAutoSize
{
    protected $builder;
    /** @var Collection|TD[] $columns */
    protected $columns;

    public function __construct($builder, Table $table)
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

        foreach ($this->getData() as $entry) {
            yield $columns->map(function ($col, $colIndex) use ($entry, $rowNum) {
                return $this->exportField($colIndex, $rowNum, $col, $entry);
            })->toArray();
            $rowNum++;
        }

        $this->computedStyles['A1:' . $this->indexToLetter(count($headers)) . '1'] = ExportStyles::FORMAT_BOLD;
    }

    protected function getHeaders(Collection $columns): array
    {
        return $columns->map(function ($e) {
            return $e->getTitle();
        })->toArray();
    }

    protected function hackyGetColumns(Table $table)
    {
        $method = new ReflectionMethod($table, 'columns');
        $method->setAccessible(true);

        return $method->invoke($table);
    }

    protected function isDate($value, $entry, $fieldName): bool
    {
        return $value instanceof DateTimeInterface
            || Str::of($fieldName)->endsWith('_at')
            || ($entry instanceof Model && $entry->hasCast(
                $fieldName,
                ['date', 'datetime', 'immutable_date', 'immutable_datetime']
            ));
    }

    protected function getName(): string
    {
        return Str::slug(
            (new ReflectionClass(
                $this->builder instanceof \Illuminate\Database\Eloquent\Builder ?
                    $this->builder->getModel() : $this->builder
            ))->getShortName()
        );
    }

    protected function exportField(int $colIndex, int $rowNum, $col, $entry)
    {
        if (method_exists($col, 'getStyle')) {
            $this->computedStyles[$this->indexToLetter($colIndex + 1) . $rowNum] = $col->getStyle($entry);
        }

        $fieldName = $col->getName();
        $value = is_object($entry) && method_exists($entry, 'getContent') ?
            $entry->getContent($fieldName) : data_get($entry, $fieldName);

        $value = $col->exportGetValue($value, $entry, $rowNum);

        if ($value instanceof Collection) {
            $value = $value->map(function ($val) {
                return $val instanceof Model ? $val->id : (string)$val;
            })
                           ->implode('| ');
        }
        if ($this->isDate($value, $entry, $fieldName)) {
            $value = $entry[$fieldName] ? Date::dateTimeToExcel($entry[$fieldName]) : '';
        }
        if ($value instanceof Model) {
            $value = (string)$value;
        }
        if (is_array($value)) {
            $value = implode(
                ', ',
                array_map(static function ($v) {
                    if (is_array($v)) {
                        $v = $v['name'] ?? Arr::first($v);
                    }

                    return (string)$v;
                }, $value)
            );
        }

        return $value;
    }

    protected function getData()
    {
        if ($this->builder instanceof \Illuminate\Database\Eloquent\Builder) {
            return $this->builder->lazy();
        }
        if (is_iterable($this->builder)) {
            return $this->builder;
        }
        throw new \RuntimeException('Export needs a builder or iterable.');
    }
}
