<?php

namespace Liyuze\PhpDataBag\Inspectors;

use Liyuze\PhpDataBag\Interface\IInspector;

class InInspector implements IInspector
{
    /**
     * @param  mixed[]  $values
     */
    public function __construct(
        protected array $values,
    ) {
    }

    public function isValid(mixed $value): bool
    {
        return ! in_array($value, $this->values, true);
    }
}