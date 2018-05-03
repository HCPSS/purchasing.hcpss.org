<?php

namespace Drupal\purchasing\Model;

use Drupal\node\NodeInterface;

class DiscountMatrix {

  /**
   * Discounted line items
   *
   * @var NodeInterface[]
   */
  private $line_items;

  public function __construct(array $line_items) {
    foreach ($line_items as $line_item) {
      if (!$line_item instanceof NodeInterface || $line_item->bundle() != 'discounted_line_item') {
        throw new \InvalidArgumentException("Line item must be a discounted line item.");
      }
    }

    $this->line_items = $line_items;
  }

  public function getDiscount($title, $vendor) {
    foreach ($this->line_items as $line_item) {
      if ($title == $line_item->getTitle()) {
        if ($vendor->id() == $line_item->field_award->entity->field_vendor->entity->id()) {
          return $line_item;
        }
      }
    }

    return NULL;
  }

  /**
   * Get a list of item names.
   *
   * @return array
   */
  public function getItemNames() {
    $names = [];
    foreach ($this->line_items as $line_item) {
      $names[$line_item->getTitle()] = $line_item->getTitle();
    }

    return array_values($names);
  }

  /**
   * Get an array of vendors.
   *
   * @return NodeInterface[]
   */
  public function getVendors() {
    $vendors = [];
    foreach ($this->line_items as $line_item) {
      $vendor = $line_item->field_award->entity->field_vendor->entity;
      $vendors[$vendor->label()] = $vendor;
    }

    ksort($vendors);

    return array_values($vendors);
  }
}
