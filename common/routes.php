<?php
/**
 * Router starts here
 * You can use it ase one big object or by parts
 * This is the one big object implementation type
 */

$router = \engine\objects\Router::start();

/**
 * Default and Error routes
 */
$router->defaultRoute("WelcomeController");
$router->errorRoute("ErrorController");