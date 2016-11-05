<?php
/**
 * Created by PhpStorm.
 * User: VarYan
 * Date: 17.01.2016
 * Time: 0:36
 */

namespace engine\traits;

/**
 * Class HashPassword
 * @package engine\traits
 */
trait Hash
{
    /**
     * generate method
     * @param string $password
     * @return string
     * */
    protected static function generate($password)
    {
        if (defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH) {
            $salt = '$2y$11$' . substr(md5(uniqid(rand(), true)), 0, 22);
            return crypt($password, $salt);
        }
        return "CRYPT_BLOWFISH missing check Your php version";
    }

    /**
     * verify method
     * @param string $password
     * @param string $hashedPassword
     * @return bool
     * */
    protected static function verify($password, $hashedPassword)
    {
        return crypt($password, $hashedPassword) == $hashedPassword;
    }
}