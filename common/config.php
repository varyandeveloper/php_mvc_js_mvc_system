<?php

\engine\config\AppConfig::setBaseUrl(SERVER."vs/");

\engine\config\SessionConfig::__init__([
    "encryptionKey"=>"lIcM8vW0wEDKC__VS__576191423075c",
    "expireTime"=>0
]);

\engine\config\ValidatorConfig::__init__([

]);

\engine\config\DatabaseConfig::__init__([
    "default"=>[
        "drive"         => "mysql",
        "host"          => "localhost",
        "user"          => "root",
        "pass"          => "",
        "port"          => null,
        "name"          => "vs_system_db",
        "charset"       => "utf8",
        'collation'     => 'utf8_unicode_ci',
        "prefix"        => "vs_"
    ]
]);


\engine\config\DatabaseConfig::setDefault("server");

\engine\objects\Session::__init__();

\engine\config\AuthConfig::setCredentials([
    'username'=>[
        'username',
        'email'
    ],
    "password"=>"password"
]);

\engine\config\CacheConfig::__init__([
    'on'=>false,
    'time'=>600,
]);

if(\engine\objects\Session::get("_language"))
    \engine\config\AppConfig::setCurrentLanguage(\engine\objects\Session::get("_language"));