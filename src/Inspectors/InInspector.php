<?php

namespace Liyuze\PhpDataBag\Inspectors;

use Liyuze\PhpDataBag\Interfaces\IInspector;

class InInspector implements IInspector
{
    /**
     * @var array<mixed>
     */
    protected array $values = [];

    /**
     * @param array<mixed> $values
     */
    public function __construct($values)
    {
        $this->values = $values;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function isValid($value):bool
    {
        return ! in_array($value, $this->values, true);
    }
}