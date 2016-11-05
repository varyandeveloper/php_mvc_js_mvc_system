<?php
/**
 * Created by PhpStorm.
 * User: 37498_000
 * Date: 10.06.2016
 * Time: 13:58
 */

namespace engine;

use engine\abstracts\ControllerParent;
use engine\config\CacheConfig;
use engine\interfaces\HTMLSchema;
use engine\interfaces\ViewSchema;
use engine\objects\Factory;
use engine\traits\SetGet;
use engine\traits\Singleton;

/**
 * Class Start
 * @package engine
 */
class Engine
{
    use Singleton, SetGet;

    /**
     * @var string $subDomain
     */
    private $subDomain;

    /**
     * @var bool $usesSubDomain
     */
    private static $usesSubDomain = false;

    /**
     * Run method
     * Can be called with instance only
     * @return void
     */
    public function run()
    {
        ENV::__init__();

        $domainParts = explode(".", $_SERVER['SERVER_NAME']);
        if (sizeof($domainParts) >= 3) {
            self::$usesSubDomain = true;
            $this->subDomain = $domainParts[0];
        }

        $this->paths = $this->__load();

        $routeResult = $this->route->getRoute();
        $ctrlName = RELEASE . $this->release ."/". $this->paths->{$this->release}->controllerPath . "/" . $routeResult['controller'];

        if (!file_exists(str_replace("\\",DS,$ctrlName).EXT))
            $ctrlName = COMMON . $this->paths->{$this->release}->controllerPath . "/" . $routeResult['controller'];

        $ctrl = Factory::create($ctrlName);
        if (!$ctrl instanceof ControllerParent)
            exit("Any Controller have to extends ControllerParent");

        $this->__finish(Factory::injectMethod($ctrlName, $routeResult['method'], $routeResult['parameters']));
    }

    /**
     * @return boolean
     */
    public static function isUsesSubDomain()
    {
        return self::$usesSubDomain;
    }

    /**
     * isAssoc method
     * @param array $arr
     * @param boolean $reusingKeys
     * @return boolean
     * */
    public static function isAssoc(array $arr, bool $reusingKeys = FALSE) : bool
    {
        $range = range(0, count($arr) - 1);
        return $reusingKeys ? $arr !== $range : array_keys($arr) !== $range;
    }

    /**
     * splitAtUpperCase method
     * @param string $string
     * @return array
     * */
    public static function splitAtUpperCase(string $string) : array
    {
        return preg_split('/(?=[A-Z])/', $string, -1, PREG_SPLIT_NO_EMPTY);
    }

    /**
     * @param string $stringToCheck
     * @return bool
     */
    public static function isStringRegex(string $stringToCheck) : bool
    {
        $answer = true;
        ini_set('track_errors', 'on');
        $phpErrorMSG = '';
        @preg_match($stringToCheck, '');
        if ($phpErrorMSG)
            $answer = false;
        ini_set('track_errors', 'off');
        return $answer;
    }

    /**
     * getStringBetween method
     * @param string $string
     * @param string $beginning
     * @param string $end
     * @return string
     * */
    public static function getStringBetween(string $string, string $beginning = '[', string $end = ']') : string
    {
        $beginningPos = strpos($string, $beginning);
        $endPos = strpos($string, $end);
        if ($beginningPos === false || $endPos === false) {
            return $string;
        }

        $textToDelete = substr($string, $beginningPos, ($endPos + strlen($end)) - $beginningPos);

        return $textToDelete;
    }

    /**
     * namespaceExists method
     * @param string $namespace
     * @return boolean
     * */
    public static function namespaceExists(string $namespace) : bool
    {
        $namespace .= DS;
        foreach (get_declared_classes() as $name)
            if (strpos($name, $namespace) === 0) return true;
        return false;
    }

    /**
     * @param string $haystack
     * @param string $needle
     * @return bool
     */
    public static function startsWith($haystack, $needle)
    {
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
    }

    /**
     * @param string $haystack
     * @param string $needle
     * @return bool
     */
    public static function endsWith($haystack, $needle)
    {
        return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
    }

    /**
     * @param $data
     * @param boolean|string $message
     */
    public static function dd($data, $message = false)
    {
        echo "<pre>";
        var_dump($data);
        echo "</pre>";
        if ($message !== FALSE)
            exit($message);
    }

