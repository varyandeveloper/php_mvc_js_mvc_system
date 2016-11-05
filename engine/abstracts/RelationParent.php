<?php
/**
 * Created by PhpStorm.
 * User: Var Yan
 * Date: 8/3/2016
 * Time: 9:50 PM
 */

namespace engine\abstracts;

/**
 * Class RelationParent
 * @package engine\abstracts
 */
abstract class RelationParent
{
    /**
     * @param string $modelName
     * @param string $foreignKey
     * @param string $refKey
     * @return mixed
     */
    protected abstract function _oneToOne(string $modelName,string $foreignKey, string $refKey = "id");

    /**
     * @param string $modelName
     * @param string $foreignKey
     * @param string $refKey
     * @return mixed
     */
    protected abstract function _oneToMany(string $modelName,string $foreignKey, string $refKey = "id");

    /**
     * @param string $modelName
     * @param string $relationModelName
     * @param string $firstForeignKey
     * @param string $secondForeignKey
     * @param string $firstRefKey
     * @param string $secondRefKey
     * @return mixed
     */
    protected abstract function _manyToMany(string $modelName,string $relationModelName, string $firstForeignKey,string $secondForeignKey,string $firstRefKey = "id",string $secondRefKey = "id");
}