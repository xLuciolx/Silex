<?php

/**
 * Les traits sont un mécanisme de réutilisation de code dans un language à héritage simple tel que PHP.
 * Un trait tente de reduire certaines limites de l'héritage simple, en autorisant le développeur à réutiliser un
 * certain nombre de méthodes dans les classes indépendantes.
 *
 * La sémantique entre les classes et les traits réduit la complexité et évite les problèmes typiques de l'héritage multiple.
 *
 * Un trait est semblable à une classe mais il ne sert qu'a grouper des fonctionnalités d'une manière intéressante.
 * Il n'est pas possible d'instancier un trait en lui même, c'est un ajout à l'héritage traditionnel qui autorise la composition horizontale
 * de comportements, càd l'utilisation de méthodes de classe sans besoin d'héritage.
 */

namespace TechNews\Traits;

/**
 *
 */
trait Shortcut
{
  /*Créer un SLUG à partir du titre d'un article*/
  /*https://stackoverflow.com/questions/2955251/php-function-to-make-slug-url-string*/

   public function generateSlug($text)
  {

    // replace non letter or digits by -
  $text = preg_replace('~[^\pL\d]+~u', '-', $text);

  // transliterate
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

  // remove unwanted characters
  $text = preg_replace('~[^-\w]+~', '', $text);

  // trim
  $text = trim($text, '-');

  // remove duplicate -
  $text = preg_replace('~-+~', '-', $text);

  // lowercase
  $text = strtolower($text);

  if (empty($text)) {
    return 'n-a';
  }

  return $text;
  }
}
