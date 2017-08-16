<?php
use Silex\Application;
use TechNews\Controller\Provider\NewsControllerProvider;
use TechNews\Controller\Provider\AdminControllerProvider;

/*autoloader*/
require_once __DIR__ . '/../vendor/autoload.php';

/*Instanciation Application*/
$app = new Application();

/*mode debug*/
$app['debug'] = true;

/*gestion de nos controller via ControllerProvider*/
$app->mount('/', new NewsControllerProvider());
$app->mount('/admin', new AdminControllerProvider());

/*execution de l'application*/
$app->run();
