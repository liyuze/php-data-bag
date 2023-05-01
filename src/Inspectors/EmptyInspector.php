<?php

namespace Liyuze\PhpDataBag\Inspectors;

use Liyuze\PhpDataBag\Interface\IInspector;

class EmptyInspector implements IInspector
{
    public function isValid(mixed $value): bool
    {
        return ! empty($value);
    }
}