<?php
/**
 * Created by PhpStorm.
 * User: VarYan
 * Date: 21.03.2016
 * Time: 22:11
 */

namespace engine\traits\model;

use engine\config\DatabaseConfig;
use engine\objects\Factory;
use engine\objects\model\State;

/**
 * Class ConcatRelation
 * @package engine\traits\model
 */
trait ConcatRelation
{
    /**
     * @var string $delimiter
     */
    protected $__delimiter = "~~";
    /**
     * @var array $_selectFields
     */
    public $_selectFields = [];
    /**
     * @var string $_lastUsedTableName
     */
    protected static $_lastUsedTableName;

    /**
     * @return string
     */
    public static function getLastUsedTableName(): string
    {
        return self::$_lastUsedTableName;
    }

    /**
     * @return string
     */
    public function getDelimiter(): string
    {
        return $this->__delimiter;
    }

    /**
     * @param string $lastUsedTableName
     */
    public static function setLastUsedTableName(string $lastUsedTableName)
    {
        self::$_lastUsedTableName = $lastUsedTableName;
    }

    /**
     * @param string $model
     * @param string $foreignKey
     * @param string $reference
     * @param array|null $selectableRows
     * @return $this
     */
    protected function oneToOne(string $model,string $foreignKey,string $reference = "id",array $selectableRows = null)
    {
        $data = $this->__relation($model,$foreignKey,$reference,$selectableRows);
        $this->_selectFields = array_merge($this->_selectFields,$data['select']);
        State::getInstance()->attach("join",$data['join'][0],$data['join'][1]);
        return $this;
    }

    /**
     * @param string $model
     * @param string $foreignKey
     * @param string $reference
     * @param array|null $selectableRows
     * @return $this
     */
    protected function oneToMany(string $model,string $foreignKey,string $reference = "id",array $selectableRows = null)
    {
        $data = $this->__relation($model,$foreignKey,$reference,$selectableRows);

        $select = explode(",",$data['select'][0]);
        if(sizeof($select) > 1)
        {
            $newSelect = "";
            for($i = 0; $i < sizeof($select); $i++)
            {
                $newSelect .= "GROUP_CONCAT(DISTINCT " . $select[$i] . " SEPARATOR '" . $this->__delimiter . "') AS " . explode(".",$select[$i])[1] . "All";
                if($i < sizeof($select) - 1)
                    $newSelect .= ",";
            }
            $data['select'][0] = $newSelect;
        }
        $this->_selectFields = array_merge($this->_selectFields,$data['select']);
        State::getInstance()->attach("join",$data['join'][0],$data['join'][1]);
        return $this;
    }

    /**
     * @param string $model
     * @param string $relationTableName
     * @param string $foreignKey1
     * @param string $foreignKey2
     * @param string $reference1
     * @param string $reference2
     * @param array|null $selectableRows
     * @return $this
     */
    protected function manyToMany(string $model,string $relationTableName,string $foreignKey1,string $foreignKey2,string $reference1 = "id",string $reference2 = "id", array $selectableRows = null)
    {
        $model = Factory::create($model);

        if(is_null($selectableRows))
            $selectFields[] = $model->_rowDetector();

        elseif(is_array($selectableRows) && sizeof($selectableRows))
            $selectFields = $selectableRows;

        $select = explode(",",$selectFields[0]);
        if(sizeof($select) > 1)
        {
            $newSelect = "";
            for($i = 0; $i < sizeof($select); $i++)
            {
                $newSelect .= "GROUP_CONCAT(DISTINCT " . $select[$i] . " SEPARATOR '" . $this->__delimiter . "') AS " . explode(".",$select[$i])[1] . "All";
                if($i < sizeof($select) - 1)
                    $newSelect .= ",";
            }
            $selectFields[0] = $newSelect;
        }

        $joinArray = [
            $model->getTableName(),
            $model->getTableName() . "." . $reference2 . " IN (SELECT " . $foreignKey2 . " FROM "  . DatabaseConfig::getPrefix().$relationTableName . " WHERE " . $foreignKey1 . " = " . $this->getTableName() . "." . $reference1 . ")",
        ];

        $this->_selectFields = array_merge($this->_selectFields,$selectFields);
        State::getInstance()->attach("join",$joinArray[0],$joinArray[1]);
        return $this;
    }

    /**
     * @param string $model
     * @param string $foreignKey
     * @param string $reference
     * @param array|null $selectableRows
     * @return array
     */
    private function __relation(string $model,string $foreignKey,string $reference = "id",array $selectableRows = null)
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