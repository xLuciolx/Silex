<?php

/*Définition de constantes*/
define('PATH_ROOT',       dirname(__DIR__));
define('PATH_PUBLIC',     PATH_ROOT.'/public');
define('PATH_SRC',        PATH_ROOT.'/src');
define('PATH_RESSOURCES', PATH_ROOT.'/ressources');
define('PATH_VIEWS',      PATH_RESSOURCES.'/views');
define('PATH_VENDOR',     PATH_ROOT.'/vendor');

/*autoloader*/
require_once PATH_VENDOR . '/autoload.php';

/*Instanciation Application*/
$app = new Silex\Application();

/*importation configuration de l'application*/
require PATH_SRC.'/app.php';

/*execution de l'application*/
$app->run();
