<?php
/**
 * Created by PhpStorm.
 * User: Var Yan
 * Date: 6/17/2016
 * Time: 10:28 PM
 */

namespace engine\objects;

use engine\abstracts\AuthParent;
use engine\config\AuthConfig;
use engine\traits\Hash;

/**
 * Class Auth
 * @package engine\objects
 */
class Auth extends AuthParent
{
    use Hash;
    /**
     * @param $username
     * @param $password
     */
    public static function make($username,$password)
    {
        if(is_null(self::$__user))
        {
            $db = Database::getInstance();
            $credentials = AuthConfig::getCredentials();
            $size = sizeof($credentials['username']);
            if($size)
            {
                $db->select()->from(AuthConfig::getTableName())->where(@$credentials["username"][0],$username);
                if($size > 1)
                {
                    for($i = 1; $i < $size; $i++)
                    {
                        $db->where($credentials['username'][$i],$username,"or");
                    }
                }
                self::$__user = $db->query()->fetch(\PDO::FETCH_OBJ);

                if(!self::$__user)
                    return;

                if(self::verify($password,self::$__user->{$credentials['password']}))
                    Session::set("authUserId",self::$__user->id);

            }
        }else
            return;
    }

    /**
     * @return void
     */
    public static function forgot()
    {
        Session::forget("authUserId");
    }
}