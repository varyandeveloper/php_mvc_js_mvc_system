<?php
/**
 * Created by PhpStorm.
 * User: VarYan
 * Date: 14.01.2016
 * Time: 21:34
 */

namespace engine\config;

/**
 * Class ValidatorConfig
 * @package engine\config
 */
class ValidatorConfig
{
    /**
     * @var array $types
     * */
    private static $types;
    /**
     * init method
     * @param array $types
     * @return void
     * */
    public static function __init__($types)
    {
        $default = [
            'email'     =>  function ($email){
                return (!filter_var($email, FILTER_VALIDATE_EMAIL) === FALSE) ? true : false;
            },
            'ipAddress' =>  function ($ip){
                return (!filter_var($ip, FILTER_VALIDATE_IP) === FALSE) ? true : false;
            },
            'linkURL'   =>  function ($url){
                return (!filter_var($url, FILTER_VALIDATE_URL) === FALSE) ? true : false;
            },
            'createdAt' =>function($date){
                return (date('Y-m-d H:i:s', strtotime($date)) == $date) ? true : false;
            },
            'updatedAt' =>function($date){
                return (date('Y-m-d H:i:s', strtotime($date)) == $date) ? true : false;
            },
            'required' =>function($data){
                return (!empty($data)) ? true : false;
            },
            'trim'      =>function($data){
                return (trim($data) != '') ? true : false;
            },
            'maxLength' =>function($data,$chars){
                return (strlen($data) <= intval($chars)) ? true : false;
            },
            'minLength' =>function($data,$chars){
                return (strlen($data) >= intval($chars)) ? true : false;
            },
            'between'   =>function($data,$start,$end){
                return (strlen($data) >= intval($start) && strlen($data) <= intval($end)) ? true : false;
            },
            'matchWith' => function($fist,$second){
                return ($fist === $second) ? true : false;
            },
            'majorCreditCard'=>function($data){
                return preg_match('/^(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|6011[0-9]{12}|622((12[6-9]|1[3-9][0-9])|([2-8][0-9][0-9])|(9(([0-1][0-9])|(2[0-5]))))[0-9]{10}|64[4-9][0-9]{13}|65[0-9]{14}|3(?:0[0-5]|[68][0-9])[0-9]{11}|3[47][0-9]{13})*$/',$data) ? true : false;
            },
            'alphaNumeric'  => function($data){
                return preg_match('/^[a-zA-Z0-9]*$/',$data) ? true : false;
            },
            'alphaNumericSpaces'=>function($data){
                return preg_match('/^[a-zA-Z0-9 ]*$/',$data) ? true : false;
            },
            'alphabetic'=>function($data){
                return preg_match('/^[a-zA-Z]*$/',$data) ? true : false;
            },
            'americanExpress'=>function($data){
                return preg_match('/^(3[47][0-9]{13})*$/',$data) ? true : false;
            },
            'australianPostal'=>function($data){
                return preg_match('/^((0[289][0-9]{2})|([1345689][0-9]{3})|(2[0-8][0-9]{2})|(290[0-9])|(291[0-4])|(7[0-4][0-9]{2})|(7[8-9][0-9]{2}))*$/',$data) ? true : false;
            },
            'canadianPostal'=>function($data){
                return preg_match('/^([ABCEGHJKLMNPRSTVXY][0-9][A-Z] [0-9][A-Z][0-9])*$/',$data) ? true :false;
            },
            'canadianProvince'=>function($data){
                return preg_match('/^(?:AB|BC|MB|N[BLTSU]|ON|PE|QC|SK|YT)*$/',$data) ? true : false;
            },
            'dateMmDdYyyy'=>function($date){
                return preg_match('/^((0?[1-9]|1[012])[- /.](0?[1-9]|[12][0-9]|3[01])[- /.](19|20)?[0-9]{2})*$/',$date) ? true : false;
            },
            'dateDdMmYyyy'=>function($data){
                return preg_match('/^(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-[0-9]{4}$/',$data) ? true : false;
            },
            'dateYyyyMmDd'=>function($data){
                return preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/',$data) ? true : false;
            },
            'numeric'=>function($data){
                return preg_match('/^[0-9]*$/',$data) ? true : false;
            },
            'dinnersClubCreditCard'=>function($data){
                return preg_match('/^(3(?:0[0-5]|[68][0-9])[0-9]{11})*$/',$data) ? true : false;
            },
            'validEmail'=>function($data){
                return preg_match('/^([a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4})*$/',$data) ? true : false;
            },
            'validIP'=>function($data){
                return preg_match('/^((?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?))*$/',$data) ? true : false;
            },
            'lowerAlpha'=>function($data){
                return preg_match('/^([a-z])*$/',$data) ? true : false;
            },
            'masterCard'=>function($data){
                return preg_match('/^(5[1-5][0-9]{14})*$/',$data) ? true : false;
            },
            'strongPassword'=>function($data){
                return preg_match('/^(?=^.{3,}$)((?=.*[A-Za-z0-9])(?=.*[A-Z])(?=.*[a-z]))^.*$/',$data) ? true : false;
            },
            'northAmericaNumber'=>function($data){
                return preg_match('/^((([0-9]{1})*[- .(]*([0-9]{3})[- .)]*[0-9]{3}[- .]*[0-9]{4})+)*$/',$data) ? true : false;
            },
            'USSocialSecurityNumber'=>function($data){
                return preg_match('/^([0-9]{3}[-]*[0-9]{2}[-]*[0-9]{4})*$/',$data) ? true : false;
            },
            'UKPostalCode'=>function($data){
                return preg_match('/^([A-Z]{1,2}[0-9][A-Z0-9]? [0-9][ABD-HJLNP-UW-Z]{2})*$/',$data) ? true : false;
            },
            'upperAlpha'=>function($data){
                return preg_match('/^([A-Z])*$/',$data) ? true : false;
            },
            'validURL'=>function($data){
                return preg_match('/^(((http|https|ftp):\/\/)?([[a-zA-Z0-9]\-\.])+(\.)([[a-zA-Z0-9]]){2,4}([[a-zA-Z0-9]\/+=%&_\.~?\-]*))*$/',$data) ? true : false;
            },
            'USZipCode'=>function($data){
                return preg_match('/^([0-9]{5}(?:-[0-9]{4})?)*$/',$data) ? true : false;
            },
            'visaCreditCard'=>function($data){
                return preg_match('/^(4[0-9]{12}(?:[0-9]{3})?)*$/',$data) ? true : false;
            }
        ];
        self::$types = array_merge($default,$types);
    }
    /**
     * getTypes method
     * @return array
     * */
    public static function getTypes()
    {
        return self::$types;
    }
}