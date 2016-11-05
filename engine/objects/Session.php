<?php
/**
 * Created by PhpStorm.
 * User: design
 * Date: 12/22/2015
 * Time: 3:28 PM
 */

namespace engine\objects;

use engine\config\SessionConfig;

/**
 * Class Session
 * @package engine\objects
 */
class Session
{
    /**
     * init
     * @var array $args (default value null)
     **/
    public static function __init__()
    {
        if (strlen(SessionConfig::getEncryptionKey()) < 32)
            exit('To use session you must set encryption key at less 32 symbols');

        if (!isset($_SESSION['session_id'])) {
            self::_register();
        }
        if (!self::isRegistered() || self::isExpired()) self::end();
    }

    /**
     * isRegistered method
     * @return bool
     */
    public static function isRegistered()
    {
        if (!empty($_SESSION['session_id'])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param string|array $key_or_keys
     * @param int|string|null $value
     * @return void
     */
    public static function set($key_or_keys, $value = null)
    {
        if ($value !== false) {
            $_SESSION[$key_or_keys] = $value;
        } else {
            if (!is_array($key_or_keys))
                exit('sessionError');
            foreach ($key_or_keys as $key => $value) {
                $_SESSION[$key] = $value;
            }
        }
    }

    /**
     * get method
     * @param string $key
     * @return string|null
     */
    public static function get($key)
    {
        return (isset($_SESSION[$key])) ? $_SESSION[$key] : null;
    }

    /**
     * getSession method
     * @return array
     */
    public static function getSession()
    {
        return $_SESSION;
    }

    /**
     * getSessionId method
     * @return string
     */
    public static function getSessionId()
    {
        return $_SESSION['session_id'];
    }

    /**
     * isExpired method
     * @return boolean
     */
    public static function isExpired()
    {
        return ($_SESSION['session_start'] < self::__timeNow()) ? true : false;
    }

    /**
     * renew method
     */
    public static function renew()
    {
        $_SESSION['session_start'] = self::__newTime();
    }

    /**
     * forget method
     * Will unset session by key
     * @param string $key
     * */
    public static function forget($key)
    {
        unset($_SESSION[$key]);
    }

    /**
     * end method
     * Will destroy session
     * @return bool
     */
    public static function end()
    {
        $_SESSION = array();
        return @session_destroy();
    }

    /**
     * _register method
     */
    protected static function _register()
    {
        $time = (SessionConfig::getExpireTime() == 0) ? $time = (60 * 60 * 24 * 100) : SessionConfig::getExpireTime();

        session_set_cookie_params(SessionConfig::getExpireTime(), RELEASE . 'storage' . DS . 'session');
        @session_start();
        $_SESSION['session_id'] = (SessionConfig::getEncryptionKey() != null) ? SessionConfig::getEncryptionKey() : session_id();
        $_SESSION['session_time'] = intval($time);
        $_SESSION['session_start'] = self::__newTime();
    }

    /**
     * newTime method
     * @return integer
     */
    private static function __newTime()
    {
        $currentHour = date('H');
        $currentMin = date('i');
        $currentSec = date('s');
        $currentMon = date('m');
        $currentDay = date('d');
        $currentYear = date('y');
        return mktime($currentHour, ($currentMin + $_SESSION['session_time']), $currentSec, $currentMon, $currentDay, $currentYear);
    }

    /**
     * timeNow method
     * @return integer
     */
    private static function __timeNow()
    {
        $currentHour = date('H');
        $currentMin = date('i');
        $currentSec = date('s');
        $currentMon = date('m');
        $currentDay = date('d');
        $currentYear = date('y');
        return mktime($currentHour, $currentMin, $currentSec, $currentMon, $currentDay, $currentYear);
    }
}