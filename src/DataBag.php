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

    public function __construct()
    {
        $this->setSandbox(new ArraySandbox());
        $this->setinspector(new NullInspector());
    }

    public function setSandbox(ISandbox $sandbox)
    {
        $this->sandbox = $sandbox;
    }

    public function setInspector(?IInspector $inspector)
    {
        $this->inspector = $inspector;
    }

    public function pickUp(string $key, callable|Closure $value, ?IInspector $inspector = null): mixed
    {
        return $this->commonPickUp($key, $value, $inspector, function ($key, $value) {
            $this->put($key, $value);
        });
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

    public function pickUpItems(string $key, callable|Closure $value, ?IInspector $inspector = null): mixed
    {
        return $this->commonPickUp($key, $value, $inspector, function ($key, $value) {
            return $this->mergeItems($key, $value);
        });
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
        foreach ($arrays as $array) {
            $array = array_filter($array, function ($v) {
                return ! ($v instanceof IEscape);
            });
        }

        $value = array_merge($value, ...$arrays);
        $this->sandbox->set($key, $value);

        return $value;
    }

    protected function commonPickUp(string $key, callable|Closure $value, ?IInspector $inspector, Closure $diyHandle)
    {
        $value = call_user_func($value);

        $inspector === null || $inspector = $this->inspector;
        if ($inspector === null || $inspector->isValid($value)) {
            $newValue = $diyHandle($key, $value);
            if ($newValue !== null) {
                $value = $newValue;
            }
        }

        return $value;
    }
}