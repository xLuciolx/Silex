<?php

namespace TechNews\Model;
use Symfony\Component\Security\Core\User\UserInterface;

class Auteur implements UserInterface {

  private $IDAUTEUR;
  private $NOMAUTEUR;
  private $PRENOMAUTEUR;
  private $EMAILAUTEUR;
  private $MDPAUTEUR;
  private $ROLESAUTEUR;

  /**
   * Creation d'un objet Auteur
   * @param int $IDAUTEUR
   * @param string $NOMAUTEUR
   * @param string $PRENOMAUTEUR
   * @param string $EMAILAUTEUR
   * @param string $MDPAUTEUR
   * @param string $ROLESAUTEUR
   */
  public function __construct(
    $IDAUTEUR,
    $NOMAUTEUR,
    $PRENOMAUTEUR,
    $EMAILAUTEUR,
    $MDPAUTEUR,
    $ROLESAUTEUR
  ) {
    $this->IDAUTEUR      = $IDAUTEUR;
    $this->NOMAUTEUR     = $NOMAUTEUR;
    $this->PRENOMAUTEUR  = $PRENOMAUTEUR;
    $this->EMAILAUTEUR   = $EMAILAUTEUR;
    $this->MDPAUTEUR     = $MDPAUTEUR;
    $this->ROLESAUTEUR[] = $ROLESAUTEUR;
  }

  /*getters*/





  /*Méthodes héritées de l'interface*/
  public function getPassword(){
    return $this->MDPAUTEUR;
  }

  public function eraseCredentials(){}

  public function getSalt(){
    return null;
  }

   public function getRoles(){
    return $this->ROLESAUTEUR;
  }

   public function getUsername(){
    return $this->EMAILAUTEUR;
   }

}
