<?php

namespace Liyuze\PhpDataBag\Inspectors;

use Liyuze\PhpDataBag\Interface\IInspector;

class NothingnessInspector implements IInspector
{
    function isValid(mixed $value): bool
    {
        return true;
    }

}