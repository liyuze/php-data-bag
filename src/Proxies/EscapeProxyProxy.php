<?php

namespace Liyuze\PhpDataBag\Proxies;

use Liyuze\PhpDataBag\Interface\IEscapeProxy;

class EscapeProxyProxy implements IEscapeProxy
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