<?php
/**
 * Created by PhpStorm.
 * User: 37498_000
 * Date: 08.06.2016
 * Time: 18:16
 */

namespace engine\objects\route;
use engine\abstracts\RouteResult;
use engine\objects\Factory;

/**
 * Class CallableRoute
 * @package Engine\Additional\Route
 */
class CallableRoute extends RouteResult
{
    /**
     * @return void
     */
    public function execute()
    {
        $refFunction = new \ReflectionFunction($this->_value);
        $parametersCount = $refFunction->getNumberOfParameters();

        if($refFunction->returnsReference())
            exit("Route Closure must return string value");

        if($parametersCount)
        {
            $collectedArgs = [];
            $allParameters = $refFunction->getParameters();
            $parameters = explode("/",$this->_url);
            $parameters = array_splice($parameters,-$parametersCount);

            $injectCount = 0;
            for($i = 0; $i < sizeof($allParameters); $i++)
            {
                if($allParameters[$i]->getClass())
                {
                    $injectCount++;
                    $collectedArgs[$i] = Factory::create($allParameters[$i]->getClass()->getName());
                }
                else
                    $collectedArgs[$i] = $parameters[$i-$injectCount];

            }
            $func = $refFunction->invokeArgs($collectedArgs);
        }else
            $func = $refFunction->invoke();

        if(is_callable($func))
        {
            $this->_value = $func;
            $this->execute();
        }
        elseif(is_null($func)){
            exit("Router Closure have too return string|callable values");
        }
        else {
            $parts = explode("/",$func);
            $this->_controller = $parts[0];

            if(isset($parts[1]))
                $this->_method = $parts[1];

            if(isset($parts[2])):
                unset($parts[0],$parts[1]);
                $this->_parameters = $parts;
            endif;
        }
    }
}