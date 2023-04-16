<?php

namespace Liyuze\PhpDataBag\Interface;

interface ISandbox
{
    public function set(string $key, mixed $value): void;

    public function get(string $key): mixed;

    public function delete(string $key): void;

    public function exists(string $key): bool;

    public function clear(): void;
}