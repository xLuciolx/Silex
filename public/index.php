<?php
use Cocur\Slugify\Bridge\Silex2\SlugifyServiceProvider;
use Silex\Application;
use Silex\Provider\AssetServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
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

/*activation de twig*/
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => [
      __DIR__.'/../ressources/views',
      __DIR__.'/../ressources/layout'
      ],
));

/*Activation de Slugify (https://github.com/cocur/slugify)*/
$app->register(new SlugifyServiceProvider());

/*récuperation des categories*/
$app['technews_categories'] = function() use($app){
  return $app['db']->fetchAll('SELECT * FROM categorie');
};

/*recuperation des tags*/
$app['technews_tags'] = function () use($app){
  return $app['db']->fetchAll('SELECT * FROM tags');
};

/*récuperation des derniers articles*/
$app['lastArticles'] = function () use ($app){
  return $app['db']->fetchAll('SELECT * FROM view_articles ORDER BY DATECREATIONARTICLE DESC LIMIT 5');
};

/*récupération des articles spéciaux*/
$app['specialArticles'] = function () use($app){
  return $app['db']->fetchAll('SELECT * FROM view_articles WHERE SPECIALARTICLE = 1');
};

/*activation de Asset*/
$app->register(new AssetServiceProvider());

/*activation Doctrine DBAL*/
$app->register(new DoctrineServiceProvider(),array(
    'db.options' => array(
      'driver'   => 'pdo_mysql',
      'host'     => 'localhost',
      'dbname'   => 'technews2',
      'user'     => 'root',
      'password' => ''

    ),
));




/*execution de l'application*/
$app->run();
