<?php
/**
 * Created by PhpStorm.
 * User: 37498_000
 * Date: 10.06.2016
 * Time: 13:59
 */

namespace engine\traits;

/**
 * Class Singleton
 * @package engine\traits
 */
trait Singleton
{
    /**
     * @var Singleton $instance
     */
    private static $__instance;

    /**
     * @return object
     */
    public static function getInstance()
    {
        if (is_null(self::$__instance))
            self::$__instance = new static();

        return self::$__instance;
    }

    /**
     * Singleton constructor.
     */
    private function __construct(){}

    /**
     * Singleton clone.
     */
    private function __clone(){}

    /**
     * Singleton __wakeup
     */
    private function __wakeup(){}
}