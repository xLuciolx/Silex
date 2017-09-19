<?php

namespace TechNews\Controller;
use TechNews\Traits\Shortcut;
use Silex\Application;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\HttpFoundation\Request;


class AdminController {

  use Shortcut;

  public function addArticleAction(Application $app, Request $request){

    /*recuperation de la liste des auteurs*/
    $auteurs = function () use($app){
    $auteurs = $app['idiorm.db']->for_table('auteur')
                                  ->find_result_set();
    /*formatage de l'affichage du select*/
    $array = [];
    foreach ($auteurs as $auteur) {
      $array[$auteur->PRENOMAUTEUR.' '.$auteur->NOMAUTEUR] = $auteur->IDAUTEUR;
    }
    return $array;
  };

    /*liste de categories*/
    $categories = function () use($app){
    $categories = $app['idiorm.db']->for_table('categorie')
                                     ->find_result_set();
    /*formatage de l'affichage du select*/
    $array = [];
    foreach ($categories as $categorie) {
      $array[$categorie->LIBELLECATEGORIE] = $categorie->IDCATEGORIE;
    }
    return $array;
  };


    /*creation d'un formulaire pour ajout d'un article*/
    $form = $app['form.factory']->createBuilder(FormType::class)
                                ->add('TITREARTICLE', TextType::class, array(
                                  'required'     => true,
                                  'label'        => false,
                                  'constraints'  => array(
                                    new NotBlank()
                                  ),
                                  'attr'          => array(
                                    'class'       => 'form-control',
                                    'placeholder' => 'Titre de l\'article'
                                  )
                                ))
                                ->add('IDAUTEUR', ChoiceType::class, array(
                                  'choices'   => $auteurs(),
                                  'expanded'  => false,  /*liste déroulée*/
                                  'multiple'  => false,  /*choix multiple*/
                                  'label'     => false,
                                  'attr'      => array(
                                    'class'   => 'form-control'
                                  )
                                ))
                                ->add('IDCATEGORIE', ChoiceType::class, array(
                                  'choices'   => $categories(),
                                  'expanded'  => false,  /*liste déroulée*/
                                  'multiple'  => false,  /*choix multiple*/
                                  'label'     => false,
                                  'attr'      => array(
                                    'class'   => 'form-control'
                                  )
                                ))
                                ->add('CONTENUARTICLE', TextareaType::class, array(
                                  'required'      => true,
                                  'label'         => false,
                                  'constraints'   => array(new NotBlank()),
                                  'attr'          => array(
                                    'class'       => 'form-control'
                                  )
                                ))
                                ->add('FEATUREDIMAGEARTICLE', FileType::class, array(
                                  'required' => true,
                                  'label'    => false,
                                  'attr'     => array(
                                    'class'  => 'dropify'
                                  )
                                ))
                                ->add('SPOTLIGHTARTICLE', CheckboxType::class, array(
                                  'required' => false,
                                  'label'    => false,
                                ))
                                ->add('SPECIALARTICLE', CheckboxType::class, array(
                                  'required' => false,
                                  'label'    => false,
                                ))
                                ->add('submit', SubmitType::class, array(
                                  'label' => 'Publier'
                                ))
                                ->getForm();
    /*traitement du formulaire*/
    $form->handleRequest($request);

    if ($form->isValid()) {
      /*recuperation des données*/
      $article = $form->getData();

      /*recup image*/
      $image       = $article['FEATUREDIMAGEARTICLE'];
      $upload_path = PATH_PUBLIC.'/assets/images/product/';
      $image->move($upload_path, $this->generateSlug($article['TITREARTICLE']) . '.jpg');

      /*insertion BDD*/
      $articleDb   = $app['idiorm.db']->for_table('article')
                                      ->create();
      $categorieDb = $app['idiorm.db']->for_table('categorie')
                                      ->find_one($article['IDCATEGORIE']);
      /*association des colonnes BDD avec nos valeurs du formulaire*/
      $articleDb->IDAUTEUR             = $article['IDAUTEUR'];
      $articleDb->IDCATEGORIE          = $article['IDCATEGORIE'];
      $articleDb->TITREARTICLE         = $article['TITREARTICLE'];
      $articleDb->CONTENUARTICLE       = $article['CONTENUARTICLE'];
      $articleDb->SPECIALARTICLE       = $article['SPECIALARTICLE'];
      $articleDb->SPOTLIGHTARTICLE     = $article['SPOTLIGHTARTICLE'];
      $articleDb->FEATUREDIMAGEARTICLE = $this->generateSlug($article['TITREARTICLE']).'.jpg';

      /*insertion BDD*/
      $articleDb->save();

      /*redirection*/
      return $app->redirect($app['url_generator']->generate('technews_article', [
        'libelleCategorie' => strtolower($categorieDb->LIBELLECATEGORIE),
        'idArticle'        => $articleDb->IDARTICLE,
        'slugArticle'      => $this->generateSlug($article['TITREARTICLE'])
      ]));
    }

    /*Affichage*/
    return $app['twig']->render('admin/addArticle.html.twig', ['form'=>$form->createView()]);
  }
}
