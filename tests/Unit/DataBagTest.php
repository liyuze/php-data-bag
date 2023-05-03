<?php

namespace Liyuze\PhpDataBag\Tests\Unit;

use Liyuze\PhpDataBag\DataBag;
use Liyuze\PhpDataBag\Interface\IDataBag;
use Liyuze\PhpDataBag\Tests\TestCase;

class DataBagTest extends TestCase
{
    protected IDataBag $bag;

    public function setUp(): void
    {
        parent::setUp();
        $this->bag = new DataBag();
    }

    /**
     * @return DataBag
     */
    public function test_instantiate()
    {
        $obj = new DataBag();
        self::assertTrue(true);

        return $obj;
    }

    public function test_take_notExistsKey_returnNull()
    {
        self::assertNull($this->bag->take('not_exists_key'));
    }

    public function test_put_putValue_takeSameValue()
    {
        $this->bag->put('a', 1);
        self::assertEquals(1, $this->bag->take('a'));
        $obj = new \stdClass();
        $this->bag->put('obj', $obj);
        self::assertSame($obj, $this->bag->take('obj'));
    }

    public function test_exists()
    {
        $this->bag->put('a', 1);
        self::assertTrue($this->bag->exists('a'));
        self::assertFalse($this->bag->exists('not_exists_key'));
    }


    function test_exists_bothExists_returnTrue()
    {
        $this->bag->put('a', 1);
        $this->bag->put('b', 1);
        self::assertTrue($this->bag->exists('a', 'b'));
    }

    function test_exists_notBothExists_returnFalse()
    {
        $this->bag->put('a', 1);
        self::assertFalse($this->bag->exists('a', 'b'));
    }

    function test_exists_noOneExists_returnFalse()
    {
        self::assertFalse($this->bag->exists('not_exists_key'));
    }

    function test_existsAny_bothExists_returnTrue()
    {
        $this->bag->put('a', 1);
        $this->bag->put('b', 1);
        self::assertTrue($this->bag->existsAny('a', 'b'));
    }

    function test_existsAny_notBothExists_returnTrue()
    {
        $this->bag->put('a', 1);
        self::assertTrue($this->bag->existsAny('a', 'b'));
    }

    function test_existsAny_noOneExists_returnFalse()
    {
        self::assertFalse($this->bag->existsAny('not_exists_key'));
    }


    public function test_putNullValue_existsReturnTrue()
    {
        $this->bag->put('a', null);
        self::assertTrue($this->bag->exists('a'));
    }

    public function test_pickUpClosure_callClosure()
    {
        $times = 0;
        $closure = function () use (&$times) {
            $times++;

            return $times;
        };

        self::assertEquals(1, $this->bag->pickUp('b', $closure));
        self::assertEquals(1, $this->bag->pickUp('b', $closure));
        $this->bag->throw('b');
        self::assertFalse($this->bag->exists('b'));
        self::assertEquals(2, $this->bag->pickUp('b', $closure));
        self::assertEquals(2, $this->bag->take('b'));
    }

    public function test_pickUpCallable_callCallable()
    {
        self::assertEquals(0, $this->bag->pickUp('c', [$this, 'popNumber']));
        self::assertEquals(0, $this->bag->pickUp('c', [$this, 'popNumber']));
        $this->bag->throw('c');
        self::assertFalse($this->bag->exists('c'));
        self::assertEquals(1, $this->bag->pickUp('c', [$this, 'popNumber']));
        self::assertEquals(1, $this->bag->take('c'));
    }

    public function popNumber()
    {
        static $num = 0;

        return $num++;
    }

    public function test_throw()
    {
        $this->bag->put('a', 1);
        self::assertEquals(1, $this->bag->throw('a'));
        self::assertNull($this->bag->take('a'));
        self::assertNull($this->bag->throw('no_exists_key'));
    }

    public function test_getAll()
    {
        $this->bag->put('a', 1);
        $this->bag->put('b', 2);
        $this->assertEquals(['a' => 1, 'b' => 2], $this->bag->getAll());
    }

    public function test_clear()
    {
        $this->bag->put('a', 1);
        $this->bag->put('b', 2);
        $this->bag->clear();
        self::assertNull($this->bag->take('a'));
        self::assertNull($this->bag->take('b'));
        self::assertNull($this->bag->take('no_exists_key'));
        self::assertEmpty($this->bag->getAll());
    }

