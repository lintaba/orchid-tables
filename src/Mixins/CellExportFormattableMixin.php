<?php

declare(strict_types=1);

namespace Lintaba\OrchidTables\Mixins;

use Lintaba\OrchidTables\DataHelpers;
use Lintaba\OrchidTables\ExportStyles;
use Orchid\Screen\Cell;

class CellExportFormattableMixin extends CellMixin
{
    public static function date()
    {
        return function (bool $withHumanReadable = true, string $format = null): self {
            /** @var Cell $this */

            $column = $this->column;

            $this->setStyle(function ($datum) use ($column) {
                $value = $datum->getContent($column);
                if ($value === null) {
                    return ExportStyles::FORMAT_NONE;
                }

                return ExportStyles::FORMAT_DATETIME;
            });

            return CellMixin::date()->call($this, ...func_get_args());
        };
    }

    public static function num()
    {
        return function (
            int $decimals = 0,
            string $suffix = null,
            string $decimalSeparator = ',',
            string $thousandsSeparator = DataHelpers::NBSP
        ): self {
            /** @var Cell $this */

            $column = $this->column;

            $this->setStyle(function ($datum) use ($suffix, $decimals) {
                $dec = $decimals > 0 ? ',' . str_repeat('#', $decimals) : '';

                return [
                    'numberFormat' => [
                        'formatCode' => sprintf(
                            "#%s\" %s\";(-#%s\" %s\");0\" %s\"",
                            $dec,
                            $suffix,
                            $dec,
                            $suffix,
                            $suffix
                        ),
                    ],
                ];
            });

            return CellMixin::num()->call($this, ...func_get_args());
        };
    }

    public static function keyValues()
    {
        return function (): self {
            $column = $this->column;

            $this->exportRender(function ($datum) use ($column) {
                if (is_scalar($datum) || $datum === null) {
                    return $datum;
                }
                $value = $datum->getContent($column);

                return json_encode($value, JSON_THROW_ON_ERROR);
            });

            return CellMixin::keyValues()->call($this, ...func_get_args());
        };
    }

    public static function setStyle(): callable
    {
        return function ($style): self {
            throw_unless(is_callable($style) || is_array($style));
            /** @var Cell $this */
            $this->excelStyles[] = $style;

            return $this;
        };
    }

    public static function getStyle(): callable
    {
        return function ($value) {
            /** @var Cell $this */
            $acc = [];
            foreach ($this->excelStyles ?? [] as $fn) {
                $acc[] = value($fn, $value);
            }

            return array_merge_recursive(...$acc);
        };
    }
}
