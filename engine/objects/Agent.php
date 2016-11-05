<?php

namespace engine\objects;

use engine\config\AgentConfig;
use engine\interfaces\AgentSchema;

/**
 * Class Agent
 * @package Engine\Library
 */
class Agent implements AgentSchema
{
    /**
     * A cache for resolved matches
     * @var array
     */
    protected $cache = array();

    /**
     * The User-Agent HTTP header is stored in here.
     * @var string
     */
    protected $userAgent = null;

    /**
     * HTTP headers in the PHP-flavor. So HTTP_USER_AGENT and SERVER_SOFTWARE.
     * @var array
     */
    protected $httpHeaders = array();

    /**
     * CloudFront headers. E.g. CloudFront-Is-Desktop-Viewer, CloudFront-Is-Mobile-Viewer & CloudFront-Is-Tablet-Viewer.
     * @var array
     */
    protected $cloudFrontHeaders = array();

    /**
     * The matching Regex.
     * This is good for debug.
     * @var string
     */
    protected $matchingRegex = null;

    /**
     * The matches extracted from the regex expression.
     * This is good for debug.
     * @var string
     */
    protected $matchesArray = null;

    /**
     * The detection type, using self::DETECTION_TYPE_MOBILE or self::DETECTION_TYPE_EXTENDED.
     *
     *
     * @var string
     */
    protected $detectionType = self::DETECTION_TYPE_MOBILE;

    /**
     * Construct an instance of this class.
     *
     * @param array $headers Specify the headers as injection. Should be PHP _SERVER flavored.
     *                          If left empty, will use the global _SERVER['HTTP_*'] vars instead.
     * @param string $userAgent Inject the User-Agent header. If null, will use HTTP_USER_AGENT
     *                          from the $headers array instead.
     */
    public function __construct(array $headers = null, $userAgent = null)
    {
        $this->setHttpHeaders($headers);
        $this->setUserAgent($userAgent);
    }

    /**
     * Get the current script version.
     * This is useful for the demo.php file,
     * so people can check on what version they are testing
     * for mobile devices.
     *
     * @return string The version number in semantic version format.
     */
    public static function getScriptVersion()
    {
        return self::VERSION;
    }

    /**
     * Set the HTTP Headers. Must be PHP-flavored. This method will reset existing headers.
     *
     * @param array $httpHeaders The headers to set. If null, then using PHP's _SERVER to extract
     *                           the headers. The default null is left for backwards compatibility.
     */
    public function setHttpHeaders($httpHeaders = null)
    {
        // use global _SERVER if $httpHeaders aren't defined
        if (!is_array($httpHeaders) || !count($httpHeaders)) {
            $httpHeaders = $_SERVER;
        }

        // clear existing headers
        $this->httpHeaders = array();

        // Only save HTTP headers. In PHP land, that means only _SERVER vars that
        // start with HTTP_.
        foreach ($httpHeaders as $key => $value) {
            if (substr($key, 0, 5) === 'HTTP_') {
                $this->httpHeaders[$key] = $value;
            }
        }

        // In case we're dealing with CloudFront, we need to know.
        $this->setCfHeaders($httpHeaders);
    }

    /**
     * Retrieves the HTTP headers.
     *
     * @return array
     */
    public function getHttpHeaders()
    {
        return $this->httpHeaders;
    }

    /**
     * Retrieves a particular header. If it doesn't exist, no exception/error is caused.
     * Simply null is returned.
     *
     * @param string $header The name of the header to retrieve. Can be HTTP compliant such as
     *                       "User-Agent" or "X-Device-User-Agent" or can be php-esque with the
     *                       all-caps, HTTP_ prefixed, underscore seperated awesomeness.
     *
     * @return string|null The value of the header.
     */
    public function getHttpHeader($header)
    {
        // are we using PHP-flavored headers?
        if (strpos($header, '_') === false) {
            $header = str_replace('-', '_', $header);
            $header = strtoupper($header);
        }

        // test the alternate, too
        $altHeader = 'HTTP_' . $header;

        //Test both the regular and the HTTP_ prefix
        if (isset($this->httpHeaders[$header])) {
            return $this->httpHeaders[$header];
        } elseif (isset($this->httpHeaders[$altHeader])) {
            return $this->httpHeaders[$altHeader];
        }

        return null;
    }

