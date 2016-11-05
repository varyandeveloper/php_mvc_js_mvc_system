<?php
/**
 * Created by PhpStorm.
 * User: Var Yan
 * Date: 6/18/2016
 * Time: 10:56 PM
 */

namespace engine\interfaces;

/**
 * Interface HTMLSchema
 * @package engine\interfaces
 */
interface HTMLSchema extends EngineSchema
{
    /**
     * @return string
     */
    public function __toString() : string ;
    public function close();
}