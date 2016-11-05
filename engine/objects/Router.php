<?php
/**
 * Created by PhpStorm.
 * User: 37498_000
 * Date: 10.06.2016
 * Time: 14:40
 */

namespace engine\objects;

use engine\abstracts\RouterParent;
use engine\interfaces\RouterSchema;

/**
 * Class Router
 * @package engine\objects
 */
class Router extends RouterParent
{
    /**
     * @var RouterSchema $__instance
     */
    protected static $_instance;
    /**
     * @var array $_aliases
     */
    protected static $_aliases;

    /**
     * @return RouterSchema|object
     */
    public static function continue()
    {
        if(!self::$_instance)
            self::$_instance = Factory::create(ENGINE."objects".DS."Router");
        return self::$_instance;
    }

    /**
     * @param bool $multiLang
     * @param string|null $pattern
     * @return RouterSchema|object
     */
    public static function start($multiLang = false,$pattern = null)
    {
        self::$_multiLang = $multiLang;
        if(self::$_multiLang && !$pattern)
            exit("It looks like you do not set language pattern");
        self::$_langPattern = $pattern;
        self::$_instance = Factory::create(ENGINE."objects".DS."Router");
        return self::$_instance;
    }

    /**
     * @return boolean
     */
    public static function isMultiLang()
    {
        return self::$_multiLang;
    }

    /**
     * @return string
     */
    public static function getLangPattern()
    {
        return self::$_langPattern;
    }

    /**
     * @param string $key
     * @return string
     */
    public function alias(string $key) : string
    {
        return @self::$_aliases[$key];
    }

    /**
     * @param string $recognizeName
     * @return RouterSchema
     */
    public function _as(string $recognizeName) : RouterSchema
    {
        self::$_aliases[$recognizeName] = $this->_currentRouteKey;
        return $this;
    }

    /**
     * @param string $pattern
     * @param $destination
     * @return RouterSchema
     */
    public function anyGetPost(string $pattern, $destination) : RouterSchema
    {
        $this->_putInRoute("get", $pattern, $destination);
        $this->_putInRoute("post", $pattern, $destination);
        $this->_putInRoute("getAjax", $pattern, $destination);
        $this->_putInRoute("postAjax", $pattern, $destination);
    }

    /**
     * @param string $pattern
     * @param $destination
     * @return RouterSchema
     */
    public function any(string $pattern, $destination) : RouterSchema
    {
        $this->_putInRoute("get", $pattern, $destination);
        $this->_putInRoute("post", $pattern, $destination);
        $this->_putInRoute("getAjax", $pattern, $destination);
        $this->_putInRoute("postAjax", $pattern, $destination);
        $this->_putInRoute("put", $pattern, $destination);
        $this->_putInRoute("patch", $pattern, $destination);
        $this->_putInRoute("delete", $pattern, $destination);
        return $this;
    }

    /**
     * @param string $pattern
     * @param $destination
     * @return RouterSchema
     */
    public function anyGet(string $pattern, $destination) : RouterSchema
    {
        $this->_putInRoute("get", $pattern, $destination);
        $this->_putInRoute("getAjax", $pattern, $destination);
        return $this;
    }

    /**
     * @param string $pattern
     * @param $destination
     * @return RouterSchema
     */
    public function anyPost(string $pattern, $destination) : RouterSchema
    {
        $this->_putInRoute("post", $pattern, $destination);
        $this->_putInRoute("postAjax", $pattern, $destination);
        return $this;
    }

    /**
     * @param string $pattern
     * @param $destination
     * @return RouterSchema
     */
    public function anyAjax(string $pattern, $destination) : RouterSchema
    {
        $this->_putInRoute("getAjax", $pattern, $destination);
        $this->_putInRoute("postAjax", $pattern, $destination);
        return $this;
    }

    /**
     * @param string $pattern
     * @param $destination
     * @return RouterSchema
     */
    public function getAjax(string $pattern, $destination) : RouterSchema
    {
        $this->_putInRoute(__FUNCTION__, $pattern, $destination);
        return $this;
    }

    /**
     * @param string $pattern
     * @param $destination
     * @return RouterSchema
     */
    public function postAjax(string $pattern, $destination) : RouterSchema
    {
        $this->_putInRoute(__FUNCTION__, $pattern, $destination);
        return $this;
    }
}