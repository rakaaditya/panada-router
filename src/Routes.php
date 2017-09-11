<?php
namespace Rakaaditya\PanadaRouter;

/**
 * This class is for routing alias
 *
 * @package PanadaRouter
 * @author raka aditya <hai@rakaaditya.com>
 * @since version 1.0 <March 2016>
 */

class Routes
{
    public $prefix = null;

    static $url = [
                'static' => [
                    'GET'       => [],
                    'POST'      => [],
                    'PUT'       => [],
                    'DELETE'    => []
                ],
                'pattern' => [
                    'GET'       => [],
                    'POST'      => [],
                    'PUT'       => [],
                    'DELETE'    => []
                ]
            ];

    public function get($uri, $action) {
        $this->mapping($uri, $action, 'GET');
    }

    public function post($uri, $action) {
        $this->mapping($uri, $action, 'POST');
    }

    public function put($uri, $action) {
        $this->mapping($uri, $action, 'PUT');
    }

    public function delete($uri, $action) {
        $this->mapping($uri, $action, 'DELETE');
    }

    public function group($prefix, $actions) {
        $this->prefix = $prefix;
        $actions($this);
        $this->prefix = null;
    }

    public function routeAction($prop) {
        return self::$routeAction[$prop];
    }

    public function mapping($uri, $action, $requestMethod) {
        $options    = [];
        $action     = explode('@', $action);
        $controller = $action[0];
        $method     = $action[1];
        $uri        = trim($uri, '/');

        if($prefix = $this->prefix)
            $uri = trim($prefix . '/' . $uri, '/');

        preg_match_all('/\{([^\s\/]+)}/', $uri, $params);
        if (count($params) && count($params[0])) {
            $matcher = $uri;
            foreach ($params [0] as $param) {
                $matcher = str_replace($param, "([^/]+)", $matcher);
            }
            $options['matcher'] = '|\A'.$matcher.'\z|';
            $options['params'] = $params[1];

            self::$url['pattern'][$requestMethod][$uri] = [
                'controller'=> '\\Controllers\\'.$controller,
                'method'    => $method,
                'options'   => $options
            ];
        } else {
            self::$url['static'][$requestMethod][$uri] = [
                'controller'=> '\\Controllers\\'.$controller,
                'method'    => $method,
                'args'      => [],
            ];
        }
    }

    public function parse($uri, $requestMethod) {
        $strUri     = trim($uri, '/');
        if (array_key_exists($strUri, self::$url['static'][$requestMethod])) {
            return self::$url['static'][$requestMethod][$strUri];
        }
        foreach (self::$url['pattern'][$requestMethod] as $row) {
            preg_match($row['options']['matcher'], $strUri, $args);
            if (count($args) === count($row['options']['params'])+1) {
                $row['args'] = array_combine($row['options']['params'], array_slice($args, 1));
                return $row;
            }
        }
    }

    public function run() {
        $parsed     = $this->parse(strtok($_SERVER["REQUEST_URI"],'?'), $_SERVER['REQUEST_METHOD']);
        if($controller = $parsed['controller']) {
            try {
                $instance = new $controller;
                return call_user_func_array(array($instance, $parsed['method']), array_values($parsed['args']));
            } catch (Exception $e) {
                die($e->getMessage());
            }
        } else {
            throw new \Resources\HttpException('Page not found!');
        }
    }
}
