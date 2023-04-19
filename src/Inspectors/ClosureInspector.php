<?php

namespace Liyuze\PhpDataBag\Inspectors;

use Closure;
use Liyuze\PhpDataBag\Interface\IInspector;

class ClosureInspector implements IInspector
{
    /**
     * @var callable
     */
    public $func;

    public function __construct(callable $func)
    {
        $this->func = $func;
    }

    function isValid(mixed $value): bool
    {
        return (bool) call_user_func($this->func, $value);
    }
}