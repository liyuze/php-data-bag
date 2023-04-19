<?php

namespace Liyuze\PhpDataBag\Tests\Unit;

use Liyuze\PhpDataBag\DataBag;
use Liyuze\PhpDataBag\Tests\TestCase;

class DataBagTest extends TestCase
{
    /**
     * @return DataBag
     */
    public function test_instantiate()
    {

        $obj = new DataBag();
        self::assertTrue(true);

        return $obj;
    }

    /**
     * @depends clone test_instantiate
     */
    public function test_take_notExistsKey_returnNull(DataBag $bag)
    {
        self::assertNull($bag->take('not_exists_key'));
    }

    /**
     * @depends clone test_instantiate
     */
    public function test_putValue_takeSameValue(DataBag $bag)
    {
        $bag->put('a', 1);
        self::assertEquals(1, $bag->take('a'));
        $obj = new \stdClass();
        $bag->put('obj', $obj);
        self::assertSame($obj, $bag->take('obj'));

        return $bag;
    }

    /**
     * @depends clone test_instantiate
     */
    public function test_exists(DataBag $bag)
    {
        $bag->put('a', 1);
        self::assertTrue($bag->exists('a'));
        self::assertFalse($bag->exists('not_exists_key'));
    }

    /**
     * @depends clone test_instantiate
     */
    public function test_putNullValue_existsReturnTrue(DataBag $bag)
    {
        $bag->put('a', null);
        self::assertTrue($bag->exists('a'));
    }

    /**
     * @depends clone test_instantiate
     */
    public function test_pickUpClosure_callClosure(DataBag $bag)
    {
        $times = 0;
        $closure = function () use (&$times) {
            $times++;

            return $times;
        };

        self::assertEquals(1, $bag->pickUp('b', $closure));
        self::assertEquals(1, $bag->pickUp('b', $closure));
        $bag->throw('b');
        self::assertFalse($bag->exists('b'));
        self::assertEquals(2, $bag->pickUp('b', $closure));
        self::assertEquals(2, $bag->take('b'));
    }

    /**
     * @depends clone test_instantiate
     */
    public function test_pickUpCallable_callCallable(DataBag $bag)
    {
        self::assertEquals(0, $bag->pickUp('c', [$this, 'popNumber']));
        self::assertEquals(0, $bag->pickUp('c', [$this, 'popNumber']));
        $bag->throw('c');
        self::assertFalse($bag->exists('c'));
        self::assertEquals(1, $bag->pickUp('c', [$this, 'popNumber']));
        self::assertEquals(1, $bag->take('c'));
    }

    public function popNumber()
    {
        static $num = 0;

        return $num++;
    }

    /**
     * @depends clone test_instantiate
     */
    public function test_throw(DataBag $bag)
    {
        $bag->put('a', 1);
        self::assertEquals(1, $bag->throw('a'));
        self::assertNull($bag->take('a'));
        self::assertNull($bag->throw('no_exists_key'));
    }

    /**
     * @depends clone test_instantiate
     */
    public function test_clear(DataBag $bag)
    {
        $bag->put('a', 1);
        $bag->put('b', 2);
        $bag->clear();
        self::assertNull($bag->take('a'));
        self::assertNull($bag->take('b'));
        self::assertNull($bag->take('no_exists_key'));
    }


    /**
     * @depends clone test_instantiate
     */
    public function test_takeItem_notExistsKey_returnNull(DataBag $bag)
    {
        $bag->putItem('arr', 'a', 1);
        self::assertNull($bag->takeItem('arr', 'not_exists_key'));
        self::assertNull($bag->takeItem('not_exists_key', 'not_exists_key'));
    }

    /**
     * @depends clone test_instantiate
     */
    public function test_putItem_takeItemSame(DataBag $bag)
    {
        $bag->putItem('arr', 'a', 1);
        $obj = new \stdClass();
        $bag->putItem('arr', 'b', $obj);

        $value = $bag->take('arr');
        self::assertSame(1, $bag->takeItem('arr', 'a'));
        self::assertSame($obj, $bag->takeItem('arr', 'b'));
    }

