<?php
/**
 * Created by PhpStorm.
 * User: 37498_000
 * Date: 10.06.2016
 * Time: 14:48
 */

namespace engine\traits;;

/**
 * Class Factory
 * @package engine\traits
 */
trait Factory
{
    /**
     * @param string $className
     * @return object
     */
    public static function create(string $className)
    {
        $args = func_get_args();
        unset($args[0]);
        $args = array_values($args);

        return self::__injectConstructor($className, $args);
    }

    /**
     * @param $objectName
     * @param string $methodName
     * @return mixed
     */
    public static function injectMethod($objectName, string $methodName)
    {
        $args = func_get_args();
        unset($args[0], $args[1]);
        if (isset($args[2]) && is_array($args[2]))
            $args = array_values($args[2]);

        $reflectionClass = self::__refClass($objectName);

        if (!$reflectionClass->hasMethod($methodName))
            exit("Controller $objectName dose`nt have $methodName method \n");

        $method = $reflectionClass->getMethod($methodName);

        if (!$method)
            exit("Controller $objectName dose`nt have $methodName method \n");

        $params = $method->getParameters();
        $newInstanceParams = [];
        $injectCount = 0;

        for ($i = 0; $i < sizeof($params); $i++) {
            if (!is_null($params[$i]->getClass())) {
                $injectCount++;
                $newInstanceParams[$i] = self::__injectConstructor($params[$i]->getClass()->getName());
            } elseif(isset($args[$i - $injectCount]))
                $newInstanceParams[$i] = $args[$i - $injectCount];

        }

        return $newInstanceParams
            ? call_user_func_array([self::create($objectName), $methodName], $newInstanceParams)
            : call_user_func([self::create($objectName), $methodName]);
    }

    /**
     * @param \Closure $callback
     * @param array $defaultArgs
     * @return mixed
     */
    public static function injectFunction(\Closure $callback, $defaultArgs = [])
    {
        $args = $defaultArgs;
        $refFunction = new \ReflectionFunction($callback);
        $parameters = $refFunction->getParameters();
        for ($i = sizeof($defaultArgs); $i < sizeof($parameters); $i++) {
            if ($parameters[$i]->getClass()) {
                $args[] = self::create($parameters[$i]->getClass()->name);
            }
        }
        return call_user_func_array($callback, $args);
    }

    /**
     * @param $className
     * @return \ReflectionClass
     */
    private static function __refClass($className)
    {
        $className = str_replace("/","\\",str_replace("../",DS,$className));
        return new \ReflectionClass($className);
    }

    /**
     * @param $class
     * @param array $args
     * @return object
     */
    private static function __injectConstructor($class, $args = null)
    {
        $reflectionClass = self::__refClass($class);
        $constructor = $reflectionClass->getConstructor();

        if (!$constructor)
            return $reflectionClass->newInstanceWithoutConstructor();

        $params = $constructor->getParameters();

        if (sizeof($params) === 0)
            return $reflectionClass->newInstance();

        $newInstanceParams = [];

        for ($i = 0; $i < sizeof($params); $i++) {
            if (!is_null($params[$i]->getClass()))
                $newInstanceParams[] = self::__injectConstructor($params[$i]->getClass()->getName());
        }

        if (is_array($args))
            $newInstanceParams = array_merge($newInstanceParams, $args);

        return $reflectionClass->newInstanceArgs($newInstanceParams);
    }

}