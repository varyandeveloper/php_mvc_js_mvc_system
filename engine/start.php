<?php

/*
 *---------------------------------------------------------------
 * Require PSR-4 autoload class
 *---------------------------------------------------------------
 */
require_once ENGINE . "Psr4Autoload" . EXT;
/*
 *---------------------------------------------------------------
 * Creating a new instance for Psr4Autoload class
 *---------------------------------------------------------------
 */
$loader = new \Engine\Psr4Autoload;
/*
 *---------------------------------------------------------------
 * ADD engine namespace to autoload class
 *---------------------------------------------------------------
 */
$loader->addNamespace("engine", realpath(ENGINE));
/*
 *---------------------------------------------------------------
 * ADD release namespace to autoload class
 *---------------------------------------------------------------
 */
$loader->addNamespace("release", realpath(RELEASE));
/*
 *---------------------------------------------------------------
 * ADD common namespace to autoload class
 *---------------------------------------------------------------
 */
$loader->addNamespace("common", realpath(COMMON));
/*
 *---------------------------------------------------------------
 * Namespace registration
 *---------------------------------------------------------------
 *
 * After defining folders as namespaces
 * We have to register them for future using
 *
 */
$loader->register();

/*
 *---------------------------------------------------------------
 * RUN ENGINE
 *---------------------------------------------------------------
 *
 * Now everything is ready we can run engine
 *
 */
try {
    \engine\Engine::getInstance()->run();
} catch (Throwable $ex) {
    var_dump([
        "message" => $ex->getMessage(),
        "file" => $ex->getFile(),
        "line" => $ex->getLine(),
    ]);
}