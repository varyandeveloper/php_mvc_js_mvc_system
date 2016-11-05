<?php
/**
 * Created by PhpStorm.
 * User: Var Yan
 * Date: 8/21/2016
 * Time: 1:07 AM
 */

namespace engine\traits;

/**
 * Class SetGet
 * @package engine\traits
 */
trait SetGet
{
    /**
     * @var array $items
     */
    private static $__items = [];

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        self::$__items[$name] = $value;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return @self::$__items[$name];
    }
}