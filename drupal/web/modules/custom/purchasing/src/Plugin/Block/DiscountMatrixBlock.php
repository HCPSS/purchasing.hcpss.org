<?php

namespace Drupal\purchasing\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\node\NodeInterface;
use Drupal\node\Entity\Node;
use Drupal\purchasing\Model\DiscountMatrix;
use Drupal\Core\Cache\Cache;

/**
 * @Block(
 *   id = "discount_matrix_block",
 *   admin_label = @Translation("Discount Matrix Block")
 * )
 */
class DiscountMatrixBlock extends BlockBase {

  /**
   * {@inheritDoc}
   * @see \Drupal\Core\Block\BlockPluginInterface::build()
   */
  public function build() {
    $node = \Drupal::routeMatch()->getParameter('node');
    $procurement_bundles = [
      'solicitation', 'contract', 'quote',
    ];

    if ($node instanceof NodeInterface && in_array($node->bundle(), $procurement_bundles)) {
      $award_ids = \Drupal::entityQuery('node')
        ->condition('type', 'award')
        ->condition('field_procurement_method', $node->id())
        ->condition('status', 1)
        ->execute();

      $line_item_ids = \Drupal::entityQuery('node')
        ->condition('type', 'discounted_line_item')
        ->condition('status', 1)
        ->condition('field_award.target_id', $award_ids, 'IN')
        ->sort('title')
        ->execute();

      if (!empty($line_item_ids)) {
        $line_items = Node::loadMultiple($line_item_ids);

        $matrix = new DiscountMatrix($line_items);

        return [
          '#theme' => 'discount_matrix',
          '#matrix' => $matrix,
        ];
      }
    }
  }

  /**
   * {@inheritDoc}
   * @see \Drupal\Core\Plugin\ContextAwarePluginBase::getCacheTags()
   */
  public function getCacheTags() {
    //With this when your node change your block will rebuild
    if ($node = \Drupal::routeMatch()->getParameter('node')) {
      //if there is node add its cachetag
      return Cache::mergeTags(parent::getCacheTags(), array('node:' . $node->id()));
    } else {
      //Return default tags instead.
      return parent::getCacheTags();
    }
  }

  /**
   * {@inheritDoc}
   * @see \Drupal\Core\Plugin\ContextAwarePluginBase::getCacheContexts()
   */
  public function getCacheContexts() {
    //if you depends on \Drupal::routeMatch()
    //you must set context of this block with 'route' context tag.
    //Every new route this block will rebuild
    return Cache::mergeContexts(parent::getCacheContexts(), array('route'));
  }
}