    /**
     * @depends clone test_instantiate
     */
    public function test_putItem_takeValue(DataBag $bag)
    {
        $bag->putItem('arr', 'a', 1);
        $bag->putItem('arr', 'b', 2);

        $value = $bag->take('arr');
        self::assertCount(2, $value);
        self::assertEquals(3, array_sum($value));
    }

    /**
     * @depends clone test_instantiate
     */
    public function test_throwItem(DataBag $bag)
    {
        $bag->putItem('arr', 'a', 1);
        self::assertEquals(1, $bag->throwItem('arr', 'a'));
        self::assertNull($bag->takeItem('arr', 'a'));
        self::assertNull($bag->throwItem('arr', 'no_exists_key'));
        self::assertNull($bag->throwItem('no_exists_key', 'no_exists_key'));
    }

    /**
     * @depends clone test_instantiate
     */
    public function test_existsItem(DataBag $bag)
    {
        $bag->putItem('arr', 'a', 1);
        self::assertTrue($bag->existsItem('arr', 'a'));
        self::assertFalse($bag->existsItem('arr', 'not_exists_key'));
        self::assertFalse($bag->existsItem('not_exists_key', 'not_exists_key'));
    }

    /**
     * @depends clone test_instantiate
     */
    public function test_mergeItems(DataBag $bag)
    {
        $bag->put('arr', []);
        $v1 = $bag->mergeItems('arr', [1, 2, 3]);
        self::assertCount(3, $v1);
        self::assertEquals(6, array_sum($v1));


        $v2 = $bag->mergeItems('arr', [4, 5, 6]);
        self::assertCount(6, $v2);
        self::assertEquals(21, array_sum($v2));

        $value = $bag->take('arr');
        self::assertCount(6, $value);
        self::assertEquals(21, array_sum($value));
    }

    /**
     * @depends clone test_instantiate
     */
    public function test_mergeItems_multipleArrayValues(DataBag $bag)
    {
        $bag->put('data', []);
        $bag->mergeItems('data', [1, 2, 3], [4, 5, 6]);
        $value = $bag->take('data');
        self::assertCount(6, $value);
        self::assertEquals(21, array_sum($value));
    }

    /**
     * @depends clone test_instantiate
     */
    public function test_mergeItems_notMatchDataType(DataBag $bag)
    {
        $this->expectException(\RuntimeException::class);
        $bag->put('data', 1);
        $bag->mergeItems('data', [1, 2, 3]);
    }

    /**
     * @depends clone test_instantiate
     */
    public function test_isGreed_isTrue_alwaysRunCallable(DataBag $bag)
    {
        $times = 0;
        $closure = function () use (&$times) {
            return $times++;
        };
        $bag->setIsGreedy(true);
        self::assertEquals(0, $bag->pickUp('c', $closure), 'first time');
        self::assertEquals(1, $bag->pickUp('c', $closure), 'second time');
        $bag->throw('c');
        self::assertFalse($bag->exists('c'));
        self::assertEquals(2, $bag->pickUp('c', $closure), 'third time');
        self::assertEquals(2, $bag->take('c'));
    }

    /**
     * @depends clone test_instantiate
     */
    public function test_runInGreedMode_alwaysRunCallable(DataBag $bag)
    {
        $times = 0;
        $closure = function () use (&$times) {
            return $times++;
        };
        $bag->runInGreedyMode(function() use ($bag, $closure) {
            self::assertEquals(0, $bag->pickUp('c', $closure), 'first time');
            self::assertEquals(1, $bag->pickUp('c', $closure), 'second time');
            $bag->throw('c');
            self::assertFalse($bag->exists('c'));
            self::assertEquals(2, $bag->pickUp('c', $closure), 'third time');
            self::assertEquals(2, $bag->take('c'));
        });
    }
}