<?php

namespace Drupal\purchasing\Breadcrumb;

use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Link;
use Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;

class PurchasingViewBreadcrumbBuilder extends SegmentBreadcrumb implements BreadcrumbBuilderInterface {

  /**
   * {@inheritDoc}
   * @see BreadcrumbBuilderInterface::applies()
   */
  public function applies(RouteMatchInterface $attributes) {
    $route_name = $attributes->getRouteName();

    if (strpos($route_name, 'view.procurement_methods.') === 0) {
      return TRUE;
    } else if (strpos($route_name, 'view.award.') === 0) {
      return TRUE;
    } else if (strpos($route_name, 'view.line_items.') === 0) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * {@inheritDoc}
   * @see BreadcrumbBuilderInterface::build()
   */
  public function build(RouteMatchInterface $route_match) {
    list($module, $view, $bundle) = explode('.', $route_match->getRouteName());

    $breadcrumb = new Breadcrumb();
    $breadcrumb->addCacheContexts(['url.path.parent']);

    $segments[self::POSITION_HOME] = Link::createFromRoute('Home', '<front>');

    switch ($view) {
      case 'award':
        $solicitaton_ids = \Drupal::entityQuery('node')
          ->condition('field_identifier', $route_match->getParameter('arg_0'))
          ->execute();

        $node = Node::load(array_shift($solicitaton_ids));
        break;
      case 'line_items':
        $award_ids = \Drupal::entityQuery('node')
          ->condition('field_identifier', $route_match->getParameter('arg_1'))
          ->execute();

        $node = Node::load(array_shift($award_ids));
        break;
    }

    if (isset($node) && $node instanceof NodeInterface) {
      $this->addNodeSegment($node, $segments);
    }

    ksort($segments);
    $breadcrumb->setLinks($segments);

    return $breadcrumb;
  }
}
