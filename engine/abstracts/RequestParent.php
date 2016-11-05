<?php
/**
 * Created by PhpStorm.
 * User: 37498_000
 * Date: 10.06.2016
 * Time: 12:54
 */

namespace engine\abstracts;

use engine\interfaces\RequestSchema;

/**
 * Class RequestParent
 * @package engine\abstracts
 */
abstract class RequestParent implements RequestSchema
{
    /**
     * @return null|string
     */
    public function method() : string
    {
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
            ? (isset($_SERVER['REQUEST_METHOD']) && !empty($_SERVER['REQUEST_METHOD']) ? strtolower($_SERVER['REQUEST_METHOD'])."Ajax" : null)
            : (isset($_SERVER['REQUEST_METHOD']) && !empty($_SERVER['REQUEST_METHOD']) ? strtolower($_SERVER['REQUEST_METHOD']) : null);
    }

    /**
     * @return array
     */
    public function all() : array
    {
        $data = array();
        foreach($_REQUEST as $item => $value){
            $data[$item] = $this->sanitize($value);
        }
        return $data;
    }

    /**
     * @param string $key
     * @return string|array
     */
    public function post(string $key = null)
    {
        if(!is_null($key))
            return isset($_POST[$key]) ? self::sanitize($_POST[$key]) : null;
        else
        {
            $data = array();
            foreach($_POST as $item => $value)
            {
                $data[$item] = self::sanitize($value);
            }
            return $data;
        }
    }

    /**
     * @param string $key
     * @return string|array
     */
    public function get(string $key = null)
    {
        if(!is_null($key))
            return isset($_GET[$key]) ? self::sanitize($_GET[$key]) : null;
        else
        {
            $data = array();
            foreach($_GET as $item => $value){
                $data[$item] = self::sanitize($value);
            }
            return $data;
        }
    }

    /**
     * @param string $key
     * @return array
     */
    public function file(string $key = null) : array
    {
        if(!is_null($key))
        {
            return isset($_FILES[$key]) ? self::sanitize($_FILES[$key]) : null;
        }
        else
            return $_FILES;
    }

    /**
     * cleanInput method
     * @param string $input
     * @return string
     * */
    private function cleanInput($input)
    {

        $search = array(
            '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
            '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
            '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
            '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
        );

        $output = preg_replace($search, '', $input);
        return $output;
    }
    /**
     * sanitize method
     * @param mixed $input
     * @return string
     * */
    private function sanitize($input)
    {
        $input  = self::cleanInput($input);
        return $input;
    }

}