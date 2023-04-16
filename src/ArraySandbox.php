<?php

namespace Liyuze\PhpDataBag;

use Liyuze\PhpDataBag\Interface\ISandbox;

class ArraySandbox implements ISandbox
{
    protected array $data = [];

    function set(string $key, mixed $value): void
    {
        $this->data[$key] = $value;
    }

    function get(string $key): mixed
    {
        return $this->data[$key] ?? null;
    }

    function delete(string $key): void
    {
        unset($this->data[$key]);
    }

    function exists(string $key): bool
    {
        return key_exists($key, $this->data);
    }

    function clear(): void
    {
        $this->data = [];
    }
}