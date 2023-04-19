<?php

namespace Liyuze\PhpDataBag\Tests\Unit;

use Liyuze\PhpDataBag\ArraySandbox;
use Liyuze\PhpDataBag\Interface\ISandbox;
use Liyuze\PhpDataBag\Tests\TestCase;
use ReflectionClass;

class ArraySandboxTest extends TestCase
{
    protected ISandbox $sandbox;

    public function setUp(): void
    {
        parent::setUp();
        $this->sandbox = new ArraySandbox();
    }

    public function testNewObj()
    {
        self::assertInstanceOf(ISandbox::class, $this->sandbox);
        $data = $this->getSandboxValue($this->sandbox);
        self::assertEmpty($data);

        return $this->sandbox;
    }

    function testSet()
    {
        $this->sandbox->set('a', 1);
        $data = $this->getSandboxValue($this->sandbox);
        self::assertCount(1, $data);
        self::assertTrue(key_exists('a', $data));
        self::assertEquals(1, $data['a']);
    }

    function testGet()
    {
        $this->sandbox->set('a', 1);
        self::assertEquals(1, $this->sandbox->get('a'));
        self::assertNull($this->sandbox->get('not_exists_key'));
    }

    function testExists()
    {
        $this->sandbox->set('a', 1);
        self::assertTrue($this->sandbox->exists('a'));
        self::assertFalse($this->sandbox->exists('not_exists_key'));
    }

    function testDelete()
    {
        $this->sandbox->set('a', 1);
        $this->sandbox->delete('a');
        self::assertNull($this->sandbox->get('a'));
        $this->sandbox->delete('not_exists_key');
    }

    function testClear()
    {
        $this->sandbox->set('a', 1);
        $this->sandbox->clear();
        $data = $this->getSandboxValue($this->sandbox);
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