<?php

namespace Liyuze\PhpDataBag\Tests\Unit\Proxies;

use Liyuze\PhpDataBag\DataBag;
use Liyuze\PhpDataBag\Interfaces\IDataBag;
use Liyuze\PhpDataBag\Proxies\EscapeProxy;
use Liyuze\PhpDataBag\Tests\TestCase;

class EscapeProxyTest extends TestCase
{
    protected IDataBag $bag;

    public function setUp(): void
    {
        parent::setUp();
        $this->bag = new DataBag();
    }

    public function test_put_escapeValue_doNothing()
    {
        $this->bag->put('a', new EscapeProxy(1));
        self::assertNull($this->bag->take('a'));
    }

    public function test_putItem_escapeValue_doNothing()
    {
        $this->bag->putItem('arr', 'a', new EscapeProxy(1));
        self::assertNull($this->bag->takeItem('arr', 'a'));
    }

    public function test_mergeItems_filterEscapeValue()
    {
        $this->bag->mergeItems('arr', [1, new EscapeProxy(2), 'c' => 3]);
        self::assertCount(2, $this->bag->take('arr'));
        self::assertEquals(1, $this->bag->takeItem('arr', 0));
        self::assertNull($this->bag->takeItem('arr', 1));
        self::assertEquals(3, $this->bag->takeItem('arr', 'c'));
    }
}