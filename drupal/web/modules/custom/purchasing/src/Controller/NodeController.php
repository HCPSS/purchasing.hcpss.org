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

    if ($node_type->id() === 'award' && $sid = \Drupal::request()->get('solicitation')) {
      $node->field_procurement_method = ['target_id' => $sid];
    }

    $form = $this->entityFormBuilder()->getForm($node);

    return $form;
  }
}
