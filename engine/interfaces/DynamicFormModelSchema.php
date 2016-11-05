<?php
/**
 * Created by PhpStorm.
 * User: Var Yan
 * Date: 6/12/2016
 * Time: 12:50 PM
 */

namespace engine\interfaces;

/**
 * Interface DynamicFormModelSchema
 * @package engine\interfaces
 */
interface DynamicFormModelSchema extends EngineSchema
{
    /**
     * @return array
     */
    public function getConnections();

    /**
     * @return array
     */
    public function getHidden();

    /**
     * @return array
     */
    public function getCreatable();
}