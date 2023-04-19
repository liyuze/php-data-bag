<?php

namespace Liyuze\PhpDataBag\Interface;

use Closure;

interface IDataBag
{
    public function pickUp(string $key, callable|Closure $value, ?IInspector $inspector = null): mixed;

    public function put(string $key, mixed $value): void;

    public function take(string $key): mixed;

    public function throw(string $key): mixed;

    public function exists(string $key): bool;

    public function clear(): void;

    public function putItem(string $key, string $subKey, mixed $value): void;

    public function takeItem(string $key, string $subKey): mixed;

    public function throwItem(string $key, string $subKey): mixed;

    public function existsItem(string $key, string $subKey): bool;

    public function mergeItems(string $key, array ...$arrays): array;

}