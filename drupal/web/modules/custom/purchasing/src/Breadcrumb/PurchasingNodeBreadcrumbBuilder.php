<?php

namespace Drupal\purchasing\Breadcrumb;

use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Link;
use Drupal\node\NodeInterface;

class PurchasingNodeBreadcrumbBuilder extends SegmentBreadcrumb implements BreadcrumbBuilderInterface {

  /**
   * {@inheritDoc}
   * @see BreadcrumbBuilderInterface::applies()
   */
  public function applies(RouteMatchInterface $attributes) {
    if ($node = $attributes->getParameter('node')) {
      $bundles = [
        'solicitation', 'contract', 'quote', 'award', 'priced_line_item',
        'discount_line_item', 'partner_line_item',
      ];

      if (in_array($node->bundle(), $bundles)) {
        return TRUE;
      }
    }

    return FALSE;
  }

  /**
   * {@inheritDoc}
   * @see \Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface::build()
   */
  public function build(RouteMatchInterface $route_match) {
    $breadcrumb = new Breadcrumb();
    $breadcrumb->addCacheContexts(['url.path.parent']);

    $segments = [];
    $segments[self::POSITION_HOME] = Link::createFromRoute('Home', '<front>');

    if ($node = $route_match->getParameter('node')) {
      $this->addViewSegment($node, $segments);
    }

    ksort($segments);
    $breadcrumb->setLinks($segments);

    return $breadcrumb;
  }
}
