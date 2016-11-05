<?php
/**
 * Created by PhpStorm.
 * User: Var Yan
 * Date: 5/3/2016
 * Time: 8:59 PM
 */

namespace engine\config;
use engine\Engine;

/**
 * Class DatabaseConfig
 * @package engine\config
 */
class DatabaseConfig
{
    /**
     * @var array $queryDifferences
     */
    private static $queryDifferences = [
        "mysql"=>[
            "tableExists"=>"SHOW TABLES LIKE '$1'",
        ],
        "sqlite"=>[
            "tableExists"=>"SELECT name FROM sqlite_master WHERE type = 'table' AND name = '$1'"
        ]
    ];
    /**
     * @var array $connections
     */
    private static $connections = [];
    /**
     * @var string $default
     */
    private static $default;

    /**
     * __init__ method
     * @param array $config
     * @return void
     * */
    public static function __init__($config)
    {
        foreach ($config as $index => $item) {
            self::$connections[$index] = $item;
            if($index == "default")
                self::$default = "default";
        }
    }

    /**
     * @param string $key
     * @return object|null
     */
    public static function getConnection(string $key)
    {
        return !empty(self::$connections[$key])
            ? (object)self::$connections[$key]
            : null;
    }

    /**
     * @param string $default
     */
    public static function setDefault(string $default)
    {
        self::$default = $default;
    }

    /**
     * @return array
     */
    public static function getQueryDifferences()
    {
        return self::$queryDifferences[self::getDrive()];
    }

    /**
     * get method
     * @return static
     * */
    public static function get()
    {
        return new static;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return string|int|null
     */
    public function __call($name, $arguments)
    {
        return self::_callMaster($name,$arguments);
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return string|int|null
     */
    public static function __callStatic($name, $arguments)
    {
        return self::_callMaster($name,$arguments);
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return string|int|null
     */
    protected static function _callMaster($name,$arguments)
    {
        $config = self::getConnection(self::$default);

        if(is_null($config))
            exit("Please set default database configuration");

        $nameParts = Engine::splitAtUpperCase($name);
        $itemToGet = strtolower($nameParts[1]);

        if(!property_exists($config,$itemToGet))
            exit("Undefined method {$name}");

        return @$config->{$itemToGet};
    }
}