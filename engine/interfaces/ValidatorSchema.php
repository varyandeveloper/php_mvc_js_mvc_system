<?php
/**
 * Created by PhpStorm.
 * User: Var Yan
 * Date: 6/11/2016
 * Time: 5:39 PM
 */

namespace engine\interfaces;

/**
 * Interface ValidatorSchema
 * @package engine\interfaces
 */
interface ValidatorSchema extends EngineSchema
{
    /**
     * @param array $validationArray
     * @return ValidatorSchema
     */
    public function setRules(array $validationArray) : ValidatorSchema;

    /**
     * @return array
     */
    public function getRules() : array;

    /**
     * @return false|array
     */
    public function getError();

    /**
     * @return bool
     */
    public function getStatus() : bool;
}