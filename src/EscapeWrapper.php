<?php

namespace Liyuze\PhpDataBag;

use Liyuze\PhpDataBag\Interface\IEscape;

class EscapeWrapper implements IEscape
{
    public function __construct(
        protected $value
    ) {
    }

    function getValue()
    {
        return $this->value;
    }
}