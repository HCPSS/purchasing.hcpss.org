<?php

namespace Drupal\purchasing\Generator\Term;

use Drupal\taxonomy\Entity\Term;
use Drupal\purchasing\Generator\EntityGeneratorInterface;

class CategoryGenerator implements EntityGeneratorInterface {

  private $data;

  public function __construct(array $data) {
    $this->data = $data;
  }

  /**
   * Delete terms.
   */
  public static function deleteAll() {
    $tids = \Drupal::entityQuery('taxonomy_term')
      ->condition('vid', 'categories')
      ->execute();

    $terms = Term::loadMultiple($tids);

    foreach ($terms as $term) {
      $term->delete();
    }
  }

  /**
   * Create a Category term from an array of values.
   *
   * @param array $data
   *   Category values.
   * @param Term $parent
   *   The parent category
   * @return Term
   */
  public function generate() {
    $category = Term::create([
      'vid' => 'categories',
      'name' => $this->data['name'],
      'description' => [
        'value' => $this->data['description'],
        'format' => 'basic_html',
      ],
    ]);

    $category->save();

    return $category;
  }
}
