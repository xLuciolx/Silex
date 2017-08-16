<?php

namespace TechNews\Controller\Provider;
use Silex\Api\ControllerProviderInterface;


class AdminControllerProvider implements ControllerProviderInterface {

  public function connect(\Silex\Application $app){

    $adminControllers = $app['controllers_factory'];

      $adminControllers
        ->get('/connexion', 'TechNews\Controller\AdminController::connexionAction')
        ->bind('technews_adminconnexion');

      $adminControllers
        ->get('/inscription', 'TechNews\Controller\AdminController::inscriptionAction')
        ->bind('technews_admininscription');

        $adminControllers
          ->get('/ajouter/article', 'TechNews\Controller\AdminController::addArticleAction')
          ->bind('technews_admin');

    return $adminControllers;
  }
}
