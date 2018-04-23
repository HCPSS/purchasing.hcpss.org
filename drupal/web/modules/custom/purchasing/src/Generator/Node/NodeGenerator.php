<?php

namespace Drupal\purchasing\Generator\Node;

use Drupal\node\Entity\Node;

abstract class NodeGenerator {

  /**
   * Delete vendors.
   */
  public static function deleteAllOfBundle($bundle) {
    $nids = \Drupal::entityQuery('node')
      ->condition('type', $bundle)
      ->execute();

    $nodes = Node::loadMultiple($nids);

    foreach ($nodes as $node) {
      $node->delete();
    }
  }

  /**
   * Get the term id of a category given it's name.
   *
   * @param string $name
   *   The category name.
   * @return int
   *   The term id.
   */
  protected static function getCategoryIdFromName($name) {
    $nids = \Drupal::entityQuery('taxonomy_term')
    ->condition('vid', 'categories')
    ->condition('name', $name)
    ->execute();

    return array_shift($nids);
  }
}
