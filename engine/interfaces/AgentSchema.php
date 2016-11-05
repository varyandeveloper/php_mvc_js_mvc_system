<?php
/**
 * Created by PhpStorm.
 * User: Var Yan
 * Date: 8/21/2016
 * Time: 10:52 AM
 */

namespace engine\interfaces;


/**
 * Interface AgentSchema
 * @package Engine\Schema
 */
interface AgentSchema
{
    /**
     * Mobile detection type.
     *
     */
    const DETECTION_TYPE_MOBILE = 'mobile';

    /**
     * Extended detection type.
     *
     */
    const DETECTION_TYPE_EXTENDED = 'extended';

    /**
     * A frequently used regular expression to extract version #s.
     *
     */
    const VER = '([\w._\+]+)';

    /**
     * Top-level device.
     */
    const MOBILE_GRADE_A = 'A';

    /**
     * Mid-level device.
     */
    const MOBILE_GRADE_B = 'B';

    /**
     * Low-level device.
     */
    const MOBILE_GRADE_C = 'C';

    /**
     * Stores the version number of the current release.
     */
    const VERSION = '2.8.20';

    /**
     * A type for the version() method indicating a string return value.
     */
    const VERSION_TYPE_STRING = 'text';

    /**
     * A type for the version() method indicating a float return value.
     */
    const VERSION_TYPE_FLOAT = 'float';

    /**
     * @return mixed
     */
    public static function getScriptVersion();

    /**
     * @return mixed
     */
    public function getRules();

    /**
     * @param string $regex
     * @param string $userAgent
     * @return mixed
     */
    public function match($regex, $userAgent = null);

    /**
     * @return mixed
     */
    public function mobileGrade();

    /**
     * @param string $propertyName
     * @param string $type
     * @return mixed
     */
    public function version($propertyName, $type = self::VERSION_TYPE_STRING);

    /**
     * @param string $ver
     * @return mixed
     */
    public function prepareVersionNo($ver);

    /**
     * @param string $userAgent
     * @param $httpHeaders
     * @return mixed
     */
    public function isMobile($userAgent = null, $httpHeaders = null);

    /**
     * @param null $userAgent
     * @param null $httpHeaders
     * @return mixed
     */
    public function isTablet($userAgent = null, $httpHeaders = null);

    /**
     * @param $key
     * @param null $userAgent
     * @param null $httpHeaders
     * @return mixed
     */
    public function is($key, $userAgent = null, $httpHeaders = null);

    /**
     * @return mixed
     */
    public function checkHttpHeadersForMobile();

    /**
     * @return mixed
     */
    public function getHttpHeaders();

    /**
     * @param null $httpHeaders
     * @return mixed
     */
    public function setHttpHeaders($httpHeaders = null);

    /**
     * @param $header
     * @return mixed
     */
    public function getHttpHeader($header);

    /**
     * @return mixed
     */
    public function getUserAgent();

    /**
     * @return mixed
     */
    public function getMatchingRegex();

    /**
     * @return mixed
     */
    public function getMatchesArray();

    /**
     * @param null $type
     * @return mixed
     */
    public function setDetectionType($type = null);

    /**
     * @param null $cfHeaders
     * @return mixed
     */
    public function setCfHeaders($cfHeaders = null);

    /**
     * @param null $userAgent
     * @return mixed
     */
    public function setUserAgent($userAgent = null);
}