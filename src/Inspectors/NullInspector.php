<?php

namespace Liyuze\PhpDataBag\Inspectors;

use Liyuze\PhpDataBag\Interface\IInspector;

class NullInspector implements IInspector
{
    function isValid(mixed $value): bool
    {
        return $value !== null;
    }
}