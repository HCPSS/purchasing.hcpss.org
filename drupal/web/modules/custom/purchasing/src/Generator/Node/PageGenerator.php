<?php

namespace Drupal\purchasing\Generator\Node;

use Drupal\purchasing\Generator\EntityGeneratorInterface;
use Drupal\node\Entity\Node;

class PageGenerator extends NodeGenerator implements EntityGeneratorInterface {

  /**
   * Delete pages.
   */
  public static function deleteAll() {
    parent::deleteAllOfBundle('page');
  }

  /**
   * Create an award node from an array of values.
   *
   * @param array $data
   *   Award values
   * @return Node
   */
  public static function createFromArray(array $data) {
    $page = Node::create([
      'type' => 'page',
      'uid' => 1,
      'title' => $data['title'],
      'body' => [
        'value' => $data['body'],
        'format' => 'basic_html',
      ],
      'path' => ['alias' => $data['url']],
    ]);

    $page->save();

    return $page;
  }
}
