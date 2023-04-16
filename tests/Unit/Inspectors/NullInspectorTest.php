<?php

namespace Liyuze\PhpDataBag\Tests\Unit\Inspectors;

use Liyuze\PhpDataBag\Inspectors\NullInspector;
use Liyuze\PhpDataBag\Tests\TestCase;

class NullInspectorTest extends TestCase
{
    /**
     * @dataProvider dataList
     */
    public function test_isValid($value, $expected)
    {
        $obj = new NullInspector();
        $this->assertEquals($obj->isValid($value), $expected);
    }

    public function dataList()
    {
        return [
            [null, false],
            [0, true],
            ['0', true],
            ['', true],
            [' ', true],
            [[], true],
            [false, true],
            [true, true],
        ];
    }
}