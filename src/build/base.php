<?php
/**
 * This7 Frame
 * @Author: else
 * @Date:   2018-01-11 14:04:08
 * @Last Modified by:   qinuoyun
 * @Last Modified time: 2018-06-21 14:15:53
 */
namespace this7\middleware\build;

class base {
    protected $app;

    protected static $run = [];

    public function __construct($app) {
        $this->app = $app;
        self::$run = C('middleware', 'global');
    }

    /**
     * 添加控制器执行的中间件
     *
     * @param $name 中间件名称
     * @param $mod array 类型
     *  ['only'=>array('a','b')] 仅执行a,b控制器动作
     *  ['except']=>array('a','b')], 除了a,b控制器动作
     */
    public function set($name, $mod = []) {
        if ($mod) {
            foreach ($mod as $type => $data) {
                switch ($type) {
                case 'only':
                    if (in_array(ACTION, $data)) {
                        self::$run[] = C('middleware', $name);
                    }
                    break;
                case 'except':
                    if (!in_array(ACTION, $data)) {
                        self::$run[] = C('middleware', $name);
                    }
                    break;
                }
            }
        } else {
            self::$run[] = C('middleware', $name);
        }
    }

    //执行控制器
    public function run() {
        foreach (self::$run as $class) {
            if (class_exists($class)) {
                $obj = $this->app->make($class, true);
                if (method_exists($obj, 'run')) {
                    $obj->run();
                }
            }
        }
    }
}