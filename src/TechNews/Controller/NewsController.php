<?php
namespace TechNews\Controller;
use Silex\Application;

class NewsController {

  /**
   * Affichage de la page d'accueil
   * @param Application $app
   * @return Application
   */
  public function indexAction(Application $app){

    //Connexion BDD et récup des articles
    $articles = $app['db']
                ->fetchAll('SELECT * FROM view_articles');

    // Récup des spotlights
    $spotlight = $app['db']
                 ->fetchAll('SELECT * FROM view_articles
                  WHERE SPOTLIGHTARTICLE = 1');

    return $app['twig']->render('index.html.twig', [
      'articles'  => $articles,
      'spotlight' => $spotlight
    ]);
  }

  /**
   * Affichage de la page catégorie
   * @param Application $app
   * @param string $libelleCategorie
   * @return Application
   */
  public function categorieAction(Application $app, $libelleCategorie){

    $articlesCategorie = $app['db']
                         ->fetchAll("SELECT * FROM view_articles
                                    WHERE LIBELLECATEGORIE = '$libelleCategorie' ");

    return $app['twig']->render('categorie.html.twig', [
      'articlesCategorie' => $articlesCategorie,
      'libelle' => $libelleCategorie
    ]);
    }

  /**
   * Affichage de la page article
   * @param Application $app
   * @param string $libelleCategorie
   * @param string $slugArticle
   * @param int $idArticle
   * @return Application
   */
  public function articleAction(Application $app, $libelleCategorie, $slugArticle, $idArticle){
    //ex. de route: index.php/business/une-formation-innovante-a-villefranche_0999999.html

    //récuperation de l'article
    $article      = $app['db']
                  ->fetchAssoc("SELECT * FROM view_articles
                                WHERE IDARTICLE = '$idArticle'");

    //récuperation des suggestions
    $suggestions  = $app['db']
                  ->fetchAll("SELECT * FROM view_articles
                              WHERE LIBELLECATEGORIE ='$libelleCategorie'
                              AND NOT IDARTICLE ='$idArticle'
                              ORDER BY IDARTICLE DESC
                              LIMIT 3");

    return $app['twig']->render('article.html.twig', [
      'article' => $article,
      'suggestions' => $suggestions
      ]) ;
  }
}
