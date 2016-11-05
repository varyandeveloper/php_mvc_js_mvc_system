<?php
/**
 * Created by PhpStorm.
 * User: 37498_000
 * Date: 10.06.2016
 * Time: 12:41
 */

namespace engine\abstracts;

use engine\config\AppConfig;
use engine\config\RouteConfig;
use engine\Engine;
use engine\interfaces\RouterSchema;
use engine\objects\Factory;
use engine\objects\Request;

/**
 * Class RouterParent
 * @package engine\abstracts
 */
abstract class RouterParent implements RouterSchema
{
    /**
     * @var boolean $_multiLang
     */
    protected static $_multiLang;
    /**
     * @var string $_langPattern
     */
    protected static $_langPattern;
    /**
     * @var string $_currentRouteKey
     */
    protected $_currentRouteKey;
    /**
     * @var array $_routes
     */
    protected static $_routes;
    /**
     * @var string $__prefix
     */
    private static $__prefix;
    /**
     * @var string $__controller
     */
    private static $__controller;
    /**
     * @var string $__method
     */
    private static $__method;
    /**
     * @var array|null $__parameters
     */
    private static $__parameters;
    /**
     * @var array $__activeRoute
     */
    private $__activeRoute;
    /**
     * @var string $__requestMethod
     */
    private $__requestMethod;
    /**
     * @var Request $__request
     */
    private $__request;
    /**
     * @var string $__url
     */
    private $__url;

    public static function getA()
    {
        return self::$_routes;
    }

    /**
     * RouterParent constructor.
     */
    public function __construct()
    {
        $this->__url = Factory::create(ENGINE . "objects" . DS . "URL")->getCleanURL();
        $this->__request = Factory::create(ENGINE . "objects" . DS . "Request");
        $this->__requestMethod = $this->__request->method();
    }

    /**
     * @param string|array|callable $destination
     * @return RouterSchema
     */
    public function defaultRoute($destination) : RouterSchema
    {
        self::$_routes[__FUNCTION__] = $destination;
        return $this;
    }

    /**
     * @param string|array|callable $destination
     * @return RouterSchema
     */
    public function errorRoute($destination) : RouterSchema
    {
        self::$_routes[__FUNCTION__] = $destination;
        return $this;
    }

    /**
     * @return array
     */
    public static function getRoutes()
    {
        return self::$_routes;
    }

    /**
     * @param string $key
     * @param callable $callback
     * @return RouterSchema
     */
    public function prefix(string $key, callable $callback) : RouterSchema
    {
        self::$__prefix = is_null(self::$__prefix) ? $key : self::$__prefix . $key;
        Factory::injectFunction($callback);
        return $this;
    }

    /**
     * @return RouterSchema
     */
    public function clearPrefix() : RouterSchema
    {
        self::$__prefix = null;
        return $this;
    }

    /**
     * @param string $pattern
     * @param $destination
     * @return RouterSchema
     */
    public function get(string $pattern, $destination) : RouterSchema
    {
        $this->_putInRoute(__FUNCTION__, $pattern, $destination);
        return $this;
    }

    /**
     * @param string $pattern
     * @param $destination
     * @return RouterSchema
     */
    public function post(string $pattern, $destination) : RouterSchema
    {
        $this->_putInRoute(__FUNCTION__, $pattern, $destination);
        return $this;
    }

    /**
     * @param string $pattern
     * @param $destination
     * @return RouterSchema
     */
    public function put(string $pattern, $destination) : RouterSchema
    {
        $this->_putInRoute(__FUNCTION__, $pattern, $destination);
        return $this;
    }

    /**
     * @param string $pattern
     * @param $destination
     * @return RouterSchema
     */
    public function patch(string $pattern, $destination) : RouterSchema
    {
        $this->_putInRoute(__FUNCTION__, $pattern, $destination);
        return $this;
    }

    /**
     * @param string $pattern
     * @param $destination
     * @return RouterSchema
     */
    public function delete(string $pattern, $destination) : RouterSchema
    {
        $this->_putInRoute(__FUNCTION__, $pattern, $destination);
        return $this;
    }

    /**
     * @param bool $forView
     * @return string
     */
    public function currentController(bool $forView = true) : string
    {
        $ctrlParts = explode(DS, self::$__controller);
        $controller = end($ctrlParts);
        return $forView
            ? Engine::splitAtUpperCase($controller)[0]
            : $controller;
    }

    /**
     * @return string
     */
    public function currentNamespace() : string
    {
        if (strpos(self::$__controller, DS) !== FALSE) {
            $ctrlParts = explode(DS, self::$__controller);
            unset($ctrlParts[(sizeof($ctrlParts) - 1)]);
            return join(DS, $ctrlParts);
        }
        return "";
    }

