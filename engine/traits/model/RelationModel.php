<?php
/**
 * Created by PhpStorm.
 * User: Artur
 * Date: 29-Aug-16
 * Time: 16:31
 */

namespace engine\traits\model;
use engine\objects\model\State;
use engine\traits\Factory;

/**
 * Class RelationModel
 * @package engine\traits\model
 */
trait RelationModel
{
    /**
     * @var string $_lastUsedTableName
     */
    protected static $_lastUsedTableName;

    /**
     * @param string $relationModel
     * @param string $foreignKey
     * @param string $reference
     * @param array|null $selectableRows
     */
    public function hasMany(string $relationModel,string $foreignKey, string $reference = "id",array $selectableRows = null)
    {

    }

    /**
     * @param string $relationModel
     * @param string $foreignKey
     * @param string $reference
     * @param array|null $selectableRows
     * @return $this
     */
    public function oneToOne(string $relationModel,string $foreignKey, string $reference = "id",array $selectableRows = null)
    {
        $data = $this->_relation($relationModel,$foreignKey,$reference,$selectableRows);
        $this->_selectFields = array_merge($this->_selectFields,$data['select']);
        State::getInstance()->attach("join",$data['join'][0],$data['join'][1]);
        return $this;
    }

    /**
     * @param string $model
     * @param string $foreignKey
     * @param string $reference
     * @param array|null $selectableRows
     * @return array
     */
    protected function _relation(string $model,string $foreignKey,string $reference = "id",array $selectableRows = null)
    {
        $selectFields = [];
        $model = Factory::create($model);
        self::$_lastUsedTableName = $model->getTableName();

        if(is_null($selectableRows))
            $selectFields[] = $model->_rowDetector();

        elseif(is_array($selectableRows) && sizeof($selectableRows))
            $selectFields = $selectableRows;

        $joinArray = [
            $model->getTableName(),
            $model->getTableName() . "." . $foreignKey . " = " . $this->getTableName() . "." . $reference
        ];

        return [
            "select"=>$selectFields,
            "join"=>$joinArray
        ];
    }
}