<?php

namespace Liyuze\PhpDataBag\Tests\Unit\Inspectors;

use Liyuze\PhpDataBag\Inspectors\EmptyInspector;
use Liyuze\PhpDataBag\Tests\TestCase;

class EmptyInspectorTest extends TestCase
{
    /**
     * @dataProvider dataList
     */
    public function test_isValid($value, $expected)
    {
        $obj = new EmptyInspector();
        $this->assertEquals($obj->isValid($value), $expected);
    }

    public function dataList()
    {
        return [
            [null, false],
            [0, false],
            ['0', false],
            ['', false],
            [' ', true],
            [[], false],
            [false, false],
            [true, true],
        ];
    }
}