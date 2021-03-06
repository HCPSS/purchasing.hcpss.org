<?php

namespace Drupal\purchasing\Generator\Node;

use Drupal\node\Entity\Node;
use Drupal\Core\Url;

class PageGenerator extends NodeGenerator {

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
      'uid' => \Drupal::currentUser()->id() ?: 1,
      'title' => $this->data['title'],
      'body' => [
        'value' => $this->data['body'],
        'format' => 'basic_html',
      ],
      'path' => ['alias' => $this->data['url']],
    ]);

    $page->save();

    if (!empty($this->data['homepage']) && $this->data['homepage']) {
      $url = Url::fromRoute('entity.node.canonical', ['node' => $page->id()])->toString();
      $config = \Drupal::service('config.factory')->getEditable('system.site');
      $config->set('page.front', $url)->save();
    }

    return $page;
  }
}