    /**
     * Retrieves the cloudFront headers.
     *
     * @return array
     */
    public function getCfHeaders()
    {
        return $this->cloudFrontHeaders;
    }

    /**
     * Set CloudFront headers
     * http://docs.aws.amazon.com/AmazonCloudFront/latest/DeveloperGuide/header-caching.html#header-caching-web-device
     *
     * @param array $cfHeaders List of HTTP headers
     *
     * @return  boolean If there were CloudFront headers to be set
     */
    public function setCfHeaders($cfHeaders = null)
    {
        // use global _SERVER if $cfHeaders aren't defined
        if (!is_array($cfHeaders) || !count($cfHeaders)) {
            $cfHeaders = $_SERVER;
        }

        // clear existing headers
        $this->cloudFrontHeaders = array();

        // Only save CLOUDFRONT headers. In PHP land, that means only _SERVER vars that
        // start with cloudfront-.
        $response = false;
        foreach ($cfHeaders as $key => $value) {
            if (substr(strtolower($key), 0, 16) === 'http_cloudfront_') {
                $this->cloudFrontHeaders[strtoupper($key)] = $value;
                $response = true;
            }
        }

        return $response;
    }

    /**
     * Set the User-Agent to be used.
     *
     * @param string $userAgent The user agent string to set.
     *
     * @return string|null
     */
    public function setUserAgent($userAgent = null)
    {
        // Invalidate cache due to #375
        $this->cache = array();

        if (false === empty($userAgent))
            return $this->userAgent = $userAgent;
        else {
            $this->userAgent = null;
            foreach (AgentConfig::getUaHttpHeaders() as $altHeader) {
                if (false === empty($this->httpHeaders[$altHeader]))
                    $this->userAgent .= $this->httpHeaders[$altHeader] . " ";
            }

            if (!empty($this->userAgent))
                return $this->userAgent = trim($this->userAgent);
        }

        if (sizeof($this->getCfHeaders()))
            return $this->userAgent = 'Amazon CloudFront';

        return $this->userAgent = null;
    }

    /**
     * Retrieve the User-Agent.
     *
     * @return string|null The user agent if it's set.
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * Set the detection type. Must be one of self::DETECTION_TYPE_MOBILE or
     * self::DETECTION_TYPE_EXTENDED. Otherwise, nothing is set.
     *
     *
     * @param string $type The type. Must be a self::DETECTION_TYPE_* constant. The default
     *                     parameter is null which will default to self::DETECTION_TYPE_MOBILE.
     */
    public function setDetectionType($type = null)
    {
        if ($type === null)
            $type = self::DETECTION_TYPE_MOBILE;

        if ($type !== self::DETECTION_TYPE_MOBILE && $type !== self::DETECTION_TYPE_EXTENDED)
            return;

        $this->detectionType = $type;
    }

    /**
     * @return string
     */
    public function getMatchingRegex()
    {
        return $this->matchingRegex;
    }

    /**
     * @return string
     */
    public function getMatchesArray()
    {
        return $this->matchesArray;
    }

    /**
     * Retrieve the current set of rules.
     *
     *
     * @return array
     */
    public function getRules()
    {
        if ($this->detectionType == self::DETECTION_TYPE_EXTENDED) {
            return AgentConfig::getMobileDetectionRulesExtended();
        } else {
            return AgentConfig::getMobileDetectionRules();
        }
    }

    /**
     * Check the HTTP headers for signs of mobile.
     * This is the fastest mobile check possible; it's used
     * inside isMobile() method.
     *
     * @return bool
     */
    public function checkHttpHeadersForMobile()
    {

        foreach (AgentConfig::getMobileHeaders() as $mobileHeader => $matchType) {
            if (isset($this->httpHeaders[$mobileHeader])) {
                if (is_array($matchType['matches'])) {
                    foreach ($matchType['matches'] as $_match) {
                        if (strpos($this->httpHeaders[$mobileHeader], $_match) !== false) {
                            return true;
                        }
                    }

                    return false;
                } else {
                    return true;
                }
            }
        }

        return false;

    }

