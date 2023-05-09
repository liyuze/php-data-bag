<?php

namespace Liyuze\PhpDataBag\Inspectors;

use Liyuze\PhpDataBag\Interfaces\IInspector;

class NullInspector implements IInspector
{
    /**
     * @param mixed $value
     * @return bool
     */
    public function isValid($value):bool
    {
        return $value !== null;
    }
}