<?php

namespace Drupal\purchasing\Routing;

use Symfony\Component\Routing\RouteCollection;
use Drupal\Core\Routing\RouteSubscriberBase;

class RouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  public function alterRoutes(RouteCollection $collection) {
    if ($route = $collection->get('node.add')) {
      $route->setDefault('_controller', '\Drupal\purchasing\Controller\NodeController::add');
    }
  }
}
