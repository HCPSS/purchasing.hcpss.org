<?php

namespace Drupal\purchasing\Generator\Menu;

use Drupal\purchasing\Generator\EntityGeneratorInterface;
use Drupal\menu_link_content\Entity\MenuLinkContent;

class MainMenuGenerator implements EntityGeneratorInterface {

  private $data;

  public function __construct(array $data) {
    $this->data = $data;
  }

  /**
   * {@inheritDoc}
   * @see \Drupal\purchasing\Generator\EntityGeneratorInterface::deleteAll()
   */
  public static function deleteAll() {
    $menu_handler = \Drupal::service('plugin.manager.menu.link');

    // Delete the existing links in the menu we want to rebuild
    $menu_handler->deleteLinksInMenu($menu_name);
  }

  /**
   * Create a menu link from an array of values.
   *
   * @param array $data
   *   link values
   * @return MenuLinkContent
   */
  public function generate() {
    $url = $this->data['url'];
    if (strpos($url, '/') === 0) {
      $url = "internal:$url";
    }

    $item = MenuLinkContent::create([
      'title' => $this->data['title'],
      'link' => ['uri' => $url],
      'menu_name' => 'main',
      'expanded' => TRUE,
      'weight' => $this->data['weight'],
    ]);

    if (!empty($this->data['parent'])) {
      $mids = \Drupal::entityQuery('menu_link_content')
        ->condition('menu_name', 'main')
        ->condition('link.uri', 'internal:' . $this->data['parent'])
        ->execute();

      $parent = MenuLinkContent::load(array_shift($mids));

      $item->parent = $parent->getPluginId();
    }

    $item->save();
  }
}
