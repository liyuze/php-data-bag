<?php

namespace Liyuze\PhpDataBag;

use Liyuze\PhpDataBag\Interface\ISandbox;

class ArraySandbox implements ISandbox
{
    /**
     * @var mixed[]
     */
    protected array $data = [];

    public function set(string $key, mixed $value): void
    {
        $this->data[$key] = $value;
    }

    public function get(string $key): mixed
    {
        return $this->data[$key] ?? null;
    }

    public function delete(string $key): void
    {
        unset($this->data[$key]);
    }

    public function exists(string $key): bool
    {
        return key_exists($key, $this->data);
    }

    public function getAll(): array
    {
        return $this->data;
    }

    public function clear(): void
    {
        $this->data = [];
    }
}