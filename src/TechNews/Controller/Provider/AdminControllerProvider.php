<?php

namespace TechNews\Controller\Provider;
use Silex\Api\ControllerProviderInterface;


class AdminControllerProvider implements ControllerProviderInterface {

  public function connect(\Silex\Application $app){

    $adminControllers = $app['controllers_factory'];

      $adminControllers
        ->match('/ajouter/article', 'TechNews\Controller\AdminController::addArticleAction')
        ->method('GET|POST')
        ->bind('technews_addArticle');

    return $adminControllers;
  }
}
