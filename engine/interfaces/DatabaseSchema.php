<?php

namespace engine\interfaces;

/**
 * Interface database
 * @package engine\interfaces
 */
interface DatabaseSchema extends EngineSchema
{
    /**
     * @param string $tableName
     * @return \PDOStatement
     */
    public function getSchema(string $tableName = null) : \PDOStatement;

    /**
     * @return int
     */
    public function rowCount() : int;

    /**
     * @param string|null $tableName
     * @return bool
     */
    public function tableExists(string $tableName = null) : bool;

    /**
     * @return \PDOStatement
     */
    public function getReferenceTable() : \PDOStatement;

    /**
     * @param string $columnName
     * @return \stdClass
     */
    public function enumField(string $columnName) : \stdClass;

    /**
     * @param $tblOrTables
     * @param string|null $condition
     * @param string $side
     * @return DatabaseSchema
     */
    public function join($tblOrTables, string $condition = null, string $side = 'inner') :DatabaseSchema;

    /**
     * @param $colOrCols
     * @param string $dimension
     * @return DatabaseSchema
     */
    public function order($colOrCols, string $dimension = 'ASC') : DatabaseSchema;

    /**
     * @param $colOrCols
     * @return DatabaseSchema
     */
    public function group($colOrCols) : DatabaseSchema;

    /**
     * @param int $startOrLimit
     * @param int|null $offset
     * @return DatabaseSchema
     */
    public function limit(int $startOrLimit, int $offset = null) : DatabaseSchema;

    /**
     * @return DatabaseSchema
     */
    public function delete() : DatabaseSchema;

    /**
     * @param $havingArrayOrColumn
     * @param string $dimension_or_value
     * @return DatabaseSchema
     */
    public function having($havingArrayOrColumn, string $dimension_or_value = 'and') : DatabaseSchema;

    /**
     * @param string $column
     * @param array $array
     * @param string|null $dimension
     * @return DatabaseSchema
     */
    public function whereIn(string $column, array $array, string $dimension = null) : DatabaseSchema;

    /**
     * @param $matchColumns
     * @param string $againstValue
     * @param bool $booleanMode
     * @param string $whereDimension
     * @return DatabaseSchema
     */
    public function fullText($matchColumns, string $againstValue, bool $booleanMode = TRUE, string $whereDimension = "and") : DatabaseSchema;

    /**
     * @param string $column
     * @param null $value
     * @param string $dimension
     * @param string $type
     * @return DatabaseSchema
     */
    public function like(string $column, $value = null, string $dimension = "AND", string $type = "*") : DatabaseSchema;

    /**
     * @param string $column
     * @param string $as
     * @param bool $distinct
     * @param string $delimiter
     * @return DatabaseSchema
     */
    public function concat(string $column, string $as, bool $distinct = TRUE, string $delimiter = ',') : DatabaseSchema;

    /**
     * @param array $data
     * @return int|null
     */
    public function update(array $data);

    /**
     * @param array $data
     * @return int|null
     */
    public function insert(array $data);

    /**
     * @param array $data
     * @return int|null
     */
    public function insertBatch(array $data);

    /**
     * @param string $column
     * @return DatabaseSchema
     */
    public function distinct(string $column = '*') : DatabaseSchema;

    /**
     * @return DatabaseSchema
     */
    public function count() : DatabaseSchema;

    /**
     * @param string $columns
     * @return DatabaseSchema
     */
    public function select(string $columns = "*") : DatabaseSchema;

    /**
     * @param string $tableName
     * @return DatabaseSchema
     */
    public function from(string $tableName) : DatabaseSchema;

    /**
     * @param $columnOrColumns
     * @param $valueForColumn
     * @param string $dimension
     * @return DatabaseSchema
     */
    public function where($columnOrColumns, $valueForColumn, string $dimension = "AND") : DatabaseSchema;

    /**
     * @return \PDOStatement
     */
    public function query() : \PDOStatement;
}