<?php
/**
 * Created by PhpStorm.
 * User: design
 * Date: 12/22/2015
 * Time: 1:28 PM
 */

namespace engine\traits;

/**
 * Trait Generate
 * @package Engine\Additional
 */
trait Generate
{
    /**
     * @var string $characters
     */
    protected static $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * token method
     * @param integer $length
     * @param boolean $hash
     * @return string
     * */
    public static function token($length = 24, $hash = false)
    {
        $charactersLength = strlen(self::$characters);
        $salt = "__VS__" . uniqid();
        $randomString = '';

        for ($i = 0; $i < ($length - strlen($salt)); $i++) :
            $randomString .= self::$characters[rand(0, $charactersLength - 1)];
        endfor;

        return ($hash) ? password_hash($randomString . password_hash($salt, PASSWORD_BCRYPT), PASSWORD_BCRYPT) : $randomString . $salt;
    }

    /**
     * imgFromBase64 method
     * Will make image file from base64 string
     * @return string
     * */
    public static function imgFromBase64($data, $destination)
    {
        $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data));
        $newImageName = "MP_" . uniqid() . ".png";
        file_put_contents($destination . $newImageName, $data);
        return $newImageName;
    }
}