    /**
     * Magic overloading method.
     *
     * @method boolean is[...]()
     * @param  string $name
     * @param  array $arguments
     * @return mixed
     * @throws \Error when the method doesn't exist and doesn't start with 'is'
     */
    public function __call($name, $arguments)
    {
        // make sure the name starts with 'is', otherwise
        if (substr($name, 0, 2) !== 'is') {
            throw new \Error("No such method exists: $name");
        }

        $this->setDetectionType(self::DETECTION_TYPE_MOBILE);

        $key = substr($name, 2);

        return $this->matchUAAgainstKey($key);
    }

    /**
     * Check if the device is mobile.
     * Returns true if any type of mobile device detected, including special ones
     * @param  null $userAgent deprecated
     * @param  null $httpHeaders deprecated
     * @return bool
     */
    public function isMobile($userAgent = null, $httpHeaders = null)
    {

        if ($httpHeaders) {
            $this->setHttpHeaders($httpHeaders);
        }

        if ($userAgent) {
            $this->setUserAgent($userAgent);
        }

        // Check specifically for cloudfront headers if the useragent === 'Amazon CloudFront'
        if ($this->getUserAgent() === 'Amazon CloudFront') {
            $cfHeaders = $this->getCfHeaders();
            if (array_key_exists('HTTP_CLOUDFRONT_IS_MOBILE_VIEWER', $cfHeaders) && $cfHeaders['HTTP_CLOUDFRONT_IS_MOBILE_VIEWER'] === 'true') {
                return true;
            }
        }

        $this->setDetectionType(self::DETECTION_TYPE_MOBILE);

        if ($this->checkHttpHeadersForMobile()) {
            return true;
        } else {
            return $this->matchDetectionRulesAgainstUA();
        }

    }

    /**
     * Check if the device is a tablet.
     * Return true if any type of tablet device is detected.
     *
     * @param  string $userAgent deprecated
     * @param  array $httpHeaders deprecated
     * @return bool
     */
    public function isTablet($userAgent = null, $httpHeaders = null)
    {
        // Check specifically for cloudfront headers if the useragent === 'Amazon CloudFront'
        if ($this->getUserAgent() === 'Amazon CloudFront') {
            $cfHeaders = $this->getCfHeaders();
            if (array_key_exists('HTTP_CLOUDFRONT_IS_TABLET_VIEWER', $cfHeaders) && $cfHeaders['HTTP_CLOUDFRONT_IS_TABLET_VIEWER'] === 'true') {
                return true;
            }
        }

        $this->setDetectionType(self::DETECTION_TYPE_MOBILE);

        foreach (AgentConfig::getTabletDevices() as $_regex) {
            if ($this->match($_regex, $userAgent)) {
                return true;
            }
        }

        return false;
    }

    /**
     * This method checks for a certain property in the
     * userAgent.
     * @todo: The httpHeaders part is not yet used.
     *
     * @param  string $key
     * @param  string $userAgent deprecated
     * @param  string $httpHeaders deprecated
     * @return bool|int|null
     */
    public function is($key, $userAgent = null, $httpHeaders = null)
    {
        // Set the UA and HTTP headers only if needed (eg. batch mode).
        if ($httpHeaders) {
            $this->setHttpHeaders($httpHeaders);
        }

        if ($userAgent) {
            $this->setUserAgent($userAgent);
        }

        $this->setDetectionType(self::DETECTION_TYPE_EXTENDED);

        return $this->matchUAAgainstKey($key);
    }

    /**
     * Some detection rules are relative (not standard),
     * because of the diversity of devices, vendors and
     * their conventions in representing the User-Agent or
     * the HTTP headers.
     *
     * This method will be used to check custom regexes against
     * the User-Agent string.
     *
     * @param $regex
     * @param  string $userAgent
     * @return bool
     *
     * @todo: search in the HTTP headers too.
     */
    public function match($regex, $userAgent = null)
    {
        $match = (bool)preg_match(sprintf('#%s#is', $regex), (false === empty($userAgent) ? $userAgent : $this->userAgent), $matches);
        // If positive match is found, store the results for debug.
        if ($match) {
            $this->matchingRegex = $regex;
            $this->matchesArray = $matches;
        }

        return $match;
    }

