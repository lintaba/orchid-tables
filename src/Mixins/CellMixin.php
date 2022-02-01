<?php

declare(strict_types=1);

namespace Lintaba\OrchidTables\Mixins;

use Carbon\CarbonImmutable;
use Carbon\Exceptions\InvalidFormatException;
use DateTimeInterface;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Lintaba\OrchidTables\DataHelpers;
use Lintaba\OrchidTables\Screen\TD;
use Orchid\Icons\IconComponent;
use Orchid\Screen\Cell;
use Orchid\Screen\Contracts\Personable;
use Orchid\Screen\Layouts\Persona;

/** @mixin TD */
class CellMixin
{
    public static function date()
    {
        return function (bool $withHumanReadable = true, string $format = null): self {
            /** @var Cell $this */

            $column = $this->column;
            $format = $format ?: config()->get('orchid-tables.date_format');
            $format = $format ?: config()->get('app.date_format');

            $this->render(function ($datum) use ($column, $withHumanReadable, $format) {
                $value = $datum->getContent($column);
                if (blank($value)) {
                    return '';
                }
                try {
                    $date = new CarbonImmutable($value);
                } catch (InvalidFormatException $e) {
                    report($e);

                    return new HtmlString(
                        sprintf(
                            '<span class="text-danger">%s</span>',
                            config('app.debug') ? $e->getMessage() : __('Invalid date')
                        )
                    );
                }

                $format        = $format ?: (($date->year < now()->year) ? 'Y-' : '')
                    . 'm-d' . DataHelpers::NBSP . DataHelpers::NBSP . 'H:i';
                $formatted     = $date->format($format);
                $dateAtom      = $date->format(DateTimeInterface::ATOM);
                $humanReadable = $withHumanReadable ? sprintf(
                    '<br><span class="text-muted">%s</span>',
                    $date->diffForHumans()
                ) : '';

                return new HtmlString(
                    sprintf(
                        '<time datetime="%s" title="%s">%s</time>%s',
                        $dateAtom,
                        $dateAtom,
                        $formatted,
                        $humanReadable
                    )
                );
            });

            return $this;
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
            $this->render(function ($datum) use ($column, $decimals, $suffix, $decimalSeparator, $thousandsSeparator) {
                $value = $datum->getContent($column);

                return DataHelpers::formatNum($value, $decimals, $suffix, $decimalSeparator, $thousandsSeparator);
            });

            return $this;
        };
    }

    public static function limit(): callable
    {
        return function (int $max = 100, string $end = '...'): self {
            /** @var Cell $this */
            $column = $this->column;
            $this->render(function ($datum) use ($column, $max, $end) {
                $value = $datum->getContent($column);
                if (blank($value) || mb_strlen($value) < $max) {
                    return e((string)$value);
                }

                return new HtmlString(
                    sprintf(
                        '<span title="%s">%s</span>',
                        e($value),
                        e(Str::limit($value, $max, $end))
                    )
                );
            });

            return $this;
        };
    }

    public static function bool(): callable
    {
        return function (): self {
            /** @var Cell $this */
            $column = $this->column;
            $this->render(function ($datum) use ($column) {
                $value = $datum->getContent($column);

                $icon = $value ? ['path' => 'check', 'fill' => 'green'] : ['path' => 'cross', 'fill' => 'red'];

                return app(IconComponent::class, $icon)->render()->toHtml();
            });

            return $this;
        };
    }

    public static function keyValues()
    {
        return function (int $maxDepth = 3): self {
            /** @var Cell $this */
            $column = $this->column;
            $this->render(function ($datum) use ($column, $maxDepth) {
                $value = $datum->getContent($column);
                if (is_scalar($value)) {
                    return e((string)$value);
                }
                if ($value === null) {
                    return '<i>null</i>';
                }
                $renderFn = static function ($record, $depth) use (&$renderFn): string {
                    $res = '';
                    foreach ($record as $key => $value) {
                        if ($value === null) {
                            $value = '<i>null</i>';
                        } elseif (is_scalar($value)) {
                            $value = e($value ?? '');
                        } elseif ($depth > 0) {
                            $value = $renderFn($value);
                        } else {
                            $value = '*';
                        }
                    }
                    $res .= sprintf('<dt>%s</dt><dd>%s</dd>', e($key), $value);
                    if ($res) {
                        $res = "<dl>$res</dl>";
                    }

                    return $res;
                };

                return $renderFn($value, $maxDepth);
            });

            return $this;
        };
    }

    public static function link(): callable
    {
        return function ($href, $segments = null): self {
            /** @var Cell $this */
            $column = $this->column;
            $this->render(function ($datum) use ($column, $href, $segments) {
                $href     = value($href, $datum);
                $segments = value($segments, $datum);
                if (is_array($segments)) {
                    $content = [];
                    foreach ($segments as $segment) {
                        $content[] = e(data_get($datum, $segment, ''));
                    }
                    $content = implode('<br>', $content);
                } else {
                    $content = e(data_get($datum, $column, ''));
                }

                return new HtmlString(
                    sprintf(
                        '<a data-turbo="true" class="btn-block btn btn-link fill-cell" href="%s">%s</a>',
                        e($href),
                        $content
                    )
                );
            });

            return $this;
        };
    }

    public static function renderable(): callable
    {
        return function (): self {
            /** @var Cell $this */
            $column = $this->column;
            $this->render(function ($datum) use ($column) {
                $value = $datum->getContent($column);

                if ($value === null) {
                    return '';
                }
                if (is_scalar($value)) {
                    return e($value);
                }

                if ($value instanceof Personable) {
                    return new Persona($value);
                }
                if (method_exists($value, 'presenter')) {
                    return new Persona($value->presenter());
                }
                if (method_exists($value, 'display')) {
                    return e($value->display());
                }

                return e($value->name ?? $value->slug ?? $value::class . '@' . $value->id);
            });

            return $this;
        };
    }


    public static function exportRender(): callable
    {
        return function ($renderer): self {
            /** @var Cell $this */
            $this->exportRenderMethod = $renderer;

            return $this;
        };
    }

    public static function exportGetValue(): callable
    {
        return function (...$arguments) {
            /** @var Cell $this */
            $callback = $this->exportRenderMethod ?? null;

            return $callback === null ? $arguments[0] : $callback(...$arguments);
        };
    }

    public static function notExportable(): callable
    {
        return function (bool $notExportable = true): self {
            /** @var Cell $this */
            $this->exportable = !$notExportable;

            return $this;
        };
    }

    public static function isExportable(): callable
    {
        return function (): bool {
            /** @var Cell $this */
            return $this->exportable ?? true;
        };
    }

    public static function getTitle(): callable
    {
        return function () {
            return $this->title;
        };
    }

    public static function getName(): callable
    {
        return function () {
            return $this->name;
        };
    }
}
