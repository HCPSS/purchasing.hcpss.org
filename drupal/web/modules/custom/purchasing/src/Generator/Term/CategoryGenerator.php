<?php

namespace Drupal\purchasing\Generator\Term;

use Drupal\taxonomy\Entity\Term;

class CategoryGenerator {

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
  public static function createCategoryFromArray(array $data, Term $parent = NULL) {
    $category = Term::create([
      'vid' => 'categories',
      'name' => $data['name'],
      'description' => [
        'value' => $data['description'],
        'format' => 'basic_html',
      ],
    ]);

    if ($parent) {
      $category->parent = ['target_id' => $parent->id()];
    }

    $category->save();

    if (!empty($data['categories'])) {
      foreach ($data['categories'] as $subData) {
        $subCategory = self::createCategoryFromArray($subData, $category);
      }
    }

    return $category;
  }
}
