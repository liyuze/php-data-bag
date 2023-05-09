<?php

namespace Liyuze\PhpDataBag;

use Liyuze\PhpDataBag\Inspectors\NullInspector;
use Liyuze\PhpDataBag\Interfaces\IDataBag;
use Liyuze\PhpDataBag\Interfaces\IEscape;
use Liyuze\PhpDataBag\Interfaces\IInspector;
use Liyuze\PhpDataBag\Interfaces\IRefuge;
use Liyuze\PhpDataBag\Interfaces\ISandbox;
use RuntimeException;

class DataBag implements IDataBag
{
    protected ISandbox $sandbox;

    protected ?IInspector $inspector;

    protected bool $isGreedy = false;

    public function __construct()
    {
        $this->setSandbox(new ArraySandbox());
        $this->setinspector(new NullInspector());
    }

    public function setSandbox(ISandbox $sandbox):self
    {
        $this->sandbox = $sandbox;

        return $this;
    }

    public function setInspector(?IInspector $inspector):self
    {
        $this->inspector = $inspector;

        return $this;
    }

    public function runInGreedyMode(callable $func)
    {
        $oldStatus = $this->isGreedy;
        $this->isGreedy = true;
        $value = call_user_func($func);
        $this->setIsGreedy($oldStatus);

        return $value;
    }

    public function setIsGreedy(bool $isGreedy):self
    {
        $this->isGreedy = $isGreedy;

        return $this;
    }

    public function pickUp(string $key, callable $value, ?IInspector $inspector = null)
    {
        if ($this->exists($key) && ! $this->isGreedy) {
            return $this->take($key);
        }

        $value = call_user_func($value);

        $inspector === null || $inspector = $this->inspector;
        if ($inspector === null || $inspector->isValid($value)) {
            $this->put($key, $value);
        }

        return $value;
    }

    public function exists(string ...$keys):bool
    {
        foreach ($keys as $key) {
            if (! $this->sandbox->exists($key)) {
                return false;
            }
        }

        return true;
    }

    public function take(string $key)
    {
        return $this->sandbox->get($key);
    }

    public function put(string $key, $value):void
    {
        if ($value instanceof IEscape) {
            return;
        } elseif ($value instanceof IRefuge) {
            $value = $value->getProxyValue();
        }

        $this->sandbox->set($key, $value);
    }

    public function throw(string $key)
    {
        $value = $this->sandbox->get($key);
        $this->sandbox->delete($key);

        return $value;
    }

    public function getAll():array
    {
        return $this->sandbox->getAll();
    }

    public function clear():void
    {
        $this->sandbox->clear();
    }

    public function existsAny(string ...$keys):bool
    {
        foreach ($keys as $key) {
            if ($this->sandbox->exists($key)) {
                return true;
            }
        }

        return false;
    }

    public function putItem(string $key, string $subKey, $value):void
    {
        if ($value instanceof IEscape) {
            return;
        } elseif ($value instanceof IRefuge) {
            $value = $value->getProxyValue();
        }

        $arr = $this->sandbox->get($key) ?? [];
        $arr[$subKey] = $value;
        $this->sandbox->set($key, $arr);
    }

    public function takeItem(string $key, string $subKey)
    {
        $value = $this->sandbox->get($key) ?? [];

        return $value[$subKey] ?? null;
    }

    public function throwItem(string $key, string $subKey)
    {
        $arr = $this->sandbox->get($key) ?? [];
        $value = $arr[$subKey] ?? null;
        unset($arr[$subKey]);
        $this->sandbox->set($key, $arr);

        return $value;
    }

    public function existsItem(string $key, string ...$subKeys):bool
    {
        $arr = $this->sandbox->get($key) ?? [];
        if (! is_array($arr)) {
            return false;
        }

        foreach ($subKeys as $subKey) {
            if (! key_exists($subKey, $arr)) {
                return false;
            }
        }

        return true;
    }

    public function existsAnyItem(string $key, string ...$subKeys):bool
    {
        $arr = $this->sandbox->get($key) ?? [];
        if (! is_array($arr)) {
            return false;
        }

        foreach ($subKeys as $subKey) {
            if (key_exists($subKey, $arr)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $key
     * @param array<int|string, > ...$arrays
     * @return array<int|string, >
     */
    public function mergeItems(string $key, array ...$arrays):array
    {
        $value = $this->sandbox->get($key) ?? [];
        if (! is_array($value)) {
            throw new RuntimeException("The value of '$key' key is not an array type");
        }

        $newArrays = [];
        foreach ($arrays as $array) {
            $newArray = [];
            foreach ($array as $k => $v) {
                if ($v instanceof IEscape) {
                    continue;
                }
                if ($v instanceof IRefuge) {
                    $v = $v->getProxyValue();
                }
                $newArray[$k] = $v;
            }
            $newArrays[] = $newArray;
        }

        $value = array_merge($value, ...$newArrays);
        $this->sandbox->set($key, $value);

        return $value;
    }
}