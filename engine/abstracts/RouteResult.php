<?php
/**
 * Created by PhpStorm.
 * User: 37498_000
 * Date: 10.06.2016
 * Time: 14:19
 */

namespace engine\abstracts;

/**
 * Class RouteResult
 * @package engine\abstracts
 */
abstract class RouteResult
{
    /**
     * @var string $controller
     */
    protected $_controller;
    /**
     * @var string $method
     */
    protected $_method = "index";
    /**
     * @var null|array $parameters
     */
    protected $_parameters = [];
    /**
     * @var string $routeKey
     */
    protected $_url;
    /**
     * @var string|array|callable
     */
    protected $_value;

    /**
     * RouteResult constructor.
     * @param string $url
     * @param $value
     */
    public function __construct($url, $value)
    {
        $this->_url = !empty($prefix)
            ? str_replace("{$prefix}/","",$url)
            : $url;
        $this->_value = $value;
    }

    /**
     * @return void
     */
    public abstract function execute();

    /**
     * @return array
     */
    public function get()
    {
        $this->execute();
        return [
            "controller" => $this->_controller,
            "method" => $this->_method,
            "parameters" => $this->_parameters,
        ];
    }
}