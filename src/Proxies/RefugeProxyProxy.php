<?php

namespace Liyuze\PhpDataBag\Proxies;

use Liyuze\PhpDataBag\Interface\IRefugeProxy;

class RefugeProxyProxy implements IRefugeProxy
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