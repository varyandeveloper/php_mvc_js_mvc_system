<?php
/**
 * Created by PhpStorm.
 * User: 37498_000
 * Date: 10.06.2016
 * Time: 15:03
 */

namespace engine\abstracts;

use engine\config\AppConfig;
use engine\Engine;
use engine\interfaces\URLSchema;

/**
 * Class URLParent
 * @package engine\abstracts
 */
abstract class URLParent implements URLSchema
{
    /**
     * @var string $__url
     */
    protected $__url;

    /**
     * URLParent constructor.
     */
    public function __construct()
    {
        $this->__url = rtrim(ltrim(str_replace("index.php","",strstr($_SERVER["PHP_SELF"], 'index.php')),"/"),"/");
    }

    /**
     * @return string
     */
    public function get() : string
    {
        return $this->__url;
    }

    /**
     * segment method
     * @param integer $position
     * @return string|null
     * */
    public function segment(int $position)
    {
        $parts = explode("/", $this->__url);
        return isset($parts[$position]) ? $parts[$position] : null;
    }

    /**
     * @param string $url
     * @param bool $refresh
     * @return void
     */
    public function to(string $url = '', bool $refresh = false)
    {
        ($refresh === TRUE)
            ? header('Refresh:0;url=' . $url)
            : header('Location: ' . $url, TRUE);
    }

    /**
     * @return string
     */
    public function getCleanURL()
    {
        $parts = explode("/",$this->__url);
        if(($key = array_search(Engine::release(), $parts)) !== false) {
            unset($parts[$key]);
        }

        return join("/",$parts);
    }

    /**
     * @param string $url
     * @return string
     */
    public function base($url = '')
    {
        $baseUrl = AppConfig::getBaseUrl();

        if (trim($baseUrl) != '') {
            $currentUrl = $baseUrl . $url;
        } else {
            $currentUrl = (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS'])) ? 'https://' : 'http://';
            $currentUrl .= (strpos($url, 'http') !== FALSE) ? $url : $_SERVER['HTTP_HOST'] . '/' . $url;
        }
        return $currentUrl;
    }

    /**
     * @param string $url
     * @return string
     */
    public function asset($url = '')
    {
        $returnUrl = $this->base($url);
        if(Engine::isUsesSubDomain())
        {
            $parts = explode("/",$returnUrl);
            $mainDomain = explode(".",@$parts[2]);
            unset($mainDomain[0]);
            $parts[2] = join(".",$mainDomain);
            $returnUrl = join("/",$parts);
        }
        return $returnUrl;
    }
}