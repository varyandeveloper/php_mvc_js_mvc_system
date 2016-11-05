<?php
/**
 * Created by PhpStorm.
 * User: Artur
 * Date: 26-Aug-16
 * Time: 09:56
 */

namespace engine;

/**
 * Class ENV
 * @package engine
 */
class ENV
{
    /**
     * @var string $_type
     */
    protected static $_type = "development";

    /**
     * @return void
     */
    public static function __init__()
    {
        self::_detectENV();
        self::_configENV();
    }

    /**
     * @return string
     */
    public static function get(): string
    {
        return self::$_type;
    }

    /**
     * @return void
     */
    protected static function _detectENV()
    {
        if(!empty($_SERVER['VS_ENV']))
            self::$_type = $_SERVER['VS_ENV'];
    }

    /**
     * @return void
     */
    protected static function _configENV()
    {
        switch (self::$_type):
            case "development":
                error_reporting(E_ALL);
                break;
            case "production":
                error_reporting(0);
                break;
        endswitch;
    }
}