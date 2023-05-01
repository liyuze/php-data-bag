<?php

namespace Liyuze\PhpDataBag;

use Liyuze\PhpDataBag\Interface\IEscape;

class EscapeWrapper implements IEscape
{
    public function __construct(
        protected mixed $value
    ) {
    }

    public function getValue(): mixed
    {
        return $this->value;
    }
}