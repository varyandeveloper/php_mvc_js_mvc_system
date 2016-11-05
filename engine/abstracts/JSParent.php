<?php
/**
 * Created by PhpStorm.
 * User: Var Yan
 * Date: 7/9/2016
 * Time: 2:03 AM
 */

namespace engine\abstracts;

use engine\interfaces\JSSchema;
use engine\objects\Factory;
use engine\traits\Instance;

/**
 * Class JSParent
 * @package engine\abstracts
 */
abstract class JSParent implements JSSchema
{
    use Instance;
    /**
     * @var string $_selector
     */
    protected static $_selector;
    /**
     * @var string $_content
     */
    protected $_content = "";

    /**
     * JSParent constructor.
     * @param string|null $selector
     */
    public function __construct(string $selector = null)
    {
        if (!is_null($selector))
            self::$_selector = $selector;
    }

    /**
     * @param string $tagName
     * @param string $innerHtml
     * @param \Closure $callback
     * @return JSSchema
     */
    public function append(string $tagName, string $innerHtml, \Closure $callback = null) : JSSchema
    {
        $id = uniqid();
        $this->_content .= "
            ".$this::ELEM." = ".$this::CREATE."('{$tagName}');
            ".$this::ELEM.".innerHTML = '{$innerHtml}';
            ".$this::ELEM.".id = '$tagName-{$id}'
            ".$this::SELECTOR."('" . self::$_selector . "').".$this::CHILD."(".$this::ELEM.");
        ";
        $this->_callback($callback, "{$tagName}-{$id}");
        return $this;
    }

    /**
     * @param string $newSelector
     * @param \Closure|null $callback
     * @return JSSchema
     */
    public function selector(string $newSelector, \Closure $callback = null) : JSSchema
    {
        self::$_selector = $newSelector;
        if(!is_null($callback))
            Factory::injectFunction($callback);
        return $this;
    }

    /**
     * @param string $action
     * @param \Closure $callback
     * @return JSSchema
     */
    public function on(string $action, \Closure $callback) : JSSchema
    {
        $stringResult = call_user_func($callback);
        if (!is_string($stringResult))
            exit("Callback have to return string as JavaScript code");

        $this->_content .= $this::SELECTOR."('" . self::$_selector . "')['on{$action}'] = function(){
            " . $stringResult . "  
        };";
        return $this;
    }

    /**
     * @param string $innerText
     * @return JSSchema
     */
    public function text(string $innerText) : JSSchema
    {
        $this->_content .= $this::SELECTOR."('" . self::$_selector . "').innerText = '{$innerText}';";
        return $this;
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return "<script>{$this->_content}</script>";
    }

    /**
     * @param \Closure $callback
     * @param string $id
     */
    protected function _callback(\Closure $callback = null, string $id)
    {
        if (!is_null($callback)) {
            $tmpSelector = self::$_selector;
            self::$_selector = "#{$id}";
            call_user_func_array($callback, [self::getInstance()]);
            self::$_selector = $tmpSelector;
        }
    }
}