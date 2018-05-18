<?php

namespace Drupal\purchasing\Breadcrumb;

use Drupal\node\NodeInterface;
use Drupal\Core\Link;
use Drupal\pathauto\AliasCleaner;

abstract class SegmentBreadcrumb {

  // The position in the breadcrumb each element belongs in.
  const POSITION_HOME             = 0;
  const POSITION_PROCUREMENT_NODE = 10;
  const POSITION_PROCUREMENT_VIEW = 5;
  const POSITION_AWARD_NODE       = 20;
  const POSITION_AWARD_VIEW       = 15;
  const POSITION_LINE_ITEM_NODE   = 30;
  const POSITION_LINE_ITEM_VIEW   = 25;

  // The abstract types of content that exist.
  const CATEGORY_LINE_ITEM   = 'line_item';
  const CATEGORY_AWARD       = 'award';
  const CATEGORY_PROCUREMENT = 'procurement';

  /**
   * Get alias cleaner.
   *
   * @return AliasCleaner
   */
  protected function aliasCleaner() {
    return \Drupal::service('pathauto.alias_cleaner');
  }

  /**
   * What kind of node is this?
   *
   * @param NodeInterface $node
   * @return string
   */
  protected function getNodeCategory(NodeInterface $node) {
    $bundle       = $node->bundle();
    $procurements = ['solicitation', 'contract', 'quote'];
    $line_items   = [
      'priced_line_item',
      'discounted_line_item',
      'partner_line_item',
    ];

    if (in_array($bundle, $line_items)) {
      return self::CATEGORY_LINE_ITEM;
    } else if ($node->bundle() == 'award') {
      return self::CATEGORY_AWARD;
    } else if (in_array($bundle, $procurements)) {
      return self::CATEGORY_PROCUREMENT;
    }

    throw new \InvalidArgumentException("Could not determine type of bundle $bundle");
  }

  /**
   * Add a view segment to segments.
   *
   * @param NodeInterface $node
   * @param array $segments
   */
  protected function addViewSegment(NodeInterface $node, array &$segments) {
    switch ($this->getNodeCategory($node)) {
      case self::CATEGORY_LINE_ITEM:
        $award       = $node->field_award->entity;
        $procurement = $award->field_procurement_method->entity;
        $route       = 'view.line_items.' . $procurement->bundle();

        $procurement_identifier = $this
          ->aliasCleaner()
          ->cleanString($procurement->field_identifier->value);

        $award_identifier = $this
          ->aliasCleaner()
          ->cleanString($award->field_identifier->value);

        $link = Link::createFromRoute('Line Item', $route, [
          'arg_0' => $procurement_identifier,
          'arg_1' => $award_identifier,
        ]);

        $segments[self::POSITION_LINE_ITEM_VIEW] = $link;

        $this->addNodeSegment($award, $segments);
        break;
      case self::CATEGORY_AWARD:
        $procurement = $node->field_procurement_method->entity;
        $route       = 'view.award.' . $procurement->bundle();

        $procurement_identifier = $this
          ->aliasCleaner()
          ->cleanString($procurement->field_identifier->value);

        $link = Link::createFromRoute('Award', $route, [
          'arg_0' => $procurement_identifier,
        ]);

        $segments[self::POSITION_AWARD_VIEW] = $link;

        $this->addNodeSegment($procurement, $segments);
        break;
      case self::CATEGORY_PROCUREMENT:
        $route = 'view.procurement_methods.' . $node->bundle();
        $link = Link::createFromRoute(ucwords($node->bundle()), $route);
        $segments[self::POSITION_PROCUREMENT_VIEW] = $link;

        break;
    }
  }

  /**
   * Add link segment to segments.
   *
   * @param NodeInterface $node
   * @param array $segments
   */
  protected function addNodeSegment(NodeInterface $node, array &$segments) {
    switch ($this->getNodeCategory($node)) {
      case self::CATEGORY_AWARD:
        $segments[self::POSITION_AWARD_NODE] = Link::createFromRoute(
          $node->field_vendor->entity->label(),
          'entity.node.canonical',
          ['node' => $node->id()]
        );

        $this->addViewSegment($node, $segments);
        break;
      case self::CATEGORY_PROCUREMENT:
        $segments[self::POSITION_PROCUREMENT_NODE] = Link::createFromRoute(
          $node->label(),
          'entity.node.canonical',
          ['node' => $node->id()]
        );

        $this->addViewSegment($node, $segments);
        break;
    }
  }
}
