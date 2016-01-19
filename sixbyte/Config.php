<?php

namespace Sixbyte;

use ArrayAccess;

class Config implements ArrayAccess
{
    protected $container = [];

    /**
     * 单例模式, 单例实例
     * @var [type]
     */
    protected static $instance;

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

    public function all()
    {
        return $this->container;
    }

    public function multSet(array $configs)
    {
        foreach ($configs as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * 获得容器的内容
     * @param  string $offset [description]
     * @return [type]         [description]
     */
    public function get(string $offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    /**
     * 设置容器的内容
     * @param string $offset [description]
     * @param [type] $value  [description]
     */
    public function set(string $offset, $value)
    {
        $this->container[$offset] = $value;
    }

    /**
     * 检查容器是否存在某个键
     * @param  string $offset [description]
     * @return [type]         [description]
     */
    public function exists(string $offset)
    {
        return isset($this->container[$offset]);
    }

    /**
     * 删除容器的某个元素
     * @param  string $offset [description]
     * @return [type]         [description]
     */
    public function delete(string $offset)
    {
        unset($this->container[$offset]);
    }

    /**
     * 数组接口的set方法
     * @param  string $offset [description]
     * @param  [type] $value  [description]
     * @return [type]         [description]
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * 数组接口的是否存在的Exists方法
     * @param  string $offset [description]
     * @return [type]         [description]
     */
    public function offsetExists($offset)
    {
        return $this->exists($offset);
    }

    /**
     * 数组接口删除的unsset方法
     * @param  string $offset [description]
     * @return [type]         [description]
     */
    public function offsetUnset($offset)
    {
        $this->delete($offset);
    }

    /**
     * 数组接口的获取的GET方法
     * @param  string $offset [description]
     * @return [type]         [description]
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * 对象获取的魔术方法
     * @param  string $offset [description]
     * @return [type]         [description]
     */
    public function __get(string $offset)
    {
        return $this->get($offset);
    }

    /**
     * 对象设置的魔术方法
     * @param string $offset [description]
     * @param [type] $value  [description]
     */
    public function __set(string $offset, $value)
    {
        $this->set($offset, $value);
    }

    public function __isset(string $offset)
    {
        return $this->exists($offset);
    }

    public function __unset(string $offset)
    {
        $this->delete($offset);
    }
}
