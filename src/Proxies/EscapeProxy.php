<?php

namespace Liyuze\PhpDataBag\Proxies;

use Liyuze\PhpDataBag\Interfaces\IEscape;

class EscapeProxy implements IEscape
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }
}