    /**
     * Prepare the version number.
     *
     * @todo Remove the error supression from str_replace() call.
     *
     * @param string $ver The string version, like "2.6.21.2152";
     *
     * @return float
     */
    public function prepareVersionNo($ver)
    {
        $ver = str_replace(array('_', ' ', '/'), '.', $ver);
        $arrVer = explode('.', $ver, 2);

        if (isset($arrVer[1])) {
            $arrVer[1] = @str_replace('.', '', $arrVer[1]);
        }

        return (float)implode('.', $arrVer);
    }

    /**
     * Check the version of the given property in the User-Agent.
     * Will return a float number. (eg. 2_0 will return 2.0, 4.3.1 will return 4.31)
     *
     * @param string $propertyName The name of the property. See self::getProperties() array
     *                             keys for all possible properties.
     * @param string $type Either self::VERSION_TYPE_STRING to get a string value or
     *                             self::VERSION_TYPE_FLOAT indicating a float value. This parameter
     *                             is optional and defaults to self::VERSION_TYPE_STRING. Passing an
     *                             invalid parameter will default to the this type as well.
     *
     * @return string|float The version of the property we are trying to extract.
     */
    public function version($propertyName, $type = self::VERSION_TYPE_STRING)
    {
        if (empty($propertyName)) {
            return false;
        }

        // set the $type to the default if we don't recognize the type
        if ($type !== self::VERSION_TYPE_STRING && $type !== self::VERSION_TYPE_FLOAT) {
            $type = self::VERSION_TYPE_STRING;
        }

        $properties = AgentConfig::getProperties();

        // Check if the property exists in the properties array.
        if (true === isset($properties[$propertyName])) {

            // Prepare the pattern to be matched.
            // Make sure we always deal with an array (string is converted).
            $properties[$propertyName] = (array)$properties[$propertyName];

            foreach ($properties[$propertyName] as $propertyMatchString) {

                $propertyPattern = str_replace('[VER]', self::VER, $propertyMatchString);

                // Identify and extract the version.
                preg_match(sprintf('#%s#is', $propertyPattern), $this->userAgent, $match);

                if (false === empty($match[1])) {
                    $version = ($type == self::VERSION_TYPE_FLOAT ? $this->prepareVersionNo($match[1]) : $match[1]);

                    return $version;
                }

            }

        }

        return false;
    }

