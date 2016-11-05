<?php

namespace engine\interfaces;

/**
 * Interface ModelSchema
 * @package engine\interfaces
 */
interface ModelSchema extends EngineSchema
{
    /**
     * @param string $column
     * @return ModelSchema
     */
    public function groupBy(string $column) : ModelSchema;

    /**
     * @return string
     */
    public function getTableName() : string;

    /**
     * @param $columnName
     * @return array
     */
    public function enumList($columnName) : array;

    /**
     * @param int $id
     * @return int|bool|null
     */
    public function delete(int $id = null);

    /**
     * @param int $id
     * @param array $dataArray
     * @return int|bool|null
     */
    public function update(int $id, array $dataArray);

    /**
     * @param array $dataArray
     * @return int|bool|null
     */
    public function save(array $dataArray);

    /**
     * @param string $columnName
     * @param $value
     * @return ModelSchema
     */
    public function columnValueEndWith(string $columnName, $value) : ModelSchema;

    /**
     * @param string $columnName
     * @param $value
     * @return ModelSchema
     */
    public function orColumnValueEndWith(string $columnName, $value) : ModelSchema;

    /**
     * @param string $columnName
     * @param $value
     * @return ModelSchema
     */
    public function columnValueStart(string $columnName, $value) : ModelSchema;

    /**
     * @param string $columnName
     * @param $value
     * @return ModelSchema
     */
    public function orColumnValueStart(string $columnName, $value) : ModelSchema;

    /**
     * @param string $columnName
     * @param $value
     * @return ModelSchema
     */
    public function like(string $columnName, $value) : ModelSchema;

    /**
     * @param string $columnName
     * @param $value
     * @return ModelSchema
     */
    public function orLike(string $columnName, $value) : ModelSchema;

    /**
     * @param int $limit
     * @param int|null $offset
     * @return ModelSchema
     */
    public function limit(int $limit, int $offset = null) : ModelSchema;

    /**
     * @return array
     */
    public function all() : array;

    /**
     * @param int|null $id
     * @return \stdClass|bool
     */
    public function one(int $id = null);

    /**
     * @param string $columnName
     * @param string|int|bool $value
     * @return ModelSchema
     */
    public function where(string $columnName, $value) : ModelSchema;

    /**
     * @param string $columnName
     * @param string|int|bool $value
     * @return ModelSchema
     */
    public function orWhere(string $columnName, $value) : ModelSchema;

    /**
     * @param string $columnName
     * @param string $sotType
     * @return ModelSchema
     */
    public function order(string $columnName, string $sotType = "ASC") : ModelSchema;

    /**
     * @param bool $namesOnly
     * @return array
     */
    public function fields(bool $namesOnly = true) : array;
}