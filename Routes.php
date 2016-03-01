<?php
namespace Rakaaditya\PanadaRouter;

/**
 * This class is for routing alias 
 *
 * @package Router
 * @author raka aditya <raka@detik.com>
 * @since version 1.0 <March 2016> 
 */

class Routes
{
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

    public function get($uri, $action)
    {
        $this->mapping($uri, $action, 'GET');
    }

    public function post($uri, $action)
    {
        $this->mapping($uri, $action, 'POST');
    }

    public function put($uri, $action)
    {
        $this->mapping($uri, $action, 'PUT');
    }

    public function delete($uri, $action)
    {
        $this->mapping($uri, $action, 'DELETE');
    }

    public function mapping($uri, $action, $requestMethod)
    {
        $options    = [];
        $action     = explode('@', $action);
        $controller = $action[0];
        $method     = $action[1];

        preg_match_all('/\{([^\)]*)\}/', $uri, $params);
        if (count($params) && count($params[0])) {
            $matcher = $uri;
            foreach ($params [0] as $param) {
                $matcher = str_replace($param, "([^/]+)", $matcher);
            }
            $options['matcher'] = '|\A'.$matcher.'\z|';
            $options['params'] = $params[1];

            self::$url['pattern'][$requestMethod][$uri] = [
                'class'     => '\\Controllers\\'.$controller,
                'method'    => $method,
                'options'   => $options
            ];
        } else {
            self::$url['static'][$requestMethod][$uri] = [
                'class'     => '\\Controllers\\'.$controller,
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

    public function run()
    {
        $parsed     = $this->parse($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
        if($controller = $parsed['class']) {
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
