<?php

namespace TechNews\Controller\Provider;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use TechNews\Model\Auteur;

class AuteurProvider implements UserProviderInterface{

  private $_db;

  /**
   * Récuperation de l'instance de la BDD
   * @param mixed $db (Idiorm ou DBAL)
   */
  public function __construct($db){
    $this->_db = $db;
  }


  public function supportsClass($class){
    return $class === 'TechNews\Model\Auteur';
  }

  /**/
  public function refreshUser(UserInterface $auteur){
    /*On s'assure de bien avoir un objet de la classe Auteur*/
    if (!$auteur instanceof Auteur) {
      throw new UnsupportedUserException(sprintf('Les instances de "%s" ne sont pas autorisés.' , get_class( $auteur)));
    }

    return $this->loadUserByUsername($auteur->getUsername());
  }

  public function loadUserByUsername($EMAILAUTEUR){
    $auteur = $this->_db->for_table('auteur')
                        ->where('EMAILAUTEUR', $EMAILAUTEUR)
                        ->find_one();
    if (empty($auteur)) {
      throw new UsernameNotFoundException(sprintf('Cet utilisateur "%s" n\'existe pas.1', $EMAILAUTEUR));
    }

    return new Auteur($auteur->IDAUTEUR,
                      $auteur->NOMAUTEUR,
                      $auteur->PRENOMAUTEUR,
                      $auteur->EMAILAUTEUR,
                      $auteur->MDPAUTEUR,
                      $auteur->ROLESAUTEUR);
  }
}
