<?php
/**
 * Created by PhpStorm.
 * User: Var Yan
 * Date: 6/18/2016
 * Time: 10:56 PM
 */

namespace engine\abstracts;

use engine\interfaces\HTMLSchema;
use engine\objects\HTML;

/**
 * Class HTMLElement
 * @package engine\abstracts
 */
abstract class HTMLElement implements HTMLSchema
{
    /**
     * @var string $_mini
     */
    protected static $_mini = "";
    /**
     * @var string $_output
     */
    protected static $_output = "";
    /**
     * @var array $_closeTags
     */
    protected static $_closeTags = [];

    /**
     * HTMLElement constructor.
     * @param string|null $tagName
     * @param array|null $attributes
     */
    public function __construct(string $tagName = null, array $attributes = null)
    {
        if(!empty($tagName))
        {
            self::$_output .= "<" . $tagName;
            if(is_array($attributes))
                $this->__addAttributes($attributes);
        }
    }

    /**
     * @param $attributes
     */
    private final function __addAttributes($attributes)
    {
        $inner = @$attributes['inner'];
        unset($attributes['inner']);

        foreach ($attributes as $attr => $value)
        {
            if($attr == "optional") {
                self::$_output .= " ".$value;
            } else{
                self::$_output .= " {$attr}";
                if(!empty($value))
                    self::$_output .= "='{$value}'";
            }

        }
        self::$_output .= ">{$inner}".self::$_mini;
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        $out = self::$_output;
        self::$_output = "";
        return $out;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return object
     */
    public function __call($name, $arguments)
    {
        if(sizeof($arguments))
            $arguments = $arguments[0];
        return self::_callMaster($name,$arguments);
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return HTMLElement
     */
    public static function __callStatic($name, $arguments)
    {
        if(sizeof($arguments))
            $arguments = $arguments[0];
        return self::_callMaster($name,$arguments);
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return HTML
     */
    protected static function _callMaster($name,$arguments)
    {
        switch ($name):
            case "link";
            case "input";
            case "meta";
                self::$_closeTags[] = "";
                break;
            default:
                self::$_closeTags[] = "</{$name}>".self::$_mini;
                break;
        endswitch;
        return new HTML($name,$arguments);
    }

    /**
     * @return $this
     */
    public function close()
    {
        $tagName = end(self::$_closeTags);
        unset(self::$_closeTags[(sizeof(self::$_closeTags) - 1)]);
        self::$_closeTags = array_values(self::$_closeTags);
        self::$_output .= "{$tagName}";
        return $this;
    }
}