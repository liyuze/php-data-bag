<?php

namespace Liyuze\PhpDataBag\Interface;

interface IDataBag
{
    public function pickUp(string $key, callable $value, ?IInspector $inspector = null): mixed;

    public function put(string $key, mixed $value): void;

    public function take(string $key): mixed;

    public function throw(string $key): mixed;

    /**
     * @param  string|array<int|string>  $keys
     * @return bool
     */
    public function exists(string|array $keys): bool;

    /**
     * @param  string|array<int|string>  $keys
     * @return bool
     */
    public function existsAny(string|array $keys): bool;

    /**
     * @return array<int|string, mixed>
     */
    public function getAll(): array;

    public function clear(): void;

    public function putItem(string $key, string $subKey, mixed $value): void;

    public function takeItem(string $key, string $subKey): mixed;

    public function throwItem(string $key, string $subKey): mixed;

    public function existsItem(string $key, string $subKey): bool;

    /**
     * @param  string  $key
     * @param  array<array<mixed, mixed>>  ...$arrays
     * @return mixed[]
     */
    public function mergeItems(string $key, array ...$arrays): array;
}