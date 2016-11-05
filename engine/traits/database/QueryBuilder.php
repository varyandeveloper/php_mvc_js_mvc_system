<?php
/**
 * Created by PhpStorm.
 * User: Var Yan
 * Date: 5/29/2016
 * Time: 3:37 PM
 */

namespace engine\traits\database;
use engine\config\DatabaseConfig;
use engine\Engine;
use engine\interfaces\DatabaseSchema;

/**
 * Class QueryBuilder
 * @package VS\Additional
 */
trait QueryBuilder
{
    /**
     * @var string $query
     * */
    private $__query;
    /**
     * @var string $create
     * */
    private $__create;
    /**
     * @var string $select
     * */
    private $__select;
    /**
     * @var string $from
     * */
    private $__from;
    /**
     * @var string $insert
     * */
    private $__insert;
    /**
     * @var array $insertParams
     */
    private $__insertParams = [];
    /**
     * @var string $update
     * */
    private $__update;
    /**
     * @var string $update
     * */
    private $__delete;
    /**
     * @var string $where
     * */
    private $__where;
    /**
     * @var string $where
     * */
    private $__between;
    /**
     * @var string $where
     * */
    private $__having;
    /**
     * @var string $limit
     * */
    private $__limit;
    /**
     * @var string $order
     * */
    private $__order;
    /**
     * @var string $group
     * */
    private $__group;
    /**
     * @var string $join
     * */
    private $__join;
    /**
     * @var int $lastInsertId
     * */
    private $__lastInsertId;
    /**
     * @var int $__rowCount
     * */
    private $__rowCount;
    /**
     * @var \PDOStatement $__result
     * */
    private $__result;

    /**
     * select method
     * @param string $columns
     * @return DatabaseSchema
     * */
    public function select(string $columns = '*') : DatabaseSchema
    {
        $this->__select = "SELECT {$columns} ";
        return $this;
    }

    /**
     * count method
     * @return DatabaseSchema
     * */
    public function count() : DatabaseSchema
    {
        $this->__select = "SELECT COUNT(*) ";
        return $this;
    }

    /**
     * distinct method
     * @param string $column
     * @return DatabaseSchema
     * */
    public function distinct(string $column = '*') : DatabaseSchema
    {
        $this->__select = " DISTINCT {$column} ";
        $this->__from = "FROM {$this->getTableName()} ";
        return $this;
    }

    /**
     * from method
     * @param null|string $tableName
     * @return DatabaseSchema
     * */
    public function from(string $tableName = null) : DatabaseSchema
    {
        if (!is_null($tableName))
            $this->setTableName($tableName);

        $this->__from = "FROM {$this->getTableName()} ";
        return $this;
    }

    /**
     * @param array $data
     * @return int|null
     */
    public function insert(array $data)
    {
        $rows = implode(',', array_keys($data));
        $vals = array_values($data);
        $values = "";
        for ($i = 0; $i < sizeof($vals); $i++) {
            $values .= "'" . $vals[$i] . "'";
            if ($i < sizeof($vals) - 1)
                $values .= ",";
        }
        $this->__insert = " INSERT INTO " . $this->getTableName() . " ($rows) VALUES ($values)";

        $this->query();
        return @$this->lastInsertId;
    }

    /**
     * @param array $data
     * @return int
     */
    public function insertBatch(array $data)
    {
        $values = [];
        $rows = join(',', array_keys(@$data[0]));
        $valuesArray = array_values($data);
        for($i = 0; $i < sizeof($valuesArray); $i++)
        {
            if(Engine::isAssoc($valuesArray[$i]))
                $valuesArray[$i] = array_values($valuesArray[$i]);

            $values[] = "(".join(',',$valuesArray[$i]).")";
        }
        $this->__insert = " INSERT INTO " . $this->getTableName() . " ($rows) VALUES ".join(',',$values);

        $this->query();
        return @$this->rowCount();
    }

    /**
     * @param array $data
     * @return int|null
     */
    public function update(array $data)
    {
        $this->__update .= " UPDATE " . $this->getTableName() . " SET ";
        $counter = 0;
        foreach ($data as $row => $value) {
            $this->__update .= " {$row} = '{$value}'";
            $counter++;
            if ($counter < sizeof($data)) {
                $this->__update .= ", ";
            }
        }
        $this->__update .= " ";
        $this->query();
        return $this->rowCount();
    }

