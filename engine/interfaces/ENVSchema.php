<?php
/**
 * Created by PhpStorm.
 * User: Artur
 * Date: 26-Aug-16
 * Time: 09:58
 */

namespace engine\interfaces;

/**
 * Interface ENVSchema
 * @package engine\interfaces
 */
interface ENVSchema extends EngineSchema
{
    /**
     * @return string
     */
    public static function get() : string;
}