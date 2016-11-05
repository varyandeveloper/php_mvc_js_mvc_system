<?php
/**
 * Created by PhpStorm.
 * User: 37498_000
 * Date: 10.06.2016
 * Time: 12:10
 */

namespace engine\abstracts;

use engine\config\DatabaseConfig;
use engine\Engine;
use engine\interfaces\ModelSchema;
use engine\objects\Database;
use engine\objects\Factory;
use engine\objects\model\Methods;
use engine\traits\Instance;

/**
 * Class ModelParent
 * @package engine\abstracts
 */
abstract class ModelParent
{
    use Instance;
    /**
     * @var array $_childModels
     */
    protected $_childModels = [];
    /**
     * @var array $_updateRules
     */
    protected $_updateRules;
    /**
     * @var array $_createRules
     */
    protected $_createRules;
    /**
     * @var array $_privateFields
     * Will not select columns mentioned in array above on select queries
     */
    protected $_privateFields;
    /**
     * @var array $_privateFields
     * Will select columns mentioned in array above on select queries
     */
    protected $_publicFields;
    /**
     * @var string $_primaryKey
     */
    protected $_primaryKey = "id";
    /**
     * @var Database $db
     * Will keep Database object
     */
    protected $_db;
    /**
     * @var string $_tableName
     * Set table name if the model name not match with table name or table name with symbol(s)
     */
    protected $_tableName;
    /**
     * @var array $__nullInstanceMethods
     */
    private static $__nullInstanceMethods = ["get","one","all"];

    /**
     * Model constructor.
     */
    public function __construct()
    {
        $this->_db = Factory::create(ENGINE . "objects" . DS . "Database");
        $this->__init();
        $this->_selectFields = [$this->_rowDetector()];
    }

    /**
     * @param $name
     * @param $arguments
     * @return ModelSchema
     */
    public function __call($name, $arguments)
    {
        return self::_callMaster($name,$arguments);
    }

    /**
     * @param $name
     * @param $arguments
     * @return ModelSchema
     */
    public static function __callStatic($name, $arguments)
    {
        return self::_callMaster($name,$arguments);
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    protected static function _callMaster($name, $arguments)
    {
        $calledModel = get_called_class();

        if(method_exists(Methods::class,$name))
        {
            $result = call_user_func_array([Methods::getInstance($calledModel),$name],$arguments);
            if(in_array($name,self::$__nullInstanceMethods))
                Methods::removeInstance();
            return $result;
        }
        else
            exit("Class {$calledModel} dose not have {$name} method");
    }

    /**
     * @return array
     */
    public function getSelectFields(): array
    {
        return array_unique($this->_selectFields);
    }

    /**
     * @return array|null
     */
    public function getCreateRules()
    {
        return $this->_createRules;
    }

    /**
     * @return array|null
     */
    public function getUpdateRules()
    {
        return $this->_updateRules;
    }

    /**
     * @param bool $withPrefix
     * @return string
     */
    public function getTableName($withPrefix = true): string
    {
        return $withPrefix
            ? DatabaseConfig::getPrefix().$this->_tableName
            : $this->_tableName;
    }

    /**
     * @return string
     */
    public function getPrimaryKey(): string
    {
        return $this->_primaryKey;
    }

    /**
     * @return array
     */
    public function getChildModels(): array
    {
        return $this->_childModels;
    }

    /**
     * @param string $name
     */
    protected function _addChildModel($name)
    {
        $this->_childModels[] = $name;
    }

    /**
     * __rowDetector method
     * @return string
     */
    protected function _rowDetector()
    {
        $selectable = $this->_publicFields
            ? $this->_publicFields
            : ($this->_privateFields
                ? array_diff(array_column($this->_db->getSchema($this->getTableName(false))->fetchAll(\PDO::FETCH_OBJ),"Field"), $this->_privateFields)
                : null);

        if (!is_null($selectable)) {
            array_walk($selectable, function (&$value) {
                $value = $this->_db->getTableName() . "." . $value;
            });
            return join(",", $selectable);
        }
        return "{$this->getTableName()}.*";
    }

    /**
     * _init_ method
     * @return void
     */
    private function __init()
    {
        $parts = explode(DS, get_called_class());
        $className = end($parts);
        $currentModel = Engine::splitAtUpperCase($className);

        array_pop($currentModel);
        array_shift($currentModel);
        $name = strtolower(join("_",$currentModel));

        if (is_null($this->_tableName)):
            if ($this->_db->tableExists($name . 's')):
                $this->_tableName = $name . 's';
            elseif ($this->_db->tableExists($name)):
                $this->_tableName = $name;
            endif;
        endif;
        if (is_null($this->_tableName))
            exit("Table with name {$name}/{$name}s not found in your database");
        $this->_db->setTableName($this->_tableName);
    }
}