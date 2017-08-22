<?php

namespace TechNews\Controller;
use Silex\Application;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\NotBlank;

class AdminController {

  public function addArticleAction(Application $app){

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
                                ->getForm();
    /*Affichage*/
    return $app['twig']->render('admin/addArticle.html.twig', ['form'=>$form->createView()]);
  }
}
