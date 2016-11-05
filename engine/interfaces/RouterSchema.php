<?php
/**
 * Created by PhpStorm.
 * User: 37498_000
 * Date: 10.06.2016
 * Time: 12:40
 */

namespace engine\interfaces;

/**
 * Interface RouterSchema
 * @package engine\interfaces
 */
interface RouterSchema extends EngineSchema
{
    /**
     * @param string $key
     * @param callable $callback
     * @return RouterSchema
     */
    public function prefix(string $key,callable $callback) : RouterSchema;

    /**
     * @return RouterSchema
     */
    public function clearPrefix() : RouterSchema;
    
    /**
     * @param string $key
     * @param string|array|callable $destination
     * @return RouterSchema
     */
    public function getAjax(string $key,$destination) : RouterSchema;

    /**
     * @param string $key
     * @param string|array|callable $destination
     * @return RouterSchema
     */
    public function anyGetPost(string $key,$destination) : RouterSchema;

    /**
     * @param string $key
     * @param string|array|callable $destination
     * @return RouterSchema
     */
    public function anyGet(string $key,$destination) : RouterSchema;

    /**
     * @param string $key
     * @param string|array|callable $destination
     * @return RouterSchema
     */
    public function anyPost(string $key,$destination) : RouterSchema;

    /**
     * @param string $key
     * @param string|array|callable $destination
     * @return RouterSchema
     */
    public function postAjax(string $key,$destination) : RouterSchema;

    /**
     * @param string $key
     * @param string|array|callable $destination
     * @return RouterSchema
     */
    public function anyAjax(string $key,$destination) : RouterSchema;

    /**
     * @param string $key
     * @param string|array|callable $destination
     * @return RouterSchema
     */
    public function any(string $key,$destination) : RouterSchema;

    /**
     * @param string $key
     * @return string
     */
    public function alias(string $key) : string;

    /**
     * @param string $recognizeName
     * @return RouterSchema
     */
    public function _as(string $recognizeName) : RouterSchema;
    /**
     * @param bool $forView
     * @return string
     */
    public function currentController(bool $forView = true) : string;

    /**
     * @return string
     */
    public function currentNamespace() : string;

    /**
     * @return string
     */
    public function currentMethod() : string;

    /**
     * @return array|null
     */
    public function currentParameters();

    /**
     * @param string|array|callable $destination
     * @return RouterSchema
     */
    public function defaultRoute($destination) : RouterSchema;

    /**
     * @param string|array|callable $destination
     * @return RouterSchema
     */
    public function errorRoute($destination) : RouterSchema;

    /**
     * @param string $pattern
     * @param $destination
     * @return RouterSchema
     */
    public function get(string $pattern, $destination) : RouterSchema;

    /**
     * @param string $pattern
     * @param $destination
     * @return RouterSchema
     */
    public function post(string $pattern, $destination) : RouterSchema;

    /**
     * @param string $pattern
     * @param $destination
     * @return RouterSchema
     */
    public function put(string $pattern, $destination) : RouterSchema;

    /**
     * @param string $pattern
     * @param $destination
     * @return RouterSchema
     */
    public function patch(string $pattern, $destination) : RouterSchema;

    /**
     * @param string $pattern
     * @param $destination
     * @return RouterSchema
     */
    public function delete(string $pattern, $destination) : RouterSchema;
}