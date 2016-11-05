<?php
/**
 * Created by PhpStorm.
 * User: Var Yan
 * Date: 6/18/2016
 * Time: 10:29 AM
 */

namespace engine\config;


class AuthConfig
{
    /**
     * @var array $__credentials
     */
    private static $__credentials;
    /**
     * @var string $__tableName
     */
    private static $__tableName = "users";

    /**
     * @param array $_credentials
     */
    public static function setCredentials($_credentials)
    {
        self::$__credentials = $_credentials;
    }

    /**
     * @param string $_tableName
     */
    public static function setTableName(string $_tableName)
    {
        self::$__tableName = $_tableName;
    }

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return self::$__tableName;
    }

    /**
     * @return array
     */
    public static function getCredentials()
    {
        return self::$__credentials;
    }
}