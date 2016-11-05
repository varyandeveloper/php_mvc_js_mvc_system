<?php

namespace engine\abstracts;

use engine\config\DatabaseConfig;
use engine\ENV;
use engine\interfaces\DatabaseSchema;
use engine\objects\Factory;
use engine\traits\database\QueryBuilder;
use engine\traits\database\Transaction;

/**
 * Class DatabaseParent
 * @package engine\abstracts
 */
abstract class DatabaseParent implements DatabaseSchema
{
    use QueryBuilder,Transaction;
    /**
     * @var \PDO $pdo
     */
    private $__pdo;
    /**
     * @var string $tableName
     */
    private $__tableName;

    /**
     * DatabaseParent constructor.
     */
    public function __construct()
    {
        $config = Factory::create(DatabaseConfig::class);
        switch ($config::getDrive()):
            case "postgres":
            case "mysql":
            case "sql":
                if ($config::getDrive() == "sql")
                    $config::setDrive("dblib");
                $this->__connectPgMysql($config);
                break;
            case "sqlite":
                $this->__connectSqlite($config);
                break;
        endswitch;

        switch (ENV::get()):
            case "development":
                $this->__pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING);
                break;
            case "production":
                $this->__pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_SILENT);
                break;
        endswitch;

        $this->__pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
        $this->__pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, TRUE);
    }

    /**
     * @return string
     */
    public function getTableName() : string
    {
        return DatabaseConfig::getPrefix() . $this->__tableName;
    }

    /**
     * @param string $tableName
     * @return DatabaseSchema
     */
    public function setTableName(string $tableName) : DatabaseSchema
    {
        $this->__tableName = $tableName;
        return $this;
    }

    /**
     * @param DatabaseConfig $config
     */
    private function __connectPgMysql($config)
    {
        try {
            $this->__pdo = new \PDO(
                $config::getDrive() . ":host=" . $config::getHost() . ";dbname=" . $config::getName(),
                $config::getUser(),
                $config::getPass(), [
                    \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . $config::getCharset(),
                ]
            );
        } catch (\PDOException $ex) {
            exit($ex->getMessage());
        }
    }

    /**
     * @param DatabaseConfig $config
     */
    private function __connectSqlite($config)
    {
        try {
            $this->__pdo = new \PDO(
                "{$config::getDrive()}:{$config::getHost()}",
                null,
                null,
                [\PDO::ATTR_PERSISTENT => true]
            );
        } catch (\PDOException $ex) {
            exit($ex->getMessage());
        }
    }

    /**
     * __destruct method
     */
    public function __destruct()
    {
        unset($this->__pdo);
    }
}