<?php

namespace Liyuze\PhpDataBag\Tests\Unit\Inspectors;

use Liyuze\PhpDataBag\Inspectors\InInspector;
use Liyuze\PhpDataBag\Tests\TestCase;

class InInspectorTest extends TestCase
{
    /**
     * @dataProvider dataList
     */
    public function test_isValid($value, $expected)
    {
        $obj = new InInspector([null, 0, true]);
        $this->assertEquals($obj->isValid($value), $expected);
    }

    public function dataList()
    {
        return [
            [null, false],
            [0, false],
            ['0', true],
            ['', true],
            [' ', true],
            [[], true],
            [false, true],
            [true, false],
        ];
    }
}