    /**
     * @param $data
     * @param boolean|string $message
     */
    public static function dp($data, $message = false)
    {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        if ($message !== FALSE)
            exit($message);
    }

    /**
     * @param $data
     * @param boolean|string $message
     */
    public static function de($data, $message = false)
    {
        echo "<pre>";
        var_export($data);
        echo "</pre>";
        if ($message !== FALSE)
            exit($message);
    }

    /**
     * loadLang method
     * @param string $fileName
     * @param string $langCode
     * @return array
     * */
    public static function loadLang(string $fileName, string $langCode = 'en') : array
    {
        $file = RELEASE . self::release() . DS . "languages" . DS . "$langCode" . DS . $fileName . EXT;

        if (file_exists($file)) {
            require "{$file}";
            return $$fileName;
        } else {

            $file = COMMON . "languages" . DS . "$langCode" . DS . $fileName . EXT;

            if (file_exists($file)) {
                require "{$file}";
                return $$fileName;
            } else {
                $file = ENGINE . "languages" . DS . "$langCode" . DS . $fileName . EXT;
                if (file_exists($file)) {
                    require "{$file}";
                    return $$fileName;
                } else {
                    exit("Translation file $file missing");
                }
            }
        }
    }

    /**
     * @param string $name
     * @param $arguments
     * @return object
     */
    public function __call($name, $arguments)
    {
        return Factory::create(ENGINE . "objects" . DS . $name, $arguments);
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        if (!array_key_exists($name, self::$__items))
            self::$__items[$name] = Factory::create(ENGINE . "objects" . DS . $name, $arguments);
        return self::$__items[$name];
    }

    /**
     * __load method
     * @return object
     */
    private function __load()
    {
        $config = json_decode(file_get_contents(CONFIG));

        $this->url = Factory::create(ENGINE . "objects" . DS . "URL");
        if (!empty($this->subDomain) && property_exists($config->releases, $this->subDomain)):
            $this->release = $this->subDomain;
            $this->__process();
        elseif (!empty($this->url->segment(0)) && property_exists($config->releases, $this->url->segment(0))):
            $this->release = $this->url->segment(0);
            $this->__process();
        else:
            $this->isDefault = true;
            $this->release = $config->defaultRelease;
            $this->__process();
        endif;

        return $config->releases;
    }

    /**
     * @return void
     */
    private function __process()
    {
        if (!is_dir(RELEASE . $this->release))
            exit("The " . $this->release . " release directory dose`nt exists");

        $this->route = Factory::create(ENGINE . "objects" . DS . "Router");

        $requireArray = [];

        if (file_exists(COMMON . DS . "config" . EXT))
            $requireArray[] = COMMON . DS . "config" . EXT;

        array_push($requireArray, RELEASE . $this->release . DS . "config" . EXT, ENGINE . "mvc" . EXT);

        if (file_exists(COMMON . DS . "routes" . EXT))
            $requireArray[] = COMMON . DS . "routes" . EXT;

        array_push($requireArray, RELEASE . $this->release . DS . "routes" . EXT);

        $this->__checkFilesAndRequire($requireArray);

        $__links__ = RELEASE . $this->release . DS . "links" . EXT;
        if (file_exists($__links__)):
            require_once $__links__;
        endif;
    }

    /**
     * @param mixed $output
     * @return null|string
     */
    private function __finish($output)
    {
        switch (TRUE):
            case ($output instanceof ViewSchema)   :
                $data = (object)$output->getOutput();

                if (CacheConfig::isOn())
                    CacheConfig::start();

                if (!ob_start("ob_gzhandler")) ob_start();
                extract($data->extract);
                include $data->view;
                if ($data->asOutput) {
                    $output = @ob_get_contents();
                    @ob_end_clean();
                    return $output;
                } else {

                    if (CacheConfig::isOn())
                        CacheConfig::end();

                    @ob_end_flush();
                }
                break;
            case ($output instanceof HTMLSchema):
            case (is_string($output)):
                echo $output;
                break;
            default:
                return null;
                break;
        endswitch;
    }

    /**
     * @param string $files
     */
    private function __checkFilesAndRequire($files)
    {
        for ($i = 0; $i < sizeof($files); $i++) {
            if (!file_exists($files[$i])) {
                $fileName = explode(DS, $files[$i]);
                exit("File " . end($fileName) . " not found in " . $this->release . " release");
            }

            require_once $files[$i];
        }
    }
}