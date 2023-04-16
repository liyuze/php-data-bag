<?php

namespace Liyuze\PhpDataBag\Tests\Unit\Inspectors;

use Liyuze\PhpDataBag\Inspectors\NothingnessInspector;
use Liyuze\PhpDataBag\Tests\TestCase;

class NotingnessInspectorTest extends TestCase
{
    /**
     * @dataProvider dataList
     */
    public function test_isValid($value, $expected)
    {
        $obj = new NothingnessInspector();
        $this->assertEquals($obj->isValid($value), $expected);
    }

    public function dataList()
    {
        return [
            [null, true],
            [0, true],
            ['0', true],
            ['', true],
            [' ', true],
            [[], true],
            [true, true],
            [true, true],
        ];
    }
}