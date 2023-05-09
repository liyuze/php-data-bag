<?php

namespace Liyuze\PhpDataBag\Proxies;

use Liyuze\PhpDataBag\Interfaces\IRefuge;

class RefugeProxy implements IRefuge
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


    /**
     * @return mixed
     */
    public function getProxyValue()
    {
        return $this->value;
    }
}