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
use engine\objects\Collection;

/**
 * Class ArrayRoute
 * @package Engine\Additional\Route
 */
class ArrayRoute extends RouteResult
{
    /**
     * @var array $argsStart
     */
    protected static $_argsUsed = [];

    /**
     * @return void
     */
    public function execute()
    {
        Engine::isAssoc($this->_value)
            ? $this->__collectAssoc()
            : $this->__collect();
    }

    /**
     * @return void
     */
    private function __collect()
    {
        $this->_controller = @$this->_value[0];
        if(isset($this->_value[1]))
        {
            if(strpos($this->_value[1],"$") !== FALSE)
            {
                $parts = explode("/",$this->_url);
                $index = str_replace("$","",$this->_value[1]);
                $this->_method = @$parts[$index];
            }
            else
                $this->_method = $this->_value[1];
        }
        if(isset($this->_value[2])):
            if(!is_array($this->_value[2]))
                exit("The 3th value of route simple array must be array");
            $parts = explode("/",$this->_url);
            for($i = 0; $i < sizeof($this->_value[2]); $i++):
                $index = str_replace("$","",$this->_value[2][$i]);
                $this->_parameters[] = @$parts[$index];
            endfor;
        endif;
    }

    /**
     * @return void
     */
    private function __collectAssoc()
    {
        $this->_controller = @$this->_value['controller'];
        if(isset($this->_value['method']))
        {
            if(strpos($this->_value['method'],"$") !== FALSE)
            {
                $parts = explode("/",$this->_url);
                $index = str_replace("$","",$this->_value["method"]);
                $this->_method = $parts[$index];
            }
            else
                $this->_method = $this->_value['method'];
        }

        if(isset($this->_value['parameters'])):
            $parts = explode("/",$this->_url);
            for($i = 0; $i < sizeof($this->_value['parameters']); $i++):
                $index = str_replace("$","",$this->_value['parameters'][$i]);
                $this->_parameters[] = @$parts[$index];
            endfor;
        endif;
    }
}