<?php


use Silex\Application;
use Symfony\Component\HttpFoundation\Response;

/*récupération de l'autoload de Composer*/
/*******************************************************************************
 * Il permet de charger toutes les dépendances du projet de façon automatique. *
 * E.g: Symfony, Pimple, Silex,..                                              *
 *******************************************************************************/
require_once __DIR__ . '/vendor/autoload.php';

/*instanciation de l'application Silex (même chose que $core = new Core() dans technews)*/
$app = new Application();

/*activation du débuggage*/
$app['debug'] = true;

/*Association de la route '/' à la fonction anonyme qui renvoie le résultat à afficher*/
$app->get('/', function(){
  return '<h1>Page Accueil !</h1>';
});

/*match permet de ne pas preciser de méthode http (get, post, ...)*/
/*method permet de n'autoriser que certaines methodes http*/
/*value definie une valeur par défaut*/
$app['prenom.default'] = function(){
  return 'Loïc';
};

/**
 * Dans silex:
 * 1. si la route est detectée grâce à match
 * 2. alors la fonction anonyme (closure - controller) est executée
 * 3. une réponse HTML et un code HTTP sont renvoyés au navigateur
 */
$app->match('/hello/{prenom}', function ($prenom){
  //une 'Response' Silex associe à notre 'Response' un statut http
  return new Response("<h1>Hello $prenom !</h1>");
})->method('GET|POST')->value('prenom', $app['prenom.default']);

/*execution de Silex*/
$app->run();
