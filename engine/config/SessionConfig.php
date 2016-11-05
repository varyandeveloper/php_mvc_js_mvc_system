<?php
/**
 * Created by PhpStorm.
 * User: VarYan
 * Date: 28.01.2016
 * Time: 19:19
 */

namespace engine\config;

/**
 * Class SessionConfig
 * @package engine\config
 */
class SessionConfig
{
    /**
     * @var string $encryptionKey
     */
    private static $encryptionKey;
    /**
     * @var int $expireTime
     */
    private static $expireTime;

    /**
     * @param $config
     */
    public static function __init__(array $config)
    {
        foreach ($config as $index => $item) {
            if (property_exists(new self, $index))
                self::${$index} = $item;
        }
    }

    /**
     * @return SessionConfig
     */
    public static function get()
    {
        return new self;
    }

    /**
     * @return string
     */
    public static function getEncryptionKey()
    {
        return self::$encryptionKey;
    }

    /**
     * @return int
     */
    public static function getExpireTime()
    {
        return self::$expireTime;
    }

    /**
     * @param int $expireTime
     */
    public static function setExpireTime(int $expireTime)
    {
        self::$expireTime = $expireTime;
    }

    /**
     * @param string $encryptionKey
     */
    public static function setEncryptionKey(string $encryptionKey)
    {
        self::$encryptionKey = $encryptionKey;
    }
}