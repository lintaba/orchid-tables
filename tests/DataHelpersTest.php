<?php

declare(strict_types=1);

namespace Lintaba\OrchidTables;

use PHPUnit\Framework\TestCase;

class DataHelpersTest extends TestCase
{
    /** @dataProvider provideNumberFormat */
    public function testFormatNum($input, $expected, ...$args)
    {
        /**
         * @param string|int|float|null $val
         * @param int                   $decimals
         * @param string|null           $suffix
         * @param string                $decimalSeparator
         * @param string                $thousandsSeparator
         *
         * @return string
         */
        $this->assertSame($expected, DataHelpers::formatNum($input, ...$args));
    }

    public function provideNumberFormat()
    {
        return [
            'regular number' => [1,'1'],
            'regular negative number' => [-1,'-1'],
            '+float, should round' => [1.9,'2'],
            '-float, should round' => [-1.9,'-2'],
            'decimals 2' => [1.23456,'1,23',2],
            'decimals 5' => [1.23456,'1,23456',5],
            'decimals keep' => [1.1,'1,10',2],
            'suffix' => [42,"42\u{00a0}X",0,'X'],
            'suffix no' => [42,"42",0,null],
            'suffix no2' => [42,"42",0,''],
            'decsep' => [42.1234,"42@12",2,null,'@'],
            'thsep' => [12345678,"12_345_678",0,null,'@','_'],
            'mixed' => [12345678.1234,"12_345_678@123\u{00a0}🦄",3,'🦄','@','_'],
        ];
    }

    public function testCheckAnyPermits()
    {
        $this->markTestIncomplete('wip');
    }

    public function testCheckAllPermit()
    {
        $this->markTestIncomplete('wip');
    }
}
