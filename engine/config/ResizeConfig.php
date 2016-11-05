<?php
/**
 * Created by PhpStorm.
 * User: Var Yan
 * Date: 5/14/2016
 * Time: 8:43 PM
 */

namespace engine\config;

/**
 * Class ResizeConfig
 * @package engine\config
 */
class ResizeConfig
{
    /**
     * @var float $original_w
     */
    protected static $original_w;
    /**
     * @var float $original_h
     */
    protected static $original_h;
    /**
     * @var $dest_w
     */
    protected static $dest_w;
    /**
     * @var $dest_h
     */
    protected static $dest_h;

    /**
     * @return mixed
     */
    public static function getDestH()
    {
        return self::$dest_h;
    }

    /**
     * @return mixed
     */
    public static function getDestW()
    {
        return self::$dest_w;
    }

    /**
     * @return float
     */
    public static function getOriginalH()
    {
        return self::$original_h;
    }

    /**
     * @return float
     */
    public static function getOriginalW()
    {
        return self::$original_w;
    }

    /**
     * @param $dest_h
     */
    public static function setDestH($dest_h)
    {
        self::$dest_h = $dest_h;
    }

    /**
     * @param $dest_w
     */
    public static function setDestW($dest_w)
    {
        self::$dest_w = $dest_w;
    }

    /**
     * @param $original_h
     */
    public static function setOriginalH($original_h)
    {
        self::$original_h = $original_h;
    }

    /**
     * @param $original_w
     */
    public static function setOriginalW($original_w)
    {
        self::$original_w = $original_w;
    }
}