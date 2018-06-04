<?php

namespace Drupal\purchasing\Controller;

use Drupal\node\Controller\NodeController as NodeControllerBase;
use Drupal\node\NodeTypeInterface;

class NodeController extends NodeControllerBase {

  /**
   * {@inheritDoc}
   * @see \Drupal\node\Controller\NodeController::add()
   */
  public function add(NodeTypeInterface $node_type) {
    $node = static::entityTypeManager()->getStorage('node')->create([
      'type' => $node_type->id(),
    ]);

    if ($node_type->id() === 'award' && $pid = \Drupal::request()->get('procurement')) {
      $node->field_procurement_method = ['target_id' => $pid];
    } else if (in_array($node_type->id(), ['priced_line_item', 'discounted_line_item'])) {
      if ($aid = \Drupal::request()->get('award')) {
        $node->field_award = ['target_id' => $aid];
      }
    }

    $form = $this->entityFormBuilder()->getForm($node);

    return $form;
  }
}
