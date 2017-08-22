<?php

use Cocur\Slugify\Bridge\Silex2\SlugifyServiceProvider;
use Silex\Provider\CsrfServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\AssetServiceProvider;
use Silex\Provider\LocaleServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use TechNews\Controller\Provider\AuteurProvider;
use TechNews\Extension\TechNewsTwigExtension;
use Idiorm\Silex\Provider\IdiormServiceProvider;

/*mode debug*/
$app['debug'] = true;

/*gestion de nos controller via ControllerProvider*/
require PATH_SRC.'/routes.php';

/*activation de twig*/
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => [
      PATH_VIEWS,
      PATH_RESSOURCES.'/layout'
      ],
));

/*Activation de Slugify (https://github.com/cocur/slugify)*/
$app->register(new SlugifyServiceProvider());

/*Ajout des extensions TechNews pour Twig*/
$app->extend('twig', function($twig, $app){
  $twig->addExtension(new TechNewsTwigExtension());
  return $twig;
});

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

$app->register(new FormServiceProvider());
$app->register(new CsrfServiceProvider());
$app->register(new LocaleServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new TranslationServiceProvider(), array(
  'translator.domains' => array()
));

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

/*integration Idiorm*/
$app->register(new IdiormServiceProvider(), array(
  'idiorm.db.options'     => array(
    'connection_string'   => 'mysql:host=localhost; dbname=technews2',
    'username'            => 'root',
    'password'            => '',
    'id_column_overrides' => array(
      'view_articles'     => 'IDARTICLE'
    )

  )
));

/*Activation HttpFragmentServiceProvider*/
/*voir doc silex*/
$app->register(new HttpFragmentServiceProvider());

$app->register(new SessionServiceProvider());

/*securisation*/
$app->register(new SecurityServiceProvider(), array(
  'security.firewalls' => array(
    'main' => array(
      'pattern'   => '^/',  /*perimetre d'action du firewall*/
      'http'      => true,
      'anonymous' => true,   /*autorisation des utilisateurs anonymes*/
      'form'      => array(
        'login_path' => '/connexion',
        'check_path' => '/connexion/login_check'  /*generer automatiquement par silex*/
      ),
      'logout'    => array(
        'logout_path' => '/deconnexion'
      ),
      'users' => function () use($app){
        return new AuteurProvider($app['idiorm.db']);
        } /*ou aller chercher les differents utilisateurs*/
    )
  ),
  'security.access_rules' => array(
    /*regles d'acces*/
    array(
      '^/admin', 'ROLE_ADMIN', null
    )
  ),
  'security.role_hierarchy' => array(
    'ROLE_ADMIN' => array(
      'ROLE_AUTEUR'
    )
  )
));

/*encodage mot de passe*/
$app['security.encoder.digest'] = function () use($app){
  return new MessageDigestPasswordEncoder('sha1', false, 1);
};

$app['security.default_encoder'] = function () use($app){
  return $app['security.encoder.digest'];
};

/*on retourne $app*/
return $app;
