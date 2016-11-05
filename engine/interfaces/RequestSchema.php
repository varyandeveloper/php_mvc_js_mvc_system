<?php
/**
 * Created by PhpStorm.
 * User: 37498_000
 * Date: 10.06.2016
 * Time: 12:51
 */

namespace engine\interfaces;

/**
 * Class RequestSchema
 * @package engine\interfaces
 */
interface RequestSchema extends EngineSchema
{
    /**
     * @return string
     */
    public function method() : string;

    /**
     * @return array
     */
    public function all() : array;

    /**
     * @param string $key
     * @return mixed
     */
    public function post(string $key);

    /**
     * @param string $key
     * @return string|array
     */
    public function get(string $key);

    /**
     * @param string $key
     * @return array
     */
    public function file(string $key) : array;
}