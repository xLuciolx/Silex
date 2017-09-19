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

  /**
   * @return mixed
   */
  public function getIDAUTEUR()
  {
    return $this->IDAUTEUR;
  }

  /**
   * @return mixed
   */
  public function getNOMAUTEUR()
  {
    return $this->NOMAUTEUR;
  }

  /**
   * @return mixed
   */
  public function getPRENOMAUTEUR()
  {
    return $this->PRENOMAUTEUR;
  }

  /**
   * @return mixed
   */
  public function getEMAILAUTEUR()
  {
    return $this->EMAILAUTEUR;
  }

  /**
   * @return mixed
   */
  public function getMDPAUTEUR()
  {
    return $this->MDPAUTEUR;
  }

  /**
   * @return mixed
   */
  public function getROLESAUTEUR()
  {
    return $this->ROLESAUTEUR;
  }



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
