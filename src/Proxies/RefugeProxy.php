<?php

namespace Liyuze\PhpDataBag\Proxies;

use Liyuze\PhpDataBag\Interface\IRefuge;

class RefugeProxy implements IRefuge
{
    public function __construct(
        protected mixed $value
    ) {
    }

    public function getProxyValue(): mixed
    {
        return $this->value;
    }
}