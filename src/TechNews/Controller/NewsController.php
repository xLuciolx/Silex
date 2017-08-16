<?php
namespace TechNews\Controller;

class NewsController {

  /**
   * Affichage de la page d'accueil
   * @return Symphony\Component\HttpFoundation\Response
   */
  public function indexAction(){
    return '<h1>Accueil</h1>';
  }

  /**
   * Affichage de la page catégorie
   * @param string $libelleCategorie
   * @return Symphony\Component\HttpFoundation\Response
   */
  public function categorieAction($libelleCategorie){
    return "<h1>Categorie - $libelleCategorie</h1>";
    }

  /**
   * Affichage de la page article
   * @param string $libelleCategorie
   * @param string $slugArticle
   * @param int $idarticle
   * @return Symphony\Component\HttpFoundation\Response
   */
  public function articleAction($libelleCategorie, $slugArticle, $idArticle){
    //ex. de route: index.php/business/une-formation-innovante-a-villefranche_0999999.html
    return "<h1>Article n° $idArticle - $slugArticle</h1>";
  }
}
