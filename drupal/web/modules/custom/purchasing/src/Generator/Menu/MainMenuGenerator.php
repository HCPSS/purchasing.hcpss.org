<?php

namespace Drupal\purchasing\Generator\Menu;

use Drupal\purchasing\Generator\EntityGeneratorInterface;
use Drupal\menu_link_content\Entity\MenuLinkContent;

class MainMenuGenerator implements EntityGeneratorInterface {
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
  public static function createFromArray(array $data) {
    $url = strpos($data['url'], '/') === 0 ? 'internal:' . $data['url'] : $data['url'];

    $item = MenuLinkContent::create([
      'title' => $data['title'],
      'link' => ['uri' => $url],
      'menu_name' => 'main',
      'expanded' => TRUE,
      'weight' => $data['weight'],
    ]);

    if (!empty($data['parent'])) {
      $mids = \Drupal::entityQuery('menu_link_content')
        ->condition('menu_name', 'main')
        ->condition('link.uri', 'internal:' . $data['parent'])
        ->execute();

      $parent = MenuLinkContent::load(array_shift($mids));

      $item->parent = $parent->getPluginId();
    }

    $item->save();
  }
}
