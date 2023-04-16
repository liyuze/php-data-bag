<?php

namespace Liyuze\PhpDataBag\Inspectors;

use Liyuze\PhpDataBag\Interface\IInspector;

class EmptyInspector implements IInspector
{
    function isValid(mixed $value): bool
    {
        return !! $value;
    }
}