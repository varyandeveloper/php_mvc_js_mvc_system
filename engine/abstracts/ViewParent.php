<?php
/**
 * Created by PhpStorm.
 * User: Var Yan
 * Date: 7/21/2016
 * Time: 10:23 PM
 */

namespace engine\abstracts;

use engine\Engine;
use engine\interfaces\ViewSchema;
use engine\objects\Router;

/**
 * Class ViewParent
 * @package engine\abstracts
 */
abstract class ViewParent implements ViewSchema
{
    /**
     * @var bool $_asOutput
     */
    protected $_asOutput;
    /**
     * @var string $_view
     */
    protected $_view;
    /**
     * @var array $_prevView
     */
    protected static $_prevViews;
    /**
     * @var array $_loadVars
     * */
    protected static $_loadVars = [];

    /**
     * @var Router $_router
     */
    protected $_router;

    /**
     * View constructor.
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->_router = $router;
    }

    /**
     * render method
     * @param string $filename
     * @param \stdClass|array $args
     * @param boolean $asOutput
     * @return ViewSchema
     * */
    public function render(string $filename, $args = null, bool $asOutput = false) : ViewSchema
    {
        $filename = str_replace("/",DS,$filename);
        $this->_render($filename,$args,$asOutput);
        return $this;
    }

    /**
     * @param array|\stdClass $args
     * @return ViewSchema
     */
    public function withVars($args) : ViewSchema
    {
        $args = (array)$args;

        foreach ($args as $key => $val)
            self::$_loadVars[$key] = $val;

        self::$_loadVars = array_merge(self::$_loadVars, (array)$args);

        return $this;
    }

    /**
     * @return array
     */
    public function getOutput() : array
    {
        return [
            "extract" => self::$_loadVars,
            "asOutput" => $this->_asOutput,
            "view" => $this->_view
        ];
    }

    /**
     * @return string
     */
    public function immediately() : string
    {
        if (!$this->_asOutput)
            exit("You should use this action then you need to get buffered data");

        if (!ob_start("ob_gzhandler")) ob_start();
        extract(self::$_loadVars);
        include $this->_view;
        $output = @ob_get_contents();
        @ob_end_clean();
        return $output;
    }

    /**
     * _render method
     * @param string $filename
     * @param \stdClass|array $args
     * @param boolean $asOutput
     * @return void
     * */
    protected function _render($filename, $args, $asOutput)
    {
        if (!is_null($args))
            self::withVars($args);

        $namespace = $this->_router->currentNamespace();
        $subs = !empty($namespace) ? strtolower($namespace . DS) : "";

        $_viewDefault__ = strtolower(RELEASE . Engine::release() . DS . 'view' . DS . $subs . strtolower($this->_router->currentController()) . DS . $filename . EXT);
        $_viewCustom__ = strtolower(RELEASE . Engine::release() . DS . 'View' . DS . $filename . EXT);
        $__currentView__ = file_exists($_viewDefault__) ? strtolower($_viewDefault__) : strtolower($_viewCustom__);

        if (file_exists($__currentView__)) {
            self::$_prevViews[] = $__currentView__;
            $this->_view = $__currentView__;
            $this->_asOutput = $asOutput;
            if (!is_null($args))
                self::$_loadVars = array_merge(self::$_loadVars, (array)$args);
        } else
            exit("View file " . $__currentView__ . " not found \n");
    }
}