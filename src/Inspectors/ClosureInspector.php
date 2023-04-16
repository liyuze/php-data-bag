<?php

namespace Liyuze\PhpDataBag\Inspectors;

use Closure;
use Liyuze\PhpDataBag\Interface\IInspector;

class ClosureInspector implements IInspector
{
    public array|Closure $func;

    public function __construct(callable|Closure $func)
    {
        $this->func = $func;
    }

    function isValid(mixed $value): bool
    {
        return (bool) call_user_func($this->func, $value);
    }
}