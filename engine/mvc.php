<?php
/**
 * Created by PhpStorm.
 * User: Var Yan
 * Date: 31.05.2015
 * Time: 17:42
 */

if(!function_exists("VSErrorHandler"))
{
    /**
     * @param int $errorCode
     * @param string $errorMessage
     * @param string $errorFile
     * @param int $errorLine
     * @return bool|void
     */
/*    function VSErrorHandler(int $errorCode, string $errorMessage, string $errorFile, int $errorLine)
    {
        if (!(error_reporting() & $errorCode))
            return;

        switch ($errorCode) {
            case E_USER_ERROR:

                break;

            case E_USER_WARNING:

                break;

            case E_USER_NOTICE:

                break;

            case E_STRICT:
                exit('aaa');
                break;

            default:

                break;
        }
        return true;
    }*/

    //$errorHandler = set_error_handler("VSErrorHandler");
}

if(!function_exists("anchor"))
{
    /**
     * @param string $href
     * @param string $name
     * @param string $attributes
     * @return string
     */
    function anchor($href,$name,$attributes = "")
    {
        $actualLink = SERVER.$_SERVER["REQUEST_URI"];
        if($href == $actualLink)
            $href = "javascript:void(0)";

        return  "<a href='{$href}' {$attributes}>{$name}</a>";
    }
}

if(!function_exists("model"))
{
    /**
     * @param string $modelName
     * @param array ...$args
     * @return \engine\interfaces\ModelSchema
     */
    function model($modelName,...$args)
    {
        static $models;
        if(!is_array($models) || !array_key_exists($modelName,$models))
            $models[$modelName] = \engine\objects\Factory::create(RELEASE.\engine\Engine::release().DS."model".DS.$modelName,$args);
        return $models[$modelName];
    }
}

if(!function_exists('route'))
{
    /**
     * @return \engine\objects\Router
     */
    function route()
    {
        return \engine\Engine::Router();
    }
}

if(!function_exists("form"))
{
    /**
     * @return \engine\objects\Form
     */
    function form()
    {
        return \engine\Engine::Form();
    }
}

if(!function_exists("release"))
{
    /**
     * @return mixed
     */
    function release()
    {
        return \engine\Engine::release();
    }
}

if(!function_exists("url"))
{
    /**
     * @return \engine\objects\URL
     */
    function url()
    {
        return \engine\Engine::URL();
    }
}

if(!function_exists("getLink"))
{
    /**
     * @param string $key
     * @param array $replace
     * @return string
     */
    function getLink($key,array $replace)
    {
        return \engine\config\AppConfig::getLink($key,$replace);
    }
}

if(!function_exists("redirect"))
{
    /**
     * @param string $newUrl
     * @param bool $refresh
     */
    function redirect($newUrl = '',$refresh = false)
    {
        url()->to($newUrl,$refresh);
    }
}

if(!function_exists("segment"))
{
    /**
     * @param $position
     * @return string
     */
    function segment($position)
    {
        return url()->segment($position);
    }
}

if(!function_exists("assetURL"))
{
    /**
     * @param string $append
     * @return string
     */
    function assetURL($append = "")
    {
        return url()->asset($append);
    }
}

if(!function_exists("baseURL"))
{
    /**
     * @param string $append
     * @return string
     */
    function baseURL($append = "")
    {
        return url()->base($append);
    }
}

if(!function_exists("releaseURL"))
{
    /**
     * @param string $append
     * @return string
     */
    function releaseURL($append = "")
    {
        return (segment(0) == \engine\Engine::release())
            ? baseURL(\engine\Engine::release()."/{$append}")
            : baseURL($append);
    }
}

if(!function_exists("routeAlias"))
{
    /**
     * @param string|null $key
     * @return string
     */
    function routeAlias(string $key) : string
    {
        $routeKey = ltrim(route()->alias($key),"/");
        return releaseURL($routeKey);
    }
}

if(!function_exists('lang'))
{
    /**
     * @param string $filename
     * @param string|null $langFolder
     * @return object
     */
    function lang(string $filename, string $langFolder = "am")
    {
        static $loadedLang;
        if(!$loadedLang)
            $loadedLang = (object)\engine\Engine::loadLang($filename,$langFolder);
        return $loadedLang;
    }
}

if(!function_exists("langLine"))
{
    /**
     * @param $key
     * @return string
     */
    function langLine($key)
    {
        $fileName = \engine\config\AppConfig::getTranslationFilename();
        $langArray = lang($fileName,\engine\config\AppConfig::getCurrentLanguage());
        return (string)@$langArray->{$key};
    }
}

if(!function_exists("prevURL"))
{
    /**
     * @return string
     */
    function prevURL()
    {
        return (string)@$_SERVER["HTTP_REFERER"];
    }
}

if(!function_exists("view"))
{
    /**
     * view function
     * @return \engine\interfaces\ViewSchema
     */
    function view()
    {
        return \engine\Engine::View();
    }
}

if(!function_exists("render"))
{
    /**
     * @param string $fileName
     * @param null $args
     * @param bool $asOutput
     * @return \engine\interfaces\ViewSchema
     */
    function render($fileName,$args = null, $asOutput = false)
    {
        return view()->render($fileName,$args,$asOutput);
    }
}

if(!function_exists('addLink'))
{
    /**
     * @param string $key
     * @param string $anchor
     * @return void
     */
    function addLink(string $key, string $anchor)
    {
        \engine\config\AppConfig::addLink($key,$anchor);
    }
}

if(!function_exists("puttSection"))
{
    /**
     * @param string $partialName
     * @param array|stdClass $args
     */
    function puttSection(string $partialName,$args = null)
    {
        view()->extends($partialName,$args);
    }
}

if(!function_exists("child"))
{
    /**
     * @param string $sectionName
     * @return void
     */
    function child(string $sectionName){
        view()->yield($sectionName);
    }
}

if(!function_exists("parent"))
{
    /**
     * @param string $viewFile
     * @return void
     */
    function parent(string $viewFile)
    {
        view()->extends($viewFile);
    }
}

if(!function_exists("section"))
{
    /**
     * @param string $sectionName
     * @return void
     */
    function section(string $sectionName)
    {
        view()->section($sectionName);
    }
}

if(!function_exists("endSection"))
{
    /**
     * @return void
     */
    function endSection()
    {
        view()->endSection();
    }
}

if(!function_exists("withVars"))
{
    /**
     * @param array $args
     * @return \engine\interfaces\ViewSchema
     */
    function withVars($args)
    {
        return view()->withVars($args);
    }
}

if(!function_exists("dp"))
{
    /**
     * @param $data
     * @param bool|string $message
     */
    function dp($data,$message = false)
    {
        \engine\Engine::dp($data,$message);
    }
}

if(!function_exists("dd"))
{
    /**
     * @param $data
     * @param bool|string $message
     */
    function dd($data,$message = false)
    {
        \engine\Engine::dd($data,$message);
    }
}

if(!function_exists("de"))
{
    /**
     * @param $data
     * @param bool|string $message
     */
    function de($data,$message = false)
    {
        \engine\Engine::de($data,$message);
    }
}