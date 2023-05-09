<?php

namespace Liyuze\PhpDataBag\Inspectors;

use Liyuze\PhpDataBag\Interfaces\IInspector;

class EmptyInspector implements IInspector
{
    /**
     * @param mixed $value
     * @return bool
     */
    public function isValid($value):bool
    {
        return ! empty($value);
    }
}