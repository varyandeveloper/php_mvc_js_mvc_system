<?php
/**
 * Created by PhpStorm.
 * User: Var Yan
 * Date: 8/8/2016
 * Time: 10:27 PM
 */

namespace engine\objects\model;

use engine\objects\Collection;
use engine\objects\Database;
use engine\objects\Factory;
use engine\traits\Instance;

/**
 * Class Methods
 * @package engine\objects\model
 */
final class Methods
{
    use Instance;

    /**
     * @var \engine\objects\Database
     */
    protected $_db;
    /**
     * @var \engine\objects\Model $_model
     */
    protected $_model;
    /**
     * @var bool $useLastTableName
     */
    protected $_useLastTableName = false;

    /**
     * Methods constructor.
     * @param string $modelName
     */
    public function __construct($modelName)
    {
        $this->_model = Factory::create($modelName);
        $this->_db = Database::getInstance();
    }

    /**
     * @param string $modelMethodName
     * @param \Closure $callback
     * @return mixed
     */
    public function with(string $modelMethodName, \Closure $callback = null)
    {
        if (!method_exists($this->_model, $modelMethodName))
            exit("Method {$modelMethodName} dose not exists");

        $method = $this->_model->{$modelMethodName}();

        if (!is_null($callback)) {
            $this->_useLastTableName = true;
            Factory::injectFunction($callback, [$this->_model]);
        }

        $this->_useLastTableName = false;

        return $method;
    }

    /**
     * @return Collection
     */
    public function get()
    {
        $data = State::getInstance()
            ->attach("select", join(",", $this->_model->getSelectFields()))
            ->attach("from", $this->_model->getTableName(false))
            ->state();

        return Factory::create(Collection::class,$data);
    }

    /**
     * @return \stdClass
     */
    public function getOne()
    {
        return State::getInstance()
            ->attach("select", join(",", $this->_model->getSelectFields()))
            ->attach("from", $this->_model->getTableName(false))
            ->attach("limit", 1)
            ->state()->fetch(\PDO::FETCH_OBJ);
    }

    /**
     * @return Collection
     */
    public function all()
    {
        $data = State::getInstance()
            ->attach("from", $this->_model->getTableName(false))
            ->attach("select")
            ->state();

        return Factory::create(Collection::class,$data);
    }

    /**
     * @param null $pKey
     * @return \stdClass
     */
    public function one($pKey = null)
    {
        State::getInstance()
            ->attach("from", $this->_model->getTableName(false))
            ->attach("select");

        if (!is_null($pKey))
            State::getInstance()->attach("where", $this->_correctColumnName($this->_model->getPrimaryKey()), $pKey);

        return State::getInstance()->state()->fetch(\PDO::FETCH_OBJ);
    }

    /**
     * @param string $columnName
     * @param $value
     * @return $this
     */
    public function where(string $columnName, $value)
    {
        State::getInstance()->attach("where", $this->_correctColumnName($columnName), $value);
        return $this->_model;
    }

    /**
     * @param string $columnName
     * @param $value
     * @return $this
     */
    public function whereIn(string $columnName, $value)
    {
        State::getInstance()->attach("whereIn", $this->_correctColumnName($columnName), $value);
        return $this->_model;
    }

    /**
     * @param string|array $columnOrColumns
     * @param string $value
     * @return \engine\objects\Model|object
     */
    public function match($columnOrColumns,string $value)
    {
        if(is_array($columnOrColumns))
        {
            array_walk($columnOrColumns,function (&$value){
                $value =  $this->_correctColumnName($value);
            });
            $columnOrColumns = join(",",$columnOrColumns);
        }else
            $columnOrColumns = $this->_correctColumnName($columnOrColumns);

        State::getInstance()->attach("fullText",$columnOrColumns,$value);
        return $this->_model;
    }

    /**
     * @param string $columnName
     * @param $value
     * @return $this
     */
    public function orWhere(string $columnName, $value)
    {
        State::getInstance()->attach("where", $this->_correctColumnName($columnName), $value, "OR");
        return $this->_model;
    }

