<?php

/**
 * helper function
 *
 *
 *
 *
 *
 *
 *
 */

if (!function_exists('container')) {
    function container()
    {
        return \Sixbyte\Container::getInstance();
    }
}
if (!function_exists('error_handler')) {
    function error_handler($errno, $errstr, $errfile, $errline, $errcontext)
    {
        $configs = container()->make('config', []);
        ob_start();
        echo 'time: [', date('Y-m-d H:i:s'), '] ', "\n";
        echo 'code: ', $errno, "\n";
        echo 'message: ', $errstr, "\n";
        echo 'in: ', $errfile, ': ', $errline, "\n";
        echo "\n";
        $content = ob_get_clean();
        error_log($content . "\n", 3, $configs["app_path"] . '/' . $configs["error_log_file"]);
        if ($configs['debug']) {
            return false;
        }
        header($_SERVER["SERVER_PROTOCOL"] . " 500 Internal Server Error");
        echo "服务器罢工了...";
    }
}
if (!function_exists('exception_handler')) {
    function exception_handler($exception)
    {
        ob_start();
        echo 'time: [', date('Y-m-d H:i:s'), '] ', "\n";
        echo 'code: ', $exception->getCode(), "\n";
        echo 'message: ', $exception->getMessage(), "\n";
        echo 'in: ', $exception->getFile(), ': ', $exception->getLine(), "\n";
        echo $exception->getTraceAsString(), "\n";
        echo 'previous: ', $exception->getPrevious(), "\n";
        echo "\n";
        $content = ob_get_clean();
        $configs = container()->make('config', []);
        // 脚本会停止执行, 奇怪, 日志文件是空的话, 存储的是二进制格式?!
        error_log($content . "\n", 3, $configs["app_path"] . '/' . $configs["error_log_file"]);
        if ($configs['debug']) {
            echo $content;
        }
        header($_SERVER["SERVER_PROTOCOL"] . " 500 Internal Server Error");
        echo "服务器罢工了...";
    }
}
if (!function_exists('view')) {
    function view($file, $variables = [])
    {
        $ccccccccccc = container()->make('config', []);
        extract($variables);
        include $ccccccccccc['app_path'] . '/' . $ccccccccccc['view_path'] . '/' . $file;
    }
}

if (!function_exists('route')) {
    function route()
    {
        $route   = container()->make('router', [])->math();
        $configs = container()->make('config', []);

        if ($route['use'] instanceof Closure) {
            $route['use']();
        }

        if (is_string($route['use']) && strpos($route['use'], "@") !== false) {
            list($controller, $method) = explode('@', $route['use']);
            $controller                = $configs['controller_namespace'] . '\\' . $controller;
            $instance                  = new $controller;
            $instance->{$method}();
        }
    }
}

if (!function_exists('env')) {
    function env($key, $default = null)
    {
        if (isset($_SERVER[$key])) {
            return $_SERVER[$key];
        } else {
            return $default;
        }
    }
}
