<?php

namespace Drupal\purchasing\Model;

use Drupal\node\NodeInterface;
use Drupal\node\Entity\Node;

class ProcurementMethod {

  /**
   * @var NodeInterface
   */
  private $node;

  /**
   * @var int[]
   */
  private $awardIds = [];

  /**
   * @var int[]
   */
  private $discountAwardIds = [];

  /**
   * @var int[]
   */
  private $discountedLineItemIds = [];

  /**
   * @var int[]
   */
  private $pricedLineItemIds = [];

  /**
   * @var NodeInterface[]
   */
  private $discountedLineItems = [];

  public function __construct(NodeInterface $node) {
    if (!in_array($node->bundle(), ['solicitation', 'contract', 'quote'])) {
      throw new \InvalidArgumentException('Node must be a solicitaiton, contract or quote.');
    }

    $this->node = $node;
  }

  /**
   * Get the award ids.
   *
   * @return number[]
   */
  public function getAwardIds() {
    if (empty($this->awardIds)) {
      $this->awardIds = \Drupal::entityQuery('node')
        ->condition('status', 1)
        ->condition('type', 'award')
        ->condition('field_procurement_method.target_id', $this->node->id())
        ->execute();
    }

    return $this->awardIds;
  }

  /**
   * Get discounted line item ids.
   *
   * @return number[]
   */
  public function getDiscountedLineItemIds() {
    if (empty($this->discountedLineItemIds)) {
      $this->discountedLineItemIds = \Drupal::entityQuery('node')
        ->condition('status', 1)
        ->condition('type', 'discounted_line_item')
        ->condition('field_award.target_id', static::getAwardIds(), 'IN')
        ->execute();
    }

    return $this->discountedLineItemIds;
  }

  /**
   * Get priced line item ids.
   *
   * @return number[]
   */
  public function getPricedLineItemIds() {
    if (empty($this->pricedLineItemIds)) {
      $this->pricedLineItemIds = \Drupal::entityQuery('node')
        ->condition('status', 1)
        ->condition('type', 'priced_line_item')
        ->condition('field_award.target_id', static::getAwardIds(), 'IN')
        ->execute();
    }

    return $this->pricedLineItemIds;
  }

  /**
   * Get discounted line items.
   *
   * @return \Drupal\node\NodeInterface[]
   */
  public function getDiscountedLineItems() {
    if (empty($this->discountedLineItems)) {
      $ids = $this->getDiscountedLineItemIds();
      $this->discountedLineItems = Node::loadMultiple($ids);
    }

    return $this->discountedLineItems;
  }

  /**
   * Get discounted award ids.
   *
   * @return number[]
   */
  public function getDiscountAwardIds() {
    if (empty($this->discountAwardIds)) {
      foreach ($this->getDiscountedLineItems() as $lineItem) {
        \Drupal::messenger()->addMessage($lineItem->field_award->target_id);
        if (!in_array($lineItem->field_award->target_id, $this->discountAwardIds)) {
          $this->discountAwardIds[] = $lineItem->field_award->target_id;
        }
      }
    }

    return $this->discountAwardIds;
  }
}
