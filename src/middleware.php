<?php
/**
 * This7 Frame
 * @Author: else
 * @Date:   2018-01-11 14:04:08
 * @Last Modified by:   qinuoyun
 * @Last Modified time: 2018-06-21 14:20:04
 */
namespace this7\middleware;
use this7\middleware\build\base;

class middleware {

    /**
     * 初始APP核心
     * @var [type]
     */
    protected $app;

    /**
     * 链接驱动
     * @var [type]
     */
    protected $link;

    public function __construct($app) {
        $this->app = $app;
    }

    //更改缓存驱动
    protected function driver() {
        $this->link = new base($this->app);

        return $this;
    }

    public function __call($method, $params = []) {
        if (is_null($this->link)) {
            $this->driver();
        }

        return call_user_func_array([$this->link, $method], $params);
    }

    //生成单例对象
    public static function single() {
        static $link;
        if (is_null($link)) {
            $link = new static();
        }

        return $link;
    }

    public static function __callStatic($name, $arguments) {
        return call_user_func_array([static::single(), $name], $arguments);
    }
}
