<?php
/**
 * Created by PhpStorm.
 * User: 37498_000
 * Date: 08.06.2016
 * Time: 17:59
 */

namespace engine\config;

/**
 * Class RouteConfig
 * @package engine\config
 */
class RouteConfig
{
    /**
     * @var bool $allowAbsoluteURL
     */
    private static $allowAbsoluteURL = false;

    /**
     * @var array $routeTypes
     * */
    private static $routeTypes = [
        '(*)' => '/^[A-Za-z0-9_\.\-\+\?\/=]/',
        '(s)' => '/^[\w]/',
        '(n)' => '/^[0-9]/',
        '(ln)' => '/^[a-z]{2}$/',
    ];

    /**
     * getRouteTypes method
     * @return array
     * */
    public static function getRouteTypes()
    {
        return self::$routeTypes;
    }

    /**
     * @return bool
     */
    public static final function isAllowAbsoluteURL()
    {
        return self::$allowAbsoluteURL;
    }

    /**
     * @param bool $allowAbsoluteURL
     * @return void
     */
    public static final function setAllowAbsoluteURL($allowAbsoluteURL)
    {
        self::$allowAbsoluteURL = $allowAbsoluteURL;
    }
}