    /**
     * @param string $columnName
     * @param string $sotType
     * @return $this
     */
    public function order(string $columnName, string $sotType = "ASC")
    {
        State::getInstance()->attach("order", $this->_correctColumnName($columnName), $sotType);
        return $this->_model;
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return $this
     */
    public function limit(int $limit, int $offset = null)
    {
        State::getInstance()->attach("limit", $limit, $offset);
        return $this->_model;
    }

    /**
     * @param string $columnName
     * @param $value
     * @return $this
     */
    public function columnValueStart(string $columnName, $value)
    {
        State::getInstance()->attach("like", $this->_correctColumnName($columnName), $value, "AND", ">");
        return $this->_model;
    }

    /**
     * @param string $columnName
     * @param $value
     * @return $this
     */
    public function orColumnValueStart(string $columnName, $value)
    {
        State::getInstance()->attach("like", $this->_correctColumnName($columnName), $value, "OR", ">");
        return $this->_model;
    }

    /**
     * @param string $columnName
     * @param $value
     * @return $this
     */
    public function columnValueEndWith(string $columnName, $value)
    {
        State::getInstance()->attach("like", $this->_correctColumnName($columnName), $value, "AND", "<");
        return $this->_model;
    }

    /**
     * @param string $columnName
     * @param $value
     * @return $this
     */
    public function orColumnValueEndWith(string $columnName, $value)
    {
        State::getInstance()->attach("like", $this->_correctColumnName($columnName), $value, "OR", "<");
        return $this->_model;
    }

    /**
     * @param string $columnName
     * @return $this
     */
    public function groupBy(string $columnName)
    {
        State::getInstance()->attach("group", $this->_correctColumnName($columnName));
        return $this->_model;
    }

    /**
     * @param string $columnName
     * @param $value
     * @return $this
     */
    public function like(string $columnName, $value)
    {
        State::getInstance()->attach("like", $this->_correctColumnName($columnName), $value);
        return $this->_model;
    }

    /**
     * @param string $modelName
     * @param string $primaryColumn
     * @param string $joinColumn
     * @param string $side
     * @return \engine\objects\Model|object
     */
    public function join(string $modelName,string $primaryColumn,string $joinColumn,string $side = "left")
    {
        $model = Factory::create($modelName);
        $this->_model->_selectFields[] = join($model->getSelectFields());
        State::getInstance()->attach("join",$model->getTableName(),$this->_correctColumnName($primaryColumn)." = ".$model->getTableName().".{$joinColumn}",$side);
        return $this->_model;
    }

    /**
     * @param string $columnName
     * @param $value
     * @return $this
     */
    public function orLike(string $columnName, $value)
    {
        State::getInstance()->attach("like", $this->_correctColumnName($columnName), $value, "OR");
        return $this->_model;
    }

    /**
     * @param string|null $pKey
     * @return int|bool|null
     */
    public function delete(string $pKey = null)
    {
        return State::getInstance()
            ->attach("where", $this->_correctColumnName($this->_model->getPrimaryKey()), $pKey)
            ->attach('delete')->state(false);
    }

    /**
     * @param string $pKey
     * @param array $dataArray
     * @return int|null|\engine\objects\Validator
     */
    public function update(string $pKey, array $dataArray)
    {
        $rules = $this->_model->getUpdateRules();
        if (is_array($rules)) {
            $validator = Factory::create(ENGINE . "objects" . DS . "Validator");
            $validator->setRules($rules);
            if ($validator->getStatus() === FALSE)
                return $validator;
        }
        return State::getInstance()
            ->attach("where", $this->_correctColumnName($this->_model->getPrimaryKey()), $pKey)
            ->attach("update", $dataArray)->state(false);
    }

    /**
     * @param array $dataArray
     * @return int|null|\engine\objects\Validator
     */
    public function save(array $dataArray)
    {
        $rules = $this->_model->getCreateRules();
        if (is_array($rules)) {
            $validator = Factory::create(ENGINE . "objects" . DS . "Validator");
            $validator->setRules($rules);
            if ($validator->getStatus() === FALSE)
                return $validator;
        }
        return !is_array($dataArray[0])
            ? State::getInstance()->attach("insert", $dataArray)->state(false)
            : State::getInstance()->attach("insertBatch", $dataArray)->state(false);
    }

    /**
     * @param bool $namesOnly
     * @return array
     */
    public function fields(bool $namesOnly = true) : array
    {
        $schema = $this->_db->getSchema($this->_model->getTableName(false))->fetchAll(\PDO::FETCH_ASSOC);

        return $namesOnly
            ? array_column($schema, "Field")
            : $schema;
    }

    /**
     * @param $columnName
     * @return array
     */
    public function enumList($columnName) : array
    {
        $row = $this->_db->enumField($this->_correctColumnName($columnName));
        $enumList = explode(",", str_replace("'", "", substr($row->COLUMN_TYPE, 5, (strlen($row->COLUMN_TYPE) - 6))));
        return $enumList;
    }

    /**
     * @param string $columnName
     * @return string
     */
    protected function _correctColumnName(string $columnName)
    {
        $model = $this->_model;
        return $this->_useLastTableName
            ? $model::getLastUsedTableName() . ".{$columnName}"
            : $this->_model->getTableName() . ".{$columnName}";
    }
}