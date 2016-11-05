<?php
/**
 * Created by PhpStorm.
 * User: Var Yan
 * Date: 5/28/2016
 * Time: 9:30 PM
 */

namespace engine\traits;

/**
 * Class Caller
 * @package Engine\Additional
 */
trait Caller
{
    /**
     * @param $name
     * @param null $arguments
     * @return object
     */
    public static function __callStatic($name, $arguments = null)
    {
        return self::__callMaster($name,$arguments);
    }

    /**
     * @param $name
     * @param $arguments
     * @return object
     */
    public function __call($name, $arguments)
    {
        return self::__callMaster($name,$arguments);
    }

    /**
     * @param $name
     * @param $arguments
     * @return object
     */
    private static function __callMaster($name, $arguments)
    {
        $class = get_called_class();
        if(method_exists($class,$name))
            return call_user_func_array([new $class,$name],$arguments);
    }
}