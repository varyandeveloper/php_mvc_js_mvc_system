<?php
/**
 * Created by PhpStorm.
 * User: Var Yan
 * Date: 6/16/2016
 * Time: 7:59 AM
 */

namespace engine\abstracts;

use engine\interfaces\AuthSchema;
use engine\objects\Database;
use engine\objects\Session;

/**
 * Class AuthParent
 * @package engine\abstracts
 */
abstract class AuthParent implements AuthSchema
{
    /**
     * @var object $__user
     */
    protected static $__user;

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        self::__checkUser();
        return @self::${"__".$name};
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        self::__checkUser();
        return @self::${"__".$name};
    }

    /**
     * @return bool
     */
    public static function status() : bool
    {
        return (bool)Session::get("authUserId");
    }
    
    /**
     * @return void
     */
    private static function __checkUser()
    {
        if(is_null(self::$__user))
        {
            $db = Database::getInstance();
            if(Session::get("authUserId"))
                self::$__user = $db
                    ->where("id",Session::get("authUserId"))
                    ->select()
                    ->from("users")
                    ->query()->fetch(\PDO::FETCH_OBJ);
        }
    }
}