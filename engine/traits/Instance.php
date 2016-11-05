<?php
/**
 * Created by PhpStorm.
 * User: Var Yan
 * Date: 8/8/2016
 * Time: 8:57 PM
 */

namespace engine\traits;

/**
 * Class Instance
 * @package engine\traits
 */
trait Instance
{
    /**
     * @var $_instance
     */
    protected static $_instance;

    /**
     * @param array ...$args
     * @return static
     */
    public static function getInstance(...$args)
    {
        if(is_null(self::$_instance))
            self::$_instance = sizeof($args) ? new static(...$args) : new static;
        return self::$_instance;
    }

    /**
     * @return void
     */
    public static function removeInstance()
    {
        self::$_instance = null;
    }
}