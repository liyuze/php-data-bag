<?php

namespace Liyuze\PhpDataBag\Inspectors;

use Liyuze\PhpDataBag\Interfaces\IInspector;

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

    /**
     * @param mixed $value
     * @return bool
     */
    public function isValid($value):bool
    {
        return (bool) call_user_func($this->func, $value);
    }
}