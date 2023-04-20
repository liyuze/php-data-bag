<?php

namespace Liyuze\PhpDataBag;

use Closure;
use Liyuze\PhpDataBag\Inspectors\NullInspector;
use Liyuze\PhpDataBag\Interface\IDataBag;
use Liyuze\PhpDataBag\Interface\IEscape;
use Liyuze\PhpDataBag\Interface\IInspector;
use Liyuze\PhpDataBag\Interface\ISandbox;

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

    public function setSandbox(ISandbox $sandbox): self
    {
        $this->sandbox = $sandbox;

        return $this;
    }

    public function setInspector(?IInspector $inspector): self
    {
        $this->inspector = $inspector;

        return $this;
    }

    public function setIsGreedy(bool $isGreedy): self
    {
        $this->isGreedy = $isGreedy;

        return $this;
    }

    public function runInGreedyMode(callable|Closure $func): mixed
    {
        $oldStatus = $this->isGreedy;
        $this->isGreedy = true;
        $value = call_user_func($func);
        $this->setIsGreedy($oldStatus);

        return $value;
    }

    public function pickUp(string $key, callable|Closure $value, ?IInspector $inspector = null): mixed
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

    public function throw(string $key): mixed
    {
        $value = $this->sandbox->get($key);
        $this->sandbox->delete($key);

        return $value;
    }

    public function clear(): void
    {
        $this->sandbox->clear();
    }

    public function put(string $key, mixed $value): void
    {
        if ($value instanceof IEscape) {
            return;
        }

        $this->sandbox->set($key, $value);
    }

    public function take(string $key): mixed
    {
        return $this->sandbox->get($key);
    }

    public function exists(string $key): bool
    {
        return $this->sandbox->exists($key);
    }

    public function putItem(string $key, string $subKey, mixed $value): void
    {
        if ($value instanceof IEscape) {
            return;
        }

        $arr = $this->sandbox->get($key) ?? [];
        $arr[$subKey] = $value;
        $this->sandbox->set($key, $arr);
    }

    public function takeItem(string $key, string $subKey): mixed
    {
        $value = $this->sandbox->get($key) ?? [];

        return $value[$subKey] ?? null;
    }

    public function throwItem(string $key, string $subKey): mixed
    {
        $arr = $this->sandbox->get($key) ?? [];
        $value = $arr[$subKey] ?? null;
        unset($arr[$subKey]);
        $this->sandbox->set($key, $arr);

        return $value;
    }

    public function existsItem(string $key, string $subKey): bool
    {
        $arr = $this->sandbox->get($key) ?? [];
        if (! is_array($arr)) {
            return false;
        }

        return key_exists($subKey, $arr);
    }

    public function mergeItems(string $key, array ...$arrays): array
    {
        $value = $this->sandbox->get($key) ?? [];
        if (! is_array($value)) {
            throw new \RuntimeException("The value of '{$key}' key is not an array type");
        }

        // filter IEscape value
        foreach ($arrays as &$array) {
            $array = array_filter($array, function ($v) {
                //@phpstan-ignore-next-line
                if ($v instanceof IEscape) {
                    return false;
                }

                return true;
            });
        }

        $value = array_merge($value, ...$arrays);
        $this->sandbox->set($key, $value);

        return $value;
    }
}