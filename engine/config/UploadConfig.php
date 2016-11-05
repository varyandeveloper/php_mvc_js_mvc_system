<?php
/**
 * Created by PhpStorm.
 * User: VarYan
 * Date: 16.01.2016
 * Time: 22:13
 */

namespace engine\config;

/**
 * Class UploadConfig
 * @package engine\config
 */
class UploadConfig
{
    /**
     * @var array $allowedTypes
     * */
    private static $allowedTypes = ['png', 'jpg', 'jpeg', 'gif'];
    /**
     * @var int $maxWidth
     * */
    private static $maxWidth = 1024;
    /**
     * @var int $maxHeight
     * */
    private static $maxHeight = 768;
    /**
     * @var int $maxSize
     * */
    private static $maxSize = 5;
    /**
     * @var string $uploadPath
     * */
    private static $uploadPath = "";
    /**
     * @var bool $randomName
     * */
    private static $randomName = true;

    /**
     * init method
     * @param array $config
     * @return self
     * */
    public static function __init__($config)
    {
        if(sizeof($config)):
            foreach ($config as $index => $item) :
                if (isset(self::${$index}) && !is_null($item))
                    self::${$index} = $item;
            endforeach;
        endif;

        return new self;
    }

    /**
     * setMaxSize method
     * @param int $maxSize
     * @return void
     * */
    public static function setMaxSize($maxSize)
    {
        self::$maxSize = $maxSize;
    }

    /**
     * setMaxHeight method
     * @param int $maxHeight
     * @return void
     * */
    public static function setMaxHeight($maxHeight)
    {
        self::$maxHeight = $maxHeight;
    }

    /**
     * setAllowedTypes method
     * @param array $allowedTypes
     * @return void
     * */
    public static function setAllowedTypes($allowedTypes)
    {
        self::$allowedTypes = $allowedTypes;
    }

    /**
     * setMaxWidth method
     * @param int $maxWidth
     * @return void
     * */
    public static function setMaxWidth($maxWidth)
    {
        self::$maxWidth = $maxWidth;
    }

    /**
     * setRandomName method
     * @param bool $randomName
     * @return void
     * */
    public static function setRandomName($randomName)
    {
        self::$randomName = $randomName;
    }

    /**
     * setUploadPath method
     * @param string $uploadPath
     * @return void
     * */
    public static function setUploadPath($uploadPath)
    {
        self::$uploadPath = $uploadPath;
    }

    /**
     * getAllowedTypes method
     * @return array
     * */
    public static function getAllowedTypes()
    {
        return self::$allowedTypes;
    }

    /**
     * getMaxHeight method
     * @return int
     * */
    public static function getMaxHeight()
    {
        return self::$maxHeight;
    }

    /**
     * getMaxSize method
     * @return int
     * */
    public static function getMaxSize()
    {
        return self::$maxSize;
    }

    /**
     * getMaxWidth method
     * @return int
     * */
    public static function getMaxWidth()
    {
        return self::$maxWidth;
    }

    /**
     * getUploadPath method
     * @return string
     * */
    public static function getUploadPath()
    {
        return self::$uploadPath;
    }

    /**
     * isRandomName method
     * @return bool
     * */
    public static function isRandomName()
    {
        return self::$randomName;
    }

    /**
     *
     * */
    public static function get()
    {
        return new self;
    }
}