    public function test_takeItem_notExistsKey_returnNull()
    {
        $this->bag->putItem('arr', 'a', 1);
        self::assertNull($this->bag->takeItem('arr', 'not_exists_key'));
        self::assertNull($this->bag->takeItem('not_exists_key', 'not_exists_key'));
    }

    public function test_putItem_takeItemSame()
    {
        $this->bag->putItem('arr', 'a', 1);
        $obj = new \stdClass();
        $this->bag->putItem('arr', 'b', $obj);

        $value = $this->bag->take('arr');
        self::assertSame(1, $this->bag->takeItem('arr', 'a'));
        self::assertSame($obj, $this->bag->takeItem('arr', 'b'));
    }

    public function test_putItem_takeValue()
    {
        $this->bag->putItem('arr', 'a', 1);
        $this->bag->putItem('arr', 'b', 2);

        $value = $this->bag->take('arr');
        self::assertCount(2, $value);
        self::assertEquals(3, array_sum($value));
    }

    public function test_throwItem()
    {
        $this->bag->putItem('arr', 'a', 1);
        self::assertEquals(1, $this->bag->throwItem('arr', 'a'));
        self::assertNull($this->bag->takeItem('arr', 'a'));
        self::assertNull($this->bag->throwItem('arr', 'no_exists_key'));
        self::assertNull($this->bag->throwItem('no_exists_key', 'no_exists_key'));
    }

    public function test_existsItem()
    {
        $this->bag->putItem('arr', 'a', 1);
        self::assertTrue($this->bag->existsItem('arr', 'a'));
        self::assertFalse($this->bag->existsItem('arr', 'not_exists_key'));
        self::assertFalse($this->bag->existsItem('not_exists_key', 'not_exists_key'));
    }

    public function test_mergeItems()
    {
        $this->bag->put('arr', []);
        $v1 = $this->bag->mergeItems('arr', [1, 2, 3]);
        self::assertCount(3, $v1);
        self::assertEquals(6, array_sum($v1));


        $v2 = $this->bag->mergeItems('arr', [4, 5, 6]);
        self::assertCount(6, $v2);
        self::assertEquals(21, array_sum($v2));

        $value = $this->bag->take('arr');
        self::assertCount(6, $value);
        self::assertEquals(21, array_sum($value));
    }

    public function test_mergeItems_multipleArrayValues()
    {
        $this->bag->put('data', []);
        $this->bag->mergeItems('data', [1, 2, 3], [4, 5, 6]);
        $value = $this->bag->take('data');
        self::assertCount(6, $value);
        self::assertEquals(21, array_sum($value));
    }

    public function test_mergeItems_notMatchDataType()
    {
        $this->expectException(\RuntimeException::class);
        $this->bag->put('data', 1);
        $this->bag->mergeItems('data', [1, 2, 3]);
    }

    public function test_isGreed_isTrue_alwaysRunCallable()
    {
        $times = 0;
        $closure = function () use (&$times) {
            return $times++;
        };
        $this->bag->setIsGreedy(true);
        self::assertEquals(0, $this->bag->pickUp('c', $closure), 'first time');
        self::assertEquals(1, $this->bag->pickUp('c', $closure), 'second time');
        $this->bag->throw('c');
        self::assertFalse($this->bag->exists('c'));
        self::assertEquals(2, $this->bag->pickUp('c', $closure), 'third time');
        self::assertEquals(2, $this->bag->take('c'));
    }

    public function test_runInGreedMode_alwaysRunCallable()
    {
        $times = 0;
        $closure = function () use (&$times) {
            return $times++;
        };
        $this->bag->runInGreedyMode(function () use ($closure) {
            self::assertEquals(0, $this->bag->pickUp('c', $closure), 'first time');
            self::assertEquals(1, $this->bag->pickUp('c', $closure), 'second time');
            $this->bag->throw('c');
            self::assertFalse($this->bag->exists('c'));
            self::assertEquals(2, $this->bag->pickUp('c', $closure), 'third time');
            self::assertEquals(2, $this->bag->take('c'));
        });
    }
}