    /**
     * Retrieve the mobile grading, using self::MOBILE_GRADE_* constants.
     *
     * @return string One of the self::MOBILE_GRADE_* constants.
     */
    public function mobileGrade()
    {
        $isMobile = $this->isMobile();

        if (
            // Apple iOS 4-7.0 – Tested on the original iPad (4.3 / 5.0), iPad 2 (4.3 / 5.1 / 6.1), iPad 3 (5.1 / 6.0), iPad Mini (6.1), iPad Retina (7.0), iPhone 3GS (4.3), iPhone 4 (4.3 / 5.1), iPhone 4S (5.1 / 6.0), iPhone 5 (6.0), and iPhone 5S (7.0)
            $this->is('iOS') && $this->version('iPad', self::VERSION_TYPE_FLOAT) >= 4.3 ||
            $this->is('iOS') && $this->version('iPhone', self::VERSION_TYPE_FLOAT) >= 4.3 ||
            $this->is('iOS') && $this->version('iPod', self::VERSION_TYPE_FLOAT) >= 4.3 ||

            // Android 2.1-2.3 - Tested on the HTC Incredible (2.2), original Droid (2.2), HTC Aria (2.1), Google Nexus S (2.3). Functional on 1.5 & 1.6 but performance may be sluggish, tested on Google G1 (1.5)
            // Android 3.1 (Honeycomb)  - Tested on the Samsung Galaxy Tab 10.1 and Motorola XOOM
            // Android 4.0 (ICS)  - Tested on a Galaxy Nexus. Note: transition performance can be poor on upgraded devices
            // Android 4.1 (Jelly Bean)  - Tested on a Galaxy Nexus and Galaxy 7
            ($this->version('Android', self::VERSION_TYPE_FLOAT) > 2.1 && $this->is('Webkit')) ||

            // Windows Phone 7.5-8 - Tested on the HTC Surround (7.5), HTC Trophy (7.5), LG-E900 (7.5), Nokia 800 (7.8), HTC Mazaa (7.8), Nokia Lumia 520 (8), Nokia Lumia 920 (8), HTC 8x (8)
            $this->version('Windows Phone OS', self::VERSION_TYPE_FLOAT) >= 7.5 ||

            // Tested on the Torch 9800 (6) and Style 9670 (6), BlackBerry® Torch 9810 (7), BlackBerry Z10 (10)
            $this->is('BlackBerry') && $this->version('BlackBerry', self::VERSION_TYPE_FLOAT) >= 6.0 ||
            // Blackberry Playbook (1.0-2.0) - Tested on PlayBook
            $this->match('Playbook.*Tablet') ||

            // Palm WebOS (1.4-3.0) - Tested on the Palm Pixi (1.4), Pre (1.4), Pre 2 (2.0), HP TouchPad (3.0)
            ($this->version('webOS', self::VERSION_TYPE_FLOAT) >= 1.4 && $this->match('Palm|Pre|Pixi')) ||
            // Palm WebOS 3.0  - Tested on HP TouchPad
            $this->match('hp.*TouchPad') ||

            // Firefox Mobile 18 - Tested on Android 2.3 and 4.1 devices
            ($this->is('Firefox') && $this->version('Firefox', self::VERSION_TYPE_FLOAT) >= 18) ||

            // Chrome for Android - Tested on Android 4.0, 4.1 device
            ($this->is('Chrome') && $this->is('AndroidOS') && $this->version('Android', self::VERSION_TYPE_FLOAT) >= 4.0) ||

            // Skyfire 4.1 - Tested on Android 2.3 device
            ($this->is('Skyfire') && $this->version('Skyfire', self::VERSION_TYPE_FLOAT) >= 4.1 && $this->is('AndroidOS') && $this->version('Android', self::VERSION_TYPE_FLOAT) >= 2.3) ||

            // Opera Mobile 11.5-12: Tested on Android 2.3
            ($this->is('Opera') && $this->version('Opera Mobi', self::VERSION_TYPE_FLOAT) >= 11.5 && $this->is('AndroidOS')) ||

            // Meego 1.2 - Tested on Nokia 950 and N9
            $this->is('MeeGoOS') ||

            // Tizen (pre-release) - Tested on early hardware
            $this->is('Tizen') ||

            // Samsung Bada 2.0 - Tested on a Samsung Wave 3, Dolphin browser
            // @todo: more tests here!
            $this->is('Dolfin') && $this->version('Bada', self::VERSION_TYPE_FLOAT) >= 2.0 ||

            // UC Browser - Tested on Android 2.3 device
            (($this->is('UC Browser') || $this->is('Dolfin')) && $this->version('Android', self::VERSION_TYPE_FLOAT) >= 2.3) ||

            // Kindle 3 and Fire  - Tested on the built-in WebKit browser for each
            ($this->match('Kindle Fire') ||
                $this->is('Kindle') && $this->version('Kindle', self::VERSION_TYPE_FLOAT) >= 3.0) ||

            // Nook Color 1.4.1 - Tested on original Nook Color, not Nook Tablet
            $this->is('AndroidOS') && $this->is('NookTablet') ||

            // Chrome Desktop 16-24 - Tested on OS X 10.7 and Windows 7
            $this->version('Chrome', self::VERSION_TYPE_FLOAT) >= 16 && !$isMobile ||

            // Safari Desktop 5-6 - Tested on OS X 10.7 and Windows 7
            $this->version('Safari', self::VERSION_TYPE_FLOAT) >= 5.0 && !$isMobile ||

            // Firefox Desktop 10-18 - Tested on OS X 10.7 and Windows 7
            $this->version('Firefox', self::VERSION_TYPE_FLOAT) >= 10.0 && !$isMobile ||

            // Internet Explorer 7-9 - Tested on Windows XP, Vista and 7
            $this->version('IE', self::VERSION_TYPE_FLOAT) >= 7.0 && !$isMobile ||

            // Opera Desktop 10-12 - Tested on OS X 10.7 and Windows 7
            $this->version('Opera', self::VERSION_TYPE_FLOAT) >= 10 && !$isMobile
        ) {
            return self::MOBILE_GRADE_A;
        }

        if (
            $this->is('iOS') && $this->version('iPad', self::VERSION_TYPE_FLOAT) < 4.3 ||
            $this->is('iOS') && $this->version('iPhone', self::VERSION_TYPE_FLOAT) < 4.3 ||
            $this->is('iOS') && $this->version('iPod', self::VERSION_TYPE_FLOAT) < 4.3 ||

            // Blackberry 5.0: Tested on the Storm 2 9550, Bold 9770
            $this->is('Blackberry') && $this->version('BlackBerry', self::VERSION_TYPE_FLOAT) >= 5 && $this->version('BlackBerry', self::VERSION_TYPE_FLOAT) < 6 ||

            //Opera Mini (5.0-6.5) - Tested on iOS 3.2/4.3 and Android 2.3
            ($this->version('Opera Mini', self::VERSION_TYPE_FLOAT) >= 5.0 && $this->version('Opera Mini', self::VERSION_TYPE_FLOAT) <= 7.0 &&
                ($this->version('Android', self::VERSION_TYPE_FLOAT) >= 2.3 || $this->is('iOS'))) ||

            // Nokia Symbian^3 - Tested on Nokia N8 (Symbian^3), C7 (Symbian^3), also works on N97 (Symbian^1)
            $this->match('NokiaN8|NokiaC7|N97.*Series60|Symbian/3') ||

            // @todo: report this (tested on Nokia N71)
            $this->version('Opera Mobi', self::VERSION_TYPE_FLOAT) >= 11 && $this->is('SymbianOS')
        ) {
            return self::MOBILE_GRADE_B;
        }

        if (
            // Blackberry 4.x - Tested on the Curve 8330
            $this->version('BlackBerry', self::VERSION_TYPE_FLOAT) <= 5.0 ||
            // Windows Mobile - Tested on the HTC Leo (WinMo 5.2)
            $this->match('MSIEMobile|Windows CE.*Mobile') || $this->version('Windows Mobile', self::VERSION_TYPE_FLOAT) <= 5.2 ||

            // Tested on original iPhone (3.1), iPhone 3 (3.2)
            $this->is('iOS') && $this->version('iPad', self::VERSION_TYPE_FLOAT) <= 3.2 ||
            $this->is('iOS') && $this->version('iPhone', self::VERSION_TYPE_FLOAT) <= 3.2 ||
            $this->is('iOS') && $this->version('iPod', self::VERSION_TYPE_FLOAT) <= 3.2 ||

            // Internet Explorer 7 and older - Tested on Windows XP
            $this->version('IE', self::VERSION_TYPE_FLOAT) <= 7.0 && !$isMobile
        ) {
            return self::MOBILE_GRADE_C;
        }

        // All older smartphone platforms and featurephones - Any device that doesn't support media queries
        // will receive the basic, C grade experience.
        return self::MOBILE_GRADE_C;
    }

    /**
     * Find a detection rule that matches the current User-agent.
     *
     * @param  null $userAgent deprecated
     * @return bool
     */
    protected function matchDetectionRulesAgainstUA($userAgent = null)
    {
        // Begin general search.
        foreach ($this->getRules() as $_regex) {
            if (empty($_regex)) {
                continue;
            }

            if ($this->match($_regex, $userAgent)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Search for a certain key in the rules array.
     * If the key is found then try to match the corresponding
     * regex against the User-Agent.
     *
     * @param string $key
     *
     * @return boolean
     */
    protected function matchUAAgainstKey($key)
    {
        // Make the keys lowercase so we can match: isIphone(), isiPhone(), isiphone(), etc.
        $key = strtolower($key);
        if (false === isset($this->cache[$key])) {

            // change the keys to lower case
            $_rules = array_change_key_case($this->getRules());

            if (false === empty($_rules[$key])) {
                $this->cache[$key] = $this->match($_rules[$key]);
            }

            if (false === isset($this->cache[$key])) {
                $this->cache[$key] = false;
            }
        }

        return $this->cache[$key];
    }
}