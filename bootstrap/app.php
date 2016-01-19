<?php

error_reporting(E_ALL);

define('APP_START', microtime(true));

define('APP_PATH', dirname(__DIR__));

// helper 函数
require APP_PATH . '/bootstrap/helper.php';
// 就我的mac而言, autoload 需要3-5ms
require APP_PATH . '/vendor/autoload.php';
// 环境变量
$env     = include APP_PATH . '/env.php';
$_SERVER = $_SERVER + $_ENV;
$_SERVER = array_merge($_SERVER, $env);

$container = container();

// config 入容器
$container->singleton('config', function ($app, $parameters) {
    $config = \Sixbyte\Config::getInstance();
    $config->multSet([
        'app_path'             => APP_PATH,
        'view_path'            => 'app/views',
        'error_log_file'       => 'app/logs/errors.log',
        'debug'                => env('APP_DEBUG', true),
        'timezone'             => 'PRC',
        'locale'               => 'zh_CN.UTF-8',
        'php_version_least'    => '7.0.2',
        'controller_namespace' => 'App\Controller',
    ]);
    return $config;
});

// router 入容器
$container->singleton('router', function ($app, $parameters) {
    $router = new \Sixbyte\Router();
    $router->setNotFoundHandle(function () {
        header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
        die;
    });
    // routes 设置
    $router->GET('/app', [
        'use' => 'AppController@index',
    ]);
    return $router;
});

$configs = $container->make('config', []);

if (version_compare(PHP_VERSION, $configs['php_version_least']) < 0) {
    trigger_error('当前版本: ' . PHP_VERSION . ' 低于允许的最低版本' . $configs['php_version_least'] . ". \n");
}

// 设置错误处理函数
set_error_handler('error_handler');
// 设置异常处理函数
set_exception_handler('exception_handler');
// 时区
date_default_timezone_set($configs['timezone']);
// 设置地区
setlocale(LC_ALL, $configs['locale']);

route();

define('APP_END', microtime(true));

echo "<script>console.log('" . (APP_END - APP_START) . "');</script>";
