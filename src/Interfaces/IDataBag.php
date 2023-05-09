<?php

namespace Liyuze\PhpDataBag\Interfaces;

interface IDataBag
{
    /**
     * @param string $key
     * @param callable $value
     * @param IInspector|null $inspector
     * @return mixed
     */
    public function pickUp(string $key, callable $value, ?IInspector $inspector = null);

    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function put(string $key, $value):void;

    /**
     * @param string $key
     * @return mixed
     */
    public function take(string $key);

    /**
     * @param string $key
     * @return mixed
     */
    public function throw(string $key);

    public function exists(string ...$keys):bool;

    public function existsAny(string ...$keys):bool;

    /**
     * @return array<int|string, mixed>
     */
    public function getAll():array;

    public function clear():void;

    /**
     * @param string $key
     * @param string $subKey
     * @param mixed $value
     * @return void
     */
    public function putItem(string $key, string $subKey, $value):void;

    /**
     * @param string $key
     * @param string $subKey
     * @return mixed
     */
    public function takeItem(string $key, string $subKey);

    /**
     * @param string $key
     * @param string $subKey
     * @return mixed
     */
    public function throwItem(string $key, string $subKey);

    public function existsItem(string $key, string ...$subKey):bool;

    public function existsAnyItem(string $key, string ...$subKey):bool;

    /**
     * @param string $key
     * @param array<array<int|string, mixed>> ...$arrays
     * @return mixed[]
     */
    public function mergeItems(string $key, array ...$arrays):array;
}