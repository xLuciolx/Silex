<?php
namespace TechNews\Controller;
use Silex\Application;

class NewsController {

  /**
   * Affichage de la page d'accueil
   * @param Application $app
   * @return Symphony\Component\HttpFoundation\Response
   */
  public function indexAction(Application $app){

    //Connexion BDD et récup des articles
    $articles = $app['db']->fetchAll('SELECT * FROM view_articles');

    // Récup des spotlights
    $spotlight = $app['db']->fetchAll('SELECT * FROM view_articles WHERE SPOTLIGHTARTICLE = 1');
    return $app['twig']->render('index.html.twig', [
      'articles'  => $articles,
      'spotlight' => $spotlight
    ]);
  }

  /**
   * Affichage de la page catégorie
   * @param Application $app
   * @param string $libelleCategorie
   * @return Symphony\Component\HttpFoundation\Response
   */
  public function categorieAction(Application $app, $libelleCategorie){

    $articlesCategorie = $app['db']->fetchAll("SELECT * FROM view_articles WHERE LIBELLECATEGORIE = '$libelleCategorie' ");

    return $app['twig']->render('categorie.html.twig', ['articlesCategorie' => $articlesCategorie]);
    }

  /**
   * Affichage de la page article
   * @param string $libelleCategorie
   * @param string $slugArticle
   * @param int $idArticle
   * @return Symphony\Component\HttpFoundation\Response
   */
  public function articleAction($libelleCategorie, $slugArticle, $idArticle){
    //ex. de route: index.php/business/une-formation-innovante-a-villefranche_0999999.html
    return "<h1>Article n° $idArticle - $slugArticle</h1>";
  }
}
