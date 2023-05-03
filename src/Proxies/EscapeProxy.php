<?php

namespace Liyuze\PhpDataBag\Proxies;

use Liyuze\PhpDataBag\Interface\IEscape;

class EscapeProxy implements IEscape
{
    public function __construct(
        protected mixed $value
    ) {
    }
}