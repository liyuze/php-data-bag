<?php

namespace Liyuze\PhpDataBag\Interfaces;

interface ISandbox
{
    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set(string $key, $value):void;

    /**
     * @param string $key
     * @return mixed|array
     */
    public function get(string $key);

    public function delete(string $key):void;

    public function exists(string $key):bool;

    /**
     * @return array<int|string, mixed>
     */
    public function getAll():array;

    public function clear():void;
}