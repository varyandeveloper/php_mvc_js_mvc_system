<?php
/**
 * Created by PhpStorm.
 * User: Var Yan
 * Date: 8/21/2016
 * Time: 8:39 PM
 */

namespace engine\objects;

use engine\abstracts\ControllerParent;

/**
 * Class Controller
 * @package engine\objects
 */
class Controller extends ControllerParent
{
    /**
     * @var array $_data
     */
    private $__data = [];

    /**
     * @param string|array $keyOrKeys
     * @param mixed $value
     * @return void
     */
    protected function _setData($keyOrKeys, $value = null)
    {
        if (is_string($keyOrKeys))
            $this->__data[$keyOrKeys] = $value;
        elseif (is_array($keyOrKeys)) {
            foreach ($keyOrKeys as $key => $value) {
                $this->_setData($key, $value);
            }
        } else {
            exit("You can pass array (key=>value) or two parameters as string key and mixed value");
        }
    }

    /**
     * @param string $key
     * @return mixed
     */
    protected function _getData(string $key = null)
    {
        return is_null($key)
            ? $this->__data
            : @$this->__data[$key];
    }
}