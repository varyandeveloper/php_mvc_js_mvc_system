<?php
/**
 * Created by PhpStorm.
 * User: Var Yan
 * Date: 8/25/2016
 * Time: 11:35 PM
 */

namespace engine\config;
use engine\Engine;
use engine\objects\Factory;
use engine\objects\URL;

/**
 * Class CacheConfig
 * @package engine\config
 */
class CacheConfig
{
    /**
     * @var bool $isOn
     */
    protected static $on = false;
    /**
     * @var string $directory
     */
    protected static $directory;
    /**
     * @var int $time
     */
    protected static $time = 600;
    /**
     * @var string $extension
     */
    protected static $extension = "varyan";
    /**
     * @var array $ignoreRotes
     */
    protected static $ignoreRotes = [];
    /**
     * @var string $cacheFileName
     */
    protected static $cacheFileName;

    /**
     * CacheConfig __init__.
     * @param array $config
     */
    public static function __init__(array $config)
    {
        foreach ($config as $index => $item) {
            if (property_exists(new self, $index))
                self::${$index} = $item;
        }
    }

    /**
     * @return boolean
     */
    public static function isOn(): bool
    {
        return self::$on;
    }

    /**
     * @return void
     */
    public static function start()
    {
        if(is_null(self::$directory))
            self::$directory = RELEASE.Engine::release().DS."cache".DS;

        if(!is_writable(self::$directory))
            exit("Cache directory is not writable");

        $page = Factory::create(URL::class)->getCleanURL();
        self::$cacheFileName = self::$directory . md5($page) . '.' . self::$extension;
        $ignore_page = false;
        for ($i = 0; $i < count(self::$ignoreRotes); $i++) {
            $ignore_page = (strpos($page, self::$ignoreRotes[$i]) !== false) ? true : $ignore_page;
        }
        $createdAt = ((@file_exists(self::$cacheFileName)) && ($ignore_page === false))
            ? @filemtime(self::$cacheFileName)
            : 0;

        @clearstatcache();
        if (time() - self::$time < $createdAt) {
            @readfile(self::$cacheFileName);
            exit();
        }
    }

    /**
     * @return void
     */
    public static function end()
    {
        $fp = @fopen(self::$cacheFileName, 'w');

        // save the contents of output buffer to the file
        @fwrite($fp, ob_get_contents());
        @fclose($fp);
    }
}