<?php

namespace TechNews\Controller\Provider;
use Silex\Api\ControllerProviderInterface;

class NewsControllerProvider implements ControllerProviderInterface {

  /**
   * @see \Silex\Api\ControllerProviderInterface::connect()
   * @param  \Silex\Application $app
   */
  public function connect(\Silex\Application $app){

    // Création instance de Silex\ControllerCollection
    // https://silex.symfony.com/api/master/Silex/ControllerCollection.html
    $controllers = $app['controllers_factory'];

      //Page d'Accueil
      $controllers
        //on associe une route à un controller et une action
        ->get('/', 'TechNews\Controller\NewsController::indexAction')
        //en option on donne un nom à la route, qui servira plus tard pour la creation de lien
        ->bind('technews_home');

      //Page categories
      $controllers
        ->get('/categorie/{libelleCategorie}', 'TechNews\Controller\NewsController::categorieAction')
        //spécifie le type de parametre attendu via une regex
        ->assert('libelleCategorie', '[^/]+')
        //valeur par defaut
        ->value('libelleCategorie', 'computer')
        //nom de la route
        ->bind('technews_categorie');

      //Page articles
      $controllers
        ->get('/{libelleCategorie}/{slugArticle}_{idArticle}.html', 'TechNews\Controller\NewsController::articleAction')

        ->assert('idArticle', '\d+')

        // ->value('idArticle', '1')

        ->bind('technews_article');

      //Page auteurs
      $controllers
        ->get('/{slugAuteur}_{idAuteur}.html', 'TechNews\Controller\NewsController::auteurAction')
        ->assert('idAuteur', '\d+')
        ->bind('technews_auteur');

      //Page connexion
      $controllers
        ->get('/connexion', 'TechNews\Controller\NewsController::connexionAction')
        ->bind('technews_connexion');

      // deconnexion
      $controllers
        ->get('/deconnexion', 'TechNews\Controller\NewsController::deconnexionAction')
        ->bind('technews_deconnexion');


      //Page inscription
      $controllers
        ->get('/inscription', 'TechNews\Controller\NewsController::inscriptionAction')
        ->bind('technews_inscription');

      $controllers
      ->post('/inscription', 'TechNews\Controller\NewsController::inscriptionPost')
      ->bind('technews_inscription_post');


    /*retourne la liste des controllers*/
    return $controllers;
  }


}
