<?php
namespace TechNews\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class NewsController {

  /**
   * Affichage de la page d'accueil
   * @param Application $app
   * @return Application
   */
  public function indexAction(Application $app){

    //Connexion BDD et récup des articles
    $articles  = $app['db']
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
      'libelle'           => $libelleCategorie
    ]);
    }

  /**
   * Affichage de la page article
   * @param Application $app
   * @param string $slugArticle
   * @param string $libelleCategorie
   * @param int $idArticle
   * @return Application
   */
  public function articleAction(Application $app, $slugArticle, $libelleCategorie, $idArticle){
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
      'article'     => $article,
      'suggestions' => $suggestions
      ]) ;
  }

  public function menu($active, Application $app){
    //recuperation des categorie
    $categories = $app['idiorm.db']->for_table('categorie')->find_result_set();

    //transmission à la vue
    return $app['twig']->render('menu.html.twig', [
      'categories' => $categories,
      'active' => $active
    ]);
  }

  public function auteurAction(Application $app, $idAuteur){
    //récupération des articles d'un auteur
    $articlesAuteur = $app['idiorm.db']
                      ->for_table('view_articles')
                      ->where('IDAUTEUR', $idAuteur)
                      ->find_result_set();

    //transmission à la vue
    return $app['twig']->render('auteur.html.twig', ['articles' => $articlesAuteur]);
  }

  /**
   * Génération de la side bar dans le layout
   * public function sidebar(Application $app){
   *   //recup info
   *   $sidebar = $app['idiorm.db']->for_table('view_articles')
   *                               ->order_by_desc('DATECREATIONARTICLE')
   *                               ->limit(5),
   *                               ->find_result_set();
   *    $special = $app['idiorm.db']->for_table('view_articles')
   *                                ->where('SPECIALARTICLE', 1)
   *                                ->find_result_set();
   *    //transmission
   *    return $app['twig']->render('sidebar.html.twig', [
   *      'sidebar' => $sidebar,
   *      'special' => $special
   *      ]);
   * }
   */

  /**
   * @param  Application $app
   * @param  Request $request
   */
   public function connexionAction(Application $app, Request $request){
     return $app['twig']->render('connexion.html.twig', [
       'error'         => $app['security.last_error']($request),
       'last_username' => $app['session']->get('_security.last_username')
     ]);
   }

  /*Deconnexion*/
   public function deconnexionAction(Application $app){
       $app['session']->clear();
      return $app->redirect($app['url_generator']
                 ->generate('technews_home'));
   }

   public function inscriptionAction(Application $app){
     return $app['twig']->render('inscription.html.twig');
   }

  /* Traitement POST du formulaire d'inscription*/
   public function inscriptionPost(Application $app, Request $request){
      /*verif et sécurisation des donnees POST*/

      /*connexion BDD*/
      $auteur = $app['idiorm.db']->for_table('auteur')
                                 ->create();
      /*affectation des valeurs*/
      $auteur->PRENOMAUTEUR  = $request->get(('PRENOMAUTEUR'));
      $auteur->NOMAUTEUR     = $request->get(('NOMAUTEUR'));
      $auteur->EMAILAUTEUR   = $request->get(('EMAILAUTEUR'));
      $auteur->MDPAUTEUR     = $app['security.encoder.digest']->encodePassword($request->get('MDPAUTEUR'), '');
      $auteur->ROLESAUTEUR   = $request->get(('ROLESAUTEUR'));

      /*persistance en BDD*/
      $auteur->save();

      /*on envoie un email de confirmation*/
      /*notification à l'administrateur*/
      /*on redirige sur la page de connexion*/
      return $app->redirect('connexion?inscription=success');
   }
}
