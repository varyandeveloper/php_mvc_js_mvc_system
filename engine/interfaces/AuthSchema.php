<?php
/**
 * Created by PhpStorm.
 * User: 37498_000
 * Date: 10.06.2016
 * Time: 12:12
 */

namespace engine\interfaces;

/**
 * Interface AuthSchema
 * @package engine\interfaces
 */
interface AuthSchema extends EngineSchema
{
    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, $arguments);

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments);

    /**
     * @return bool
     */
    public static function status() : bool;
}