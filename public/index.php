<?php

/*
 *---------------------------------------------------------------
 * Define DS as DIRECTORY_SEPARATOR constant
 *---------------------------------------------------------------
 */
define("DS", DIRECTORY_SEPARATOR);

/*
 *---------------------------------------------------------------
 * CONFIG.JSON File
 *---------------------------------------------------------------
 *
 * This variable must contain the name of your "config.json" file.
 * Include the path if the folder is not in the same directory
 * as this file.
 */
$JSON_CONFIG = "../config.json";

/*
 *---------------------------------------------------------------
 * ENGINE FOLDER NAME
 *---------------------------------------------------------------
 *
 * This variable must contain the name of your "engine" folder.
 * Include the path if the folder is not in the same directory
 * as this file.
 */
$ENGINE_PATH = "../engine";

/*
 *---------------------------------------------------------------
 * RELEASE FOLDER NAME
 *---------------------------------------------------------------
 *
 * If you want this front controller to use a different "release"
 * folder than the default one you can set its name here. The folder
 * can also be renamed or relocated anywhere on your getServer.
 * If you want to rename folder name be sure to correct namespaces inside
 * this folder
 *
 */
$RELEASE_PATH = "../release";

/*
 *---------------------------------------------------------------
 * COMMON FOLDER NAME
 *---------------------------------------------------------------
 *
 * This variable must contain the name of your "common" folder.
 * This folder contains controllers,models,etc. for your releases
 * Include the path if the folder is not in the same directory
 * as this file.
 */
$COMMON_PATH = "../common";

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) ? "https://" : "http://";
$domainName = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] . '/' : 'localhost/';

if(!realpath($ENGINE_PATH))
    exit("Path {$ENGINE_PATH} dose`nt exists");

if(!realpath($RELEASE_PATH))
    exit("Path {$RELEASE_PATH} dose`nt exists");

if(!realpath($COMMON_PATH))
    exit("Path {$COMMON_PATH} dose`nt exists");

define("CONFIG", $JSON_CONFIG);
define("ENGINE", $ENGINE_PATH . DS);
define("RELEASE", $RELEASE_PATH . DS);
define("COMMON", $COMMON_PATH . DS);
define("SERVER", $protocol . $domainName);
define("EXT", ".php");

require_once ENGINE."start".EXT;

