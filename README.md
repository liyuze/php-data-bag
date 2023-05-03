# php-data-bag

Cache the execution results to prevent multiple executions.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/liyuze/php-data-bag.svg?style=flat-square)](https://packagist.org/packages/liyuze/php-data-bag)
[![Total Downloads](https://img.shields.io/packagist/dt/liyuze/php-data-bag.svg?style=flat-square)](https://packagist.org/packages/liyuze/php-data-bag)
![GitHub Actions](https://github.com/liyuze/php-data-bag/actions/workflows/main.yml/badge.svg)

This is where your description should go. Try and limit it to a paragraph or two, and maybe throw in a mention of what
PSRs you support to avoid any confusion with users and contributors.

## Installation

You can install the package via composer:

```bash
composer require liyuze/php-data-bag
```

## Usage

```php
$bag = new DataBag();

$cacheKey = 'cache_key';
$callable = function () {
    //数据库查询、耗时运算
    return 'result';
}

//运行 callable 并将执行结果放入背包
$bag->pickUp('cache_key', $callable);   //result

//获取背包中某 key 对应的值
$bag->take($cacheKey);  //result

//获取背包中某 key 对应的值，并删除掉改数据项
$bag->throw($cacheKey);  //result

//直接将结果放入到背包中
$bag->put($cacheKey, 123);    //void
$bag->put($cacheKey, 123);    //void

//判断背包中是否存在某个 key
$bag->exists($cacheKey); //true
$bag->exists('k1', 'k2'); //指定的 keys 都存在时返回 true
$bag->existsAny('k1', 'k2'); //指定的 keys 任何一个存在时返回 true

//清空背包
$bag->clear(); //void
```

### 数组类型支持

```php
//设置单个元素
public function putItem(string $key, string $subKey, mixed $value): void;
//取出单个元素
public function takeItem(string $key, string $subKey): mixed;
//取出单个元素，并丢掉该元素
public function throwItem(string $key, string $subKey): mixed;
//判断是否存在某个子元素
public function existsItem(string $key, string $subKey): bool;
//合并一个或多个新的数组到旧元素上
public function mergeItems(string $key, array ...$arrays): array;
```

### 拦截器

数据背包通过 `拦截器` 来判断一个值是否为有效值，无效值将被丢弃，不被缓存。默认配置的 `NullInspector` 拦截器，当值为 `null`
时将不进行缓存。

可用的拦截器：

- `NullInspector` `=== null` 拦截器。
- `EmptyInspector` `empty()` 拦截器。
- `InInspector` `in_array()` （强类型对比）拦截器。
- `ClosureInspector` 自定义类型拦截器。
- `NothingnessInspector` 无限制拦截器（任何类型都是有效值）。

设置拦截器有两种方式：

一、全局设置

```php
$bag = new DataBag();
$bag->setInspector(new \Liyuze\PhpDataBag\Inspectors\EmptyInspector());
```

二、临时设置

```php
$bag->pickUp('cacheKey', fn ()=>0, new \Liyuze\PhpDataBag\Inspectors\\Liyuze\PhpDataBag\Inspectors\EmptyInspector());
```

### 逃脱值

`可逃脱值` 不能被缓存。

```php
$bag->pickUp('cacheKey', fn () => {
    return new \Liyuze\PhpDataBag\Proxies\EscapeProxyProxy(5);
});
$bag->exists('cacheKey'); //false

```

> 与拦截器的区别
> 
> 拦截器：适用于统一设置的缓存拦截器，针对所有被缓存的值进行检查。<br/>
> 可逃脱值：适用于特殊情况，进行针对当前要缓存的值有效。优先级比拦截器高，可以覆盖拦截器的缓存规则。

### 避难值

`避难值` 将跳过检查器的拦截，进行缓存。

```php
$bag->setInspector(new \Liyuze\PhpDataBag\Inspectors\EmptyInspector());
$bag->pickUp('cacheKey', fn ()=> {
    return new \Liyuze\PhpDataBag\Proxies\RefugeProxyProxy(0);
});
$bag->exists('cacheKey'); //true
```

> 与拦截器的区别
>
> 拦截器：适用于统一设置的缓存拦截器，针对所有被缓存的值进行检查。<br/>
> 避难值：适用于特殊情况，进行针对当前要缓存的值有效。优先级比`拦截器`高，比`逃脱值`低。



### 贪婪模式

在贪婪模式下，`pickUp` 和 `pickUpArr` 中的 `callable` 总是会执行，这在排查系统性能时很有用。

开启方式有两种：

一、全局开启

```php
$bag->setIsGreedy(true);
```

二、局部开启

```php
$bag->runInGreedyMode(function () {
    //在贪婪模式开启中执行程序
});
```

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email 290315384@qq.com instead of using the issue tracker.

## Credits

- [Yuze Li](https://github.com/liyuze)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## PHP Package Boilerplate

This package was generated using the [PHP Package Boilerplate](https://laravelpackageboilerplate.com)
by [Beyond Code](http://beyondco.de/).