    /**
     * @return string
     */
    public function currentMethod() : string
    {
        return self::$__method;
    }

    /**
     * @return array|null
     */
    public function currentParameters()
    {
        return self::$__parameters;
    }

    /**
     * @param string $method
     * @param string $key
     * @param string|array|callable $value
     */
    protected function _putInRoute(string $method, string $key, $value)
    {
        $key = !is_null(self::$__prefix)
            ? self::$__prefix . $key
            : $key;
        $this->_currentRouteKey = $key;
        self::$_routes[$method][$key] = $value;
    }

    /**
     * @return array
     */
    public function getRoute()
    {
        $segment = AppConfig::withAddedPath() ? segment(1) : segment(0);
        if(self::$_multiLang && preg_match(self::$_langPattern,$segment))
        {
            $urlParts = explode("/",$this->__url);
            unset($urlParts[0]);
            $this->__url = join("/",$urlParts);
        }
        if (empty($this->__url)):
            if (empty(self::$_routes["defaultRoute"])):
                if (empty(self::$_routes["errorRoute"])):
                    exit("It looks like you dose`nt set error route");
                else:
                    $this->__activeRoute = self::$_routes['errorRoute'];
                endif;
            else:
                $this->__activeRoute = self::$_routes['defaultRoute'];
            endif;
        else:
            $this->__activeRoute = @self::$_routes[$this->__request->method()];
        endif;

        return $this->__getCurrentRoute();
    }

    /**
     * @return array
     */
    private function __getCurrentRoute()
    {
        $valueParsPath = ENGINE . "objects" . DS . "route" . DS;

        if (!is_array($this->__activeRoute)):
            $value = $this->__activeRoute;
        else:
            if (array_key_exists($this->__url, $this->__activeRoute)):
                $value = $this->__activeRoute[$this->__url];
            else:
                $value = $this->__advanceSearch();
            endif;
        endif;

        switch (true):
            case is_string($value):
                $routeResult = Factory::create($valueParsPath . "StringRoute", $this->__url, $value)->get();
                break;
            case is_array($value):
                $routeResult = Factory::create($valueParsPath . "ArrayRoute", $this->__url, $value)->get();
                break;
            case ($value instanceof \Closure):
                $routeResult = Factory::create($valueParsPath . "CallableRoute", $this->__url, $value)->get();
                break;
            default:
                $routeResult = Factory::create($valueParsPath . "StringRoute", $this->__url, @self::$_routes['errorRoute'])->get();
                break;
        endswitch;

        self::$__controller = $routeResult['controller'];
        self::$__method = $routeResult['method'];
        self::$__parameters = $routeResult['parameters'];

        return $routeResult;
    }

    /**
     * @return string|array|callable
     */
    private function __advanceSearch()
    {
        $matchKey = [];
        $routeTypes = RouteConfig::getRouteTypes();
        $urlParts = explode("/", $this->__url);
        $urlPartsLength = sizeof($urlParts);
        $keys = array_keys($this->__activeRoute);

        for ($i = 0; $i < sizeof($keys); $i++) :
            $keyParts = explode("/", ltrim($keys[$i], "/"));
            $keyPartsLength = sizeof($keyParts);
            if ($urlPartsLength == $keyPartsLength):
                for ($j = 0; $j < $urlPartsLength; $j++):
                    if (($urlParts[$j] == $keyParts[$j])):
                        $matchKey[$j] = $keyParts[$j];
                    elseif (array_key_exists($keyParts[$j], $routeTypes)):
                        if (@preg_match($routeTypes[$keyParts[$j]], $urlParts[$j]) && ((isset($keyParts[$j - 1]) && strpos($keyParts[$j - 1], "(") !== FALSE) || empty($keyParts[$j - 1]) || (isset($keyParts[$j - 1]) && $keyParts[$j - 1] == $urlParts[$j - 1]))):
                            $matchKey[$j] = $keyParts[$j];
                        endif;
                    elseif(Engine::isStringRegex($keyParts[$j])):
                        $pattern = rtrim(ltrim($keyParts[$j],"("),")");
                        if(@preg_match($pattern,$urlParts[$j])):
                            $matchKey[$j] = $keyParts[$j];
                        endif;
                    endif;
                endfor;
            endif;
        endfor;
        ksort($matchKey);
        $this->__url = join("/",array_diff($urlParts,$matchKey));
        return @$this->__activeRoute["/" . join("/", $matchKey)];
    }

}