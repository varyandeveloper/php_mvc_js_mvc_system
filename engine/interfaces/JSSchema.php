<?php
/**
 * Created by PhpStorm.
 * User: Var Yan
 * Date: 7/9/2016
 * Time: 1:59 AM
 */

namespace engine\interfaces;

/**
 * Interface JSSchema
 * @package engine\interfaces
 */
interface JSSchema extends EngineSchema
{
    const SELECTOR = "document.querySelector";
    const ELEM = "window.element";
    const CREATE = "document.createElement";
    const CHILD = "appendChild";

    /**
     * @param string $newSelector
     * @param \Closure|null $callback
     * @return JSSchema
     */
    public function selector(string $newSelector, \Closure $callback = null) : JSSchema;

    /**
     * @param string $tagName
     * @param string $innerText
     * @param \Closure $callback
     * @return JSSchema
     */
    public function append(string $tagName, string $innerText, \Closure $callback = null) : JSSchema;

    /**
     * @param string $action
     * @param \Closure $callback
     * @return JSSchema
     */
    public function on(string $action, \Closure $callback) : JSSchema;

    /**
     * @param string $innerText
     * @return JSSchema
     */
    public function text(string $innerText) : JSSchema;

    /**
     * @return string
     */
    public function __toString() : string;
}