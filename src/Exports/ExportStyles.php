<?php

declare(strict_types=1);

namespace Lintaba\OrchidTables\Exports;

use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

interface ExportStyles
{
    public const FORMAT_RED        = [
        'fill'  => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'FFEEEE']],
        'color' => ['rgb' => Color::COLOR_DARKRED],
    ];
    public const FORMAT_GREEN      = [
        'fill'  => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'EEFFEE']],
        'color' => ['rgb' => Color::COLOR_DARKGREEN],
    ];
    public const FORMAT_YELLOW     = [
        'fill'  => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'FFFFEE']],
        'color' => ['rgb' => Color::COLOR_YELLOW],
    ];
    public const FORMAT_BLUE       = [
        'fill'  => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'EEEEFF']],
        'color' => ['rgb' => Color::COLOR_BLUE],
    ];
    public const FORMAT_BLACK      = [
        'fill'  => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '000000']],
        'color' => ['rgb' => Color::COLOR_WHITE],
    ];
    public const FORMAT_NONE       = [];
    public const FORMAT_HUF        = ['numberFormat' => ['formatCode' => '#,###.??" Ft";(-#,###.??" Ft");0" Ft"']];
    public const FORMAT_USD        = ['numberFormat' => ['formatCode' => NumberFormat::FORMAT_CURRENCY_USD]];
    public const FORMAT_EUR        = ['numberFormat' => ['formatCode' => NumberFormat::FORMAT_CURRENCY_EUR]];
    public const FORMAT_DATE       = ['numberFormat' => ['formatCode' => 'yyyy-mm-dd']];
    public const FORMAT_DATETIME   = ['numberFormat' => ['formatCode' => 'yyyy-mm-dd h:mm']];
    public const FORMAT_TIME       = ['numberFormat' => ['formatCode' => 'h:mm']];
    public const FORMAT_PCS        = ['numberFormat' => ['formatCode' => '#,###" Db";(-#,###" Db");0" Db"']];
    public const FORMAT_TEXT       = ['numberFormat' => ['formatCode' => NumberFormat::FORMAT_TEXT]];
    public const FORMAT_BOLD       = ['font' => ['bold' => true]];
    public const FORMAT_ITALIC     = ['font' => ['italic' => true]];
    public const FORMAT_UNDERLINED = ['font' => ['italic' => true]];
    public const FORMAT_LEFT       = ['alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT,],];
    public const FORMAT_RIGHT      = ['alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT,],];
    public const FORMAT_CENTER     = ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER,],];
    public const FORMAT_TOP        = ['alignment' => ['vertical' => Alignment::VERTICAL_TOP,],];
    public const FORMAT_MIDDLE     = ['alignment' => ['vertical' => Alignment::VERTICAL_CENTER,],];
    public const FORMAT_BOTTOM     = ['alignment' => ['vertical' => Alignment::VERTICAL_BOTTOM,],];
}
