<?php

namespace Liyuze\PhpDataBag\Tests\Unit;

use Liyuze\PhpDataBag\ArraySandbox;
use Liyuze\PhpDataBag\Interface\ISandbox;
use Liyuze\PhpDataBag\Tests\TestCase;
use ReflectionClass;

class ArraySandboxTest extends TestCase
{
    public function testNewObj()
    {
        $sandbox = new ArraySandbox();

        self::assertInstanceOf(ISandbox::class, $sandbox);
        $data = $this->getSandboxValue($sandbox);
        self::assertEmpty($data);

        return $sandbox;
    }

    /**
     * @depends testNewObj
     * @param  ArraySandbox  $sandbox
     * @return ArraySandbox
     */
    function testSet(ArraySandbox $sandbox)
    {
        $sandbox->set('a', 1);
        $data = $this->getSandboxValue($sandbox);
        self::assertCount(1, $data);
        self::assertTrue(key_exists('a', $data));
        self::assertEquals(1, $data['a']);

        return $sandbox;
    }

    /**
     * @depends testSet
     * @param  ArraySandbox  $sandbox
     * @return ArraySandbox
     */
    function testGet(ArraySandbox $sandbox)
    {
        $value = $sandbox->get('a');
        self::assertEquals(1, $value);
        self::assertNull($sandbox->get('not_exists_key'));

        return $sandbox;
    }

    /**
     * @depends testSet
     * @param  ArraySandbox  $sandbox
     * @return ArraySandbox
     */
    function testExists(ArraySandbox $sandbox)
    {
        self::assertTrue($sandbox->exists('a'));
        self::assertFalse($sandbox->exists('not_exists_key'));

        return $sandbox;
    }

    /**
     * @depends testSet
     * @param  ArraySandbox  $sandbox
     * @return ArraySandbox
     */
    function testDelete(ArraySandbox $sandbox)
    {
        $sandbox->delete('a');
        self::assertNull($sandbox->get('a'));
        $sandbox->delete('not_exists_key');

        return $sandbox;
    }


    /**
     * @depends testNewObj
     * @param  ArraySandbox  $sandbox
     * @return void
     */
    function testClear(ArraySandbox $sandbox)
    {
        $sandbox->set('a', 1);
        $sandbox->clear();
        $data = $this->getSandboxValue($sandbox);
        self::assertEmpty($data);
    }

    public function getSandboxValue(ArraySandbox $sandbox)
    {
        $reflectedClass = new ReflectionClass(ArraySandbox::class);
        try {
            $reflectedProperty = $reflectedClass->getProperty('data');
        } catch (\ReflectionException $e) {
            self::assertTrue((bool) false, __METHOD__." can't get value for data property ");
        }
        $reflectedProperty->setAccessible(true);

        return $reflectedProperty->getValue($sandbox);
    }
}