    /**
     * @param string $column
     * @param string $as
     * @param bool $distinct
     * @param string $delimiter
     * @return DatabaseSchema
     */
    public function concat(string $column, string $as, bool $distinct = TRUE, string $delimiter = ',') : DatabaseSchema
    {
        $this->__select = empty(trim($this->__select))
            ? " SELECT GROUP_CONCAT("
            : $this->__select . ", GROUP_CONCAT(";

        if ($distinct === TRUE)
            $this->__select .= "DISTINCT ";

        $this->__select .= "{$column}";
        $this->__select .= " SEPARATOR '{$delimiter}') AS {$as} ";

        return $this;
    }

    /**
     * where method
     * @param string|array $columnOrColumns
     * @param null|string $valueForItem
     * @param string $dimension
     * @return DatabaseSchema
     * */
    public function where($columnOrColumns, $valueForItem = null, string $dimension = "AND") : DatabaseSchema
    {
        $this->__where = empty(trim($this->__where)) ? " WHERE " : $this->__where . " " . strtoupper($dimension) . " ";
        if (is_array($columnOrColumns)) :
            $counter = 0;
            foreach ($columnOrColumns as $column => $value) :
                $counter++;
                $this->__where .= "{$column} = '{$value}' ";
                if ($counter < sizeof($columnOrColumns))
                    $this->__where .= " " . strtoupper($dimension) . " ";
            endforeach;
        else :
            if(strpos($valueForItem,"SELECT") !== FALSE && strpos($valueForItem,"FROM") !== FALSE)
                $this->__where .= "{$columnOrColumns} = ({$valueForItem})";
            else
                $this->__where .= "{$columnOrColumns} = '{$valueForItem}'";
        endif;

        return $this;
    }

    /**
     * @param string $column
     * @param null $value
     * @param string $dimension
     * @param string $type
     * @return DatabaseSchema
     */
    public function like(string $column, $value = null, string $dimension = "AND", string $type = "*") : DatabaseSchema
    {
        $this->__where = empty(trim($this->__where)) ? " WHERE " : $this->__where . " " . strtoupper($dimension) . " ";
        $this->__where .= " {$column} LIKE '";
        switch ($type)
        {
            case "*":
            case "all":
                $this->__where .= "%{$value}%'";
                break;
            case ">":
            case "begin":
                $this->__where .= "{$value}%'";
                break;
            case "<":
            case "end":
                $this->__where .= "%{$value}'";
                break;
        }
        return $this;
    }

    /**
     * @param string|array $matchColumns
     * @param string $againstValue
     * @param bool $booleanMode
     * @param string $whereDimension
     * @return DatabaseSchema
     */
    public function fullText($matchColumns, string $againstValue, bool $booleanMode = TRUE, string $whereDimension = "and") : DatabaseSchema
    {
        $this->__where = empty(trim($this->__where)) ? " WHERE " : $this->__where . " " . strtoupper($whereDimension) . " ";

        $this->__where .= is_array($matchColumns)
            ? "MATCH(" . implode(",", $matchColumns) . ")"
            : "MATCH($matchColumns)";

        $this->__where .= " AGAINST('{$againstValue}'";

        if ($booleanMode === TRUE)
            $this->__where .= " IN BOOLEAN MODE";

        $this->__where .= ")";

        return $this;
    }

    /**
     * whereIn method
     * @param string $column
     * @param array $array
     * @param string $dimension
     * @return DatabaseSchema
     * */
    public function whereIn(string $column, array $array, string $dimension = null) : DatabaseSchema
    {
        $items = "";
        for ($i = 0; $i < sizeof($array); $i++) {
            $items .= "'" . $array[$i] . "'";
            if ($i < sizeof($array) - 1)
                $items .= ",";
        }

        $this->__where .= empty(trim($this->__where))
            ? "WHERE {$column} IN (" . $items . ") "
            : strtoupper($dimension) . " {$column} IN ({$items}) ";

        return $this;
    }

    /**
     * having method
     * @param string|array $havingArrayOrColumn
     * @param string $dimension_or_value
     * @return DatabaseSchema
     * */
    public function having($havingArrayOrColumn, string $dimension_or_value = 'AND') : DatabaseSchema
    {
        $this->__having = " HAVING ";
        if (is_array($havingArrayOrColumn)) {
            $counter = 0;
            foreach ($havingArrayOrColumn as $column => $value) {
                $this->__having .= " {$column} = '{$value}' ";
                $counter++;
                if ($counter < sizeof($havingArrayOrColumn))
                    $this->__having .= " " . $dimension_or_value . " ";
            }
        } else {
            $this->__having .= "{$havingArrayOrColumn} = '{$dimension_or_value}'";
        }

        return $this;
    }

    /**
     * delete method
     * @return DatabaseSchema
     * */
    public function delete() : DatabaseSchema
    {
        $this->__delete .= " DELETE FROM {$this->getTableName()} ";
        $this->query();

        return $this->rowCount();
    }

    /**
     * @param int $startOrLimit
     * @param int|null $offset
     * @return DatabaseSchema
     */
    public function limit(int $startOrLimit, int $offset = null) : DatabaseSchema
    {
        $this->__limit = " LIMIT {$startOrLimit} ";
        if (!is_null($offset))
            $this->__limit .= ",{$offset} ";

        return $this;
    }

    /**
     * group method
     * @param string|array $colOrCols
     * @return DatabaseSchema
     * */
    public function group($colOrCols) : DatabaseSchema
    {
        $this->__group = empty(trim($this->__group)) ? " GROUP BY " : $this->__group . ", ";

        if (is_array($colOrCols)):
            for ($i = 0; $i < sizeof($colOrCols); $i++):
                $this->__group .= "{$colOrCols[$i]} ";
                if ($i < sizeof($colOrCols))
                    $this->__group .= ", ";
            endfor;
        else:
            $this->__group .= "{$colOrCols} ";
        endif;

        return $this;
    }

    /**
     * order method
     * @param string|array $colOrCols
     * @param string $dimension
     * @return DatabaseSchema object
     * */
    public function order($colOrCols, string $dimension = 'ASC') : DatabaseSchema
    {
        $this->__order = empty(trim($this->__order)) ? " ORDER BY " : $this->__order . ", ";

        $counter = 0;
        if (is_array($colOrCols)) {
            foreach ($colOrCols as $column => $value) {
                $this->__order .= " {$column} = '{$value}' ";
                $counter++;
                if ($counter < sizeof($colOrCols))
                    $this->__order .= ", ";
            }
        } else {
            $this->__order .= " {$colOrCols} " . strtoupper($dimension);
        }

        return $this;
    }

    /**
     * join method
     * @param string|array $tblOrTables
     * @param string $condition
     * @param string $side
     * @return DatabaseSchema
     * */
    public function join($tblOrTables, string $condition = null, string $side = 'left') :DatabaseSchema
    {
        if (is_array($tblOrTables)) {
            foreach ($tblOrTables as $tbl) {
                $this->__join .= isset($tbl[2]) ? " " . strtoupper($tbl[2]) : " " . strtoupper($side) . " JOIN ";
                $this->__join .= $tbl[0] . " ON " . $tbl[1];
            }
        } else
            $this->__join .= strtoupper($side) . " JOIN {$tblOrTables} ON {$condition} ";

        $this->__join .= " ";

        return $this;
    }

    /**
     * @param string $columnName
     * @return \stdClass
     */
    public function enumField(string $columnName) : \stdClass
    {
        $query = $this->query("SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '{$this->getTableName()}' AND COLUMN_NAME = '{$columnName}'");
        return $query->fetch();
    }

    /**
     * @return \PDOStatement
     */
    public function getReferenceTable() : \PDOStatement
    {
        return $this
            ->query("
            SELECT TABLE_NAME as tableName
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
            WHERE REFERENCED_TABLE_NAME = '{$this->getTableName()}'");
    }

    /**
     * @param string|null $tableName
     * @return bool
     */
    public function tableExists(string $tableName = null) : bool
    {
        if(!is_null($tableName))
            $this->setTableName($tableName);
        return ($this->query(str_replace("$1",$this->getTableName(),DatabaseConfig::getQueryDifferences()[__FUNCTION__]))->fetch() !== FALSE);
    }

    /**
     * rowCount method
     * @return integer
     * */
    public function rowCount() : int
    {
        return $this->__rowCount;
    }

    /**
     * @param string $tableName
     * @return \PDOStatement
     */
    public function getSchema(string $tableName = null) : \PDOStatement
    {
        if(!is_null($tableName))
            $this->setTableName($tableName);

        $this->query("DESCRIBE {$this->getTableName()}");
        return $this->__result;
    }
}