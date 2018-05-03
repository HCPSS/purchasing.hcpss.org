<?php

namespace Drupal\purchasing\Generator\Node;

use Drupal\purchasing\Generator\EntityGeneratorInterface;
use Drupal\node\Entity\Node;

class PageGenerator extends NodeGenerator implements EntityGeneratorInterface {

  private $data;
  public function __construct(array $data) {
    $this->data = $data;
  }

  protected static function getBundle() {
    return 'page';
  }

  /**
   * Create an award node from an array of values.
   *
   * @param array $data
   *   Award values
   * @return Node
   */
  public function generate() {
    $page = Node::create([
      'type' => static::getBundle(),
      'uid' => \Drupal::currentUser()->id(),
      'title' => $this->data['title'],
      'body' => [
        'value' => $this->data['body'],
        'format' => 'basic_html',
      ],
      'path' => ['alias' => $this->data['url']],
    ]);

    $page->save();

    return $page;
  }
}
