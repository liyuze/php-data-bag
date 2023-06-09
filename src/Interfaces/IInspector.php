<?php

namespace Liyuze\PhpDataBag\Interfaces;

/**
 * 检查器
 * 当  pick up 缓存数据时，检查器判断缓存值为无效数据时将跳过缓存
 * 当任何值都需要被缓存时，可以传递 NothingnessInspector 实例
 */
interface IInspector
{
    /**
     * @param mixed $value
     * @return bool
     */
    public function isValid($value):bool;
}
