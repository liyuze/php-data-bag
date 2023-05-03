<?php

namespace Liyuze\PhpDataBag\Interface;

interface ISandbox
{
    public function set(string $key, mixed $value): void;

    /**
     * @param  string  $key
     * @return mixed|array
     */
    public function get(string $key): mixed;

    public function delete(string $key): void;

    public function exists(string $key): bool;

    /**
     * @return array<int|string, mixed>
     */
    public function getAll(): array;

    public function clear(): void;
}