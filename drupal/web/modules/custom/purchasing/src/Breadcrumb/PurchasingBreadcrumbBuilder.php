<?php

namespace Drupal\purchasing\Breadcrumb;

use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Link;
use Drupal\node\NodeInterface;

class PurchasingBreadcrumbBuilder implements BreadcrumbBuilderInterface {

  /**
   * {@inheritDoc}
   * @see \Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface::applies()
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
    $breadcrumb->addLink(Link::createFromRoute('Home', '<front>'));
    $breadcrumb->addCacheContexts(['url.path.parent']);

    if ($node = $route_match->getParameter('node')) {
      switch ($node->bundle()) {
        case 'award':
          $this->addProcurement($node, $breadcrumb);
          break;
        case 'priced_line_item':
          $this->addProcurement($node->field_award->entity, $breadcrumb);
          $this->addAward($node, $breadcrumb);
      }
    }

    return $breadcrumb;
  }

  /**
   * Add the award to the breadcrumb given the line item.
   *
   * @param NodeInterface $line_item
   * @param Breadcrumb $breadcrumb
   */
  private function addAward(NodeInterface $line_item, Breadcrumb $breadcrumb) {
    $award = $line_item->field_award->entity;
    $label = vsprintf('%s (%s)', [
      $award->field_vendor->entity->label(),
      $award->field_identifier->value,
    ]);

    $breadcrumb->addLink(Link::createFromRoute(
      $label,
      'entity.node.canonical',
      ['node' => $line_item->field_award->entity->id()]
    ));
  }

  /**
   * Add the procurement to the breadcrumb given the award.
   *
   * @param NodeInterface $award
   * @param Breadcrumb $breadcrumb
   */
  private function addProcurement(NodeInterface $award, Breadcrumb $breadcrumb) {
    $procurement = $award->field_procurement_method->entity;

    if ($procurement) {
      $label = vsprintf('%s (%s)', [
        $procurement->label(),
        $procurement->field_identifier->value
      ]);

      $breadcrumb->addLink(Link::createFromRoute(
        $label,
        'entity.node.canonical',
        ['node' => $award->field_procurement_method->entity->id()]
      ));
    }
  }
}
