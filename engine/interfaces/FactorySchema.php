<?php
/**
 * Created by PhpStorm.
 * User: 37498_000
 * Date: 10.06.2016
 * Time: 14:45
 */

namespace engine\interfaces;

/**
 * Interface FactorySchema
 * @package engine\interfaces
 */
interface FactorySchema extends EngineSchema
{
    /**
     * @param string $className
     * @return mixed
     */
    public static function create(string $className);

    /**
     * @param $object
     * @param string $methodName
     * @return mixed
     */
    public static function injectMethod($object,string $methodName);
}