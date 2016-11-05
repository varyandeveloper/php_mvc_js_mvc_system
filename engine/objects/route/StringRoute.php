<?php
/**
 * Created by PhpStorm.
 * User: 37498_000
 * Date: 08.06.2016
 * Time: 18:16
 */

namespace engine\objects\route;
use engine\abstracts\RouteResult;
use engine\Engine;

/**
 * Class StringRoute
 * @package Engine\Additional\Route
 */
class StringRoute extends RouteResult
{
    /**
     * @return void
     */
    public function execute()
    {
        if(empty($this->_url)):
            if(is_string($this->_value)):
                $this->makeRouteArray();
            endif;
        else:
            $parts = $this->_correctParts();
            for($i = 0; $i < sizeof($parts); $i++):
                if(strpos($this->_value,"$".$i) !== FALSE) :
                    $this->_value = str_replace("$".$i,$parts[$i],$this->_value);
                endif;
            endfor;
            $this->makeRouteArray();
        endif;
    }

    /**
     * @return void
     */
    private function makeRouteArray()
    {
        $parts = explode("/",$this->_value);
        $this->_controller = @$parts[0];
        $this->_method = !empty($parts[1]) ? $parts[1] : "index";

        if(!empty($parts[2])):
            unset($parts[0],$parts[1]);
            $this->_parameters = array_values($parts);
        endif;
    }

    /**
     * @return array
     */
    protected function _correctParts()
    {
        $parts = explode("/",$this->_url);
        $count = substr_count($this->_value,"$") + 1;
        unset($parts[sizeof($parts) - $count]);
        return array_values($parts);
    }
}