<?php

namespace Liyuze\PhpDataBag\Tests\Unit\Proxies;

use Liyuze\PhpDataBag\DataBag;
use Liyuze\PhpDataBag\Inspectors\EmptyInspector;
use Liyuze\PhpDataBag\Interfaces\IDataBag;
use Liyuze\PhpDataBag\Proxies\RefugeProxy;
use Liyuze\PhpDataBag\Tests\TestCase;

class RefugeProxyTest extends TestCase
{
    protected IDataBag $bag;

    public function setUp(): void
    {
        parent::setUp();
        $this->bag = new DataBag();
        $this->bag->setInspector(new EmptyInspector());
    }

    public function test_put_refugeValue_normalCache()
    {
        $this->bag->put('a', new RefugeProxy(0));
        self::assertEquals(0 , $this->bag->take('a'));
    }

    public function test_putItem_refugeValue_normalCache()
    {
        $this->bag->putItem('arr', 'a', new RefugeProxy(false));
        self::assertFalse($this->bag->takeItem('arr', 'a'));
    }

    public function test_mergeItems_notFilterRefugeValue()
    {
        $this->bag->mergeItems('arr', [1, new RefugeProxy(0), 'c' => 3]);
        self::assertCount(3, $this->bag->take('arr'));
        self::assertEquals(1, $this->bag->takeItem('arr', 0));
        self::assertEquals(0, $this->bag->takeItem('arr', 1));
        self::assertEquals(3, $this->bag->takeItem('arr', 'c'));
    }
}