<?php
/**
 * Created by PhpStorm.
 * User: Var Yan
 * Date: 4/29/2016
 * Time: 8:19 PM
 */

namespace engine\config;
use engine\Engine;
use engine\objects\Router;

/**
 * Class AppConfig
 * @package engine\config
 */
class AppConfig
{
    /**
     * @var array $_links
     */
    protected static $_links = [];
    /**
     * @var array $allowedLanguages
     */
    private static $allowedLanguages = ["am","ru","en"];
    /**
     * @var string $currentLanguage
     */
    private static $currentLanguage = "am";
    /**
     * @var string
     */
    private static $translationFilename = "translations";
    /**
     * @var string $baseUrl
     */
    protected static $baseUrl;

    /**
     * @param $baseUrl
     */
    public static function setBaseUrl($baseUrl)
    {
        self::$baseUrl = $baseUrl;
    }

    /**
     * @return bool
     */
    public static function withAddedPath()
    {
        return (boolean)sizeof(explode("/",self::$baseUrl)) > 4;
    }

    /**
     * @param string $key
     * @param string $anchor
     */
    public static function addLink($key,$anchor)
    {
        self::$_links[$key] = $anchor;
    }

    /**
     * @param string $key
     * @param array $args
     * @return string|null
     */
    public static function getLink($key, array $args)
    {
        $link = @self::$_links[$key];
        if(!is_null($link))
            for ($i = 0; $i < sizeof($args); $i++)
                $link = str_replace('{var'.$i.'}',$args[$i],$link);
        return $link;
    }

    /**
     * @return array
     */
    public static function getLinks()
    {
        return self::$_links;
    }

    /**
     * @return string
     */
    public static function getBaseUrl()
    {
        return self::$baseUrl;
    }

    /**
     * @param string $filename
     */
    public static function setTranslationFilename(string $filename)
    {
        self::$translationFilename = $filename;
    }

    /**
     * @return string
     */
    public static function getTranslationFilename()
    {
        return self::$translationFilename;
    }

    /**
     * @return array
     */
    public static function getAllowedLanguages()
    {
        return self::$allowedLanguages;
    }

    /**
     * @return string
     */
    public static function getCurrentLanguage()
    {
        return self::$currentLanguage;
    }

    /**
     * @param $currentLanguage
     */
    public static function setCurrentLanguage($currentLanguage = null)
    {
        if(!is_null($currentLanguage))
            self::$currentLanguage = $currentLanguage;
    }
}