<?php

namespace Liyuze\PhpDataBag\Tests\Unit\Inspectors;

use Liyuze\PhpDataBag\Inspectors\ClosureInspector;
use Liyuze\PhpDataBag\Tests\TestCase;

class ClosureInspectorTest extends TestCase
{
    /**
     * @dataProvider dataList
     */
    public function test_isValid_Callable($value, $expected)
    {
        $obj = new ClosureInspector([$this, 'checkValue']);
        $this->assertEquals($obj->isValid($value), $expected);
    }

    public function checkValue($value)
    {
        $data = $this->dataList();
        foreach ($data as $v) {
            if ($v[0] === $value) {
                return $v[1];
            }
        }
    }

    /**
     * @dataProvider dataList
     */
    public function test_isValid_Closure($value, $expected)
    {
        $obj = new ClosureInspector(
            function ($value) {
                $data = $this->dataList();
                foreach ($data as $v) {
                    if ($v[0] === $value) {
                        return $v[1];
                    }
                }
            }
        );
        $this->assertEquals($obj->isValid($value), $expected);
    }

    public function dataList()
    {
        return [
            [null, false],
            [0, true],
            ['0', false],
            ['', true],
            [' ', true],
            [[], false],
            [false, true],
            [true, true],
        ];
    }
}