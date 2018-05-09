<?php

namespace Drupal\purchasing\Breadcrumb;

use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Link;
use Drupal\node\NodeInterface;

class PurchasingBreadcrumbBuilder implements BreadcrumbBuilderInterface {

  private $routeMap = [
    'solicitation' => ['Solicitation', 'view.procurements.page_3'],
    'contract'     => ['Contract',     'view.procurements.page_1'],
    'quote'        => ['Quote',        'view.procurements.page_2'],
  ];

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
          $procurement = $node->field_procurement_method->entity;
          $this->addProcurementMethod($procurement, $breadcrumb);
          $this->addProcurement($procurement, $breadcrumb);
          break;
        case 'priced_line_item':
          $award = $node->field_award->entity;
          $procurement = $award->field_procurement_method->entity;

          $this->addProcurementMethod($procurement, $breadcrumb);
          $this->addProcurement($procurement, $breadcrumb);
          $this->addAwardName($award, $breadcrumb);
          $this->addAward($award, $breadcrumb);
          break;
        case 'discounted_line_item':

          break;
        case 'solicitation':
          $this->addProcurementMethod($node, $breadcrumb);
          break;
        case 'contract':
          $this->addProcurementMethod($node, $breadcrumb);
          break;
        case 'quote':
          $this->addProcurementMethod($node, $breadcrumb);
          break;
      }
    }

    return $breadcrumb;
  }

  /**
   * Add generic award link.
   *
   * @param NodeInterface $award
   * @param Breadcrumb $breadcrumb
   */
  private function addLineItem(NodeInterface $lineItem, Breadcrumb $breadcrumb) {
    $award = $lineItem->field_award->entity;
    $procurement = $award->field_procurement_method->entity;

    switch ($procurement->bundle()) {
      case 'solicitation':
        $route = 'view.line_item.page_1';
        break;
      case 'contract':
        $route = 'view.line_item.page_2';
        break;
      case 'quote':
        $route = 'view.line_item.page_3';
        break;
    }

    $breadcrumb->addLink(Link::createFromRoute(ucwords($procurement->bundle()), $route, [
      'arg_0' => $procurement->field_identifier->value,
      'arg_1' => $award->field_identifier->value,
    ]));
  }

  /**
   * Add generic award link.
   *
   * @param NodeInterface $award
   * @param Breadcrumb $breadcrumb
   */
  private function addAward(NodeInterface $award, Breadcrumb $breadcrumb) {
    $breadcrumb->addLink(Link::createFromRoute('Award', 'view.award.page_1', [
      'arg_0' => $award->field_procurement_method->entity->field_identifier->value,
    ]));
  }

  /**
   * Add the procurement method to the breadcrumb.
   *
   * @param NodeInterface $procurement
   * @param Breadcrumb $breadcrumb
   */
  private function addProcurementMethod(NodeInterface $procurement, Breadcrumb $breadcrumb) {
    list($label, $route) = $this->routeMap[$procurement->bundle()];
    $breadcrumb->addLink(Link::createFromRoute($label, $route));
  }

  /**
   * Add the award to the breadcrumb given the line item.
   *
   * @param NodeInterface $award
   * @param Breadcrumb $breadcrumb
   */
  private function addAwardName(NodeInterface $award, Breadcrumb $breadcrumb) {
    $breadcrumb->addLink(Link::createFromRoute(
      $award->field_vendor->entity->label(),
      'entity.node.canonical',
      ['node' => $award->id()]
    ));
  }

  /**
   * Add the procurement to the breadcrumb given the award.
   *
   * @param NodeInterface $procurement
   * @param Breadcrumb $breadcrumb
   */
  private function addProcurement(NodeInterface $procurement, Breadcrumb $breadcrumb) {
    if ($procurement) {
      $breadcrumb->addLink(Link::createFromRoute(
        $procurement->label(),
        'entity.node.canonical',
        ['node' => $procurement->id()]
      ));
    }
  }
}
