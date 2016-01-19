<?php

namespace Sixbyte;

use Closure;

/**
 * 一个依赖注入容器, 为了提高性能, 不使用反射, 所以没有自动绑定解析依赖和自动注入依赖的功能.
 * 依赖composer的autoload
 */
class Container
{

    /**
     * 单例模式, 单例实例
     * @var [type]
     */
    protected static $instance;

    /**
     * 绑定到容器里的内容
     * @var array
     */
    protected $bindings = [];

    /**
     * 容器存放的单例实例
     * @var array
     */
    protected $instances = [];

    /**
     * 单例模式, 禁止实例化
     */
    private function __construct()
    {

    }

    /**
     * 单列模式, 生成实例
     * @return [type] [description]
     */
    public static function getInstance()
    {
        if (!static::$instance instanceof self) {
            static::$instance = new self();
        }
        return static::$instance;
    }

    /**
     * 单例模式, 禁止克隆
     * @return [type] [description]
     */
    public function __clone()
    {

    }

    public function bound(string $abstract)
    {
        return isset($this->bindings[$abstract]) || isset($this->instances[$abstract]);
    }

    /**
     * 绑定一个生成实例的回调方法到容器, 自动覆盖已存在的绑定
     * @param  string       $abstract [description]
     * @param  Closure      $concrete [description]
     * @param  bool|boolean $shared   [description]
     * @return [type]                 [description]
     */
    public function bind(string $abstract, Closure $concrete, bool $shared)
    {
        $this->bindings[$abstract] = [
            'concrete' => $concrete,
            'shared'   => $shared,
        ];
    }

    /**
     * 获取容器的实例
     * @param  string $abstract   [description]
     * @param  array  $parameters [description]
     * @return [type]             [description]
     */
    public function make(string $abstract, array $parameters)
    {
        // 如果存在单例, 直接返回单例, 如果想要重新生成实例, 请调用其他方法
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        if (!isset($this->bindings[$abstract])) {
            throw new Exception('Not found the ' . $abstract);

        }

        if ($this->bindings[$abstract]['shared']) {
            if (isset($this->instances[$abstract])) {
                return $this->instances[$abstract];
            }
        }

        $object = $this->build($this->bindings[$abstract]['concrete'], $parameters);

        if ($this->bindings[$abstract]['shared']) {
            $this->instances[$abstract] = $object;
        }

        return $object;
    }

    /**
     * 获取容器的实例, 必须是一个新实例.
     * @param  string $abstract   [description]
     * @param  array  $parameters [description]
     * @return [type]             [description]
     */
    public function makeNew(string $abstract, array $parameters)
    {
        if (!isset($this->bindings[$abstract])) {
            throw new Exception('Not found the ' . $abstract);

        }

        return $this->build($this->bindings[$abstract]['concrete'], $parameters);
    }

    /**
     * 构建容器的实例
     * @param  Closure $concrete   [description]
     * @param  array   $parameters [description]
     * @return [type]              [description]
     */
    protected function build(Closure $concrete, array $parameters)
    {
        return $concrete($this, $parameters);
    }

    /**
     * 设置单例
     * @param  string  $abstract [description]
     * @param  Closure $concrete [description]
     * @return [type]            [description]
     */
    public function singleton(string $abstract, Closure $concrete)
    {
        $this->bind($abstract, $concrete, true);
    }

    /**
     * 注册一个单例的实例
     * @param  string $abstract [description]
     * @param  [type] $instance [description]
     * @return [type]           [description]
     */
    public function instance(string $abstract, $instance)
    {
        // 注意, 已存在的抽象名称会被覆盖.
        $this->instances[$abstract] = $instance;
    }

}
