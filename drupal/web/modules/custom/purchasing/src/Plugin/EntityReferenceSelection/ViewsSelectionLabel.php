<?php

namespace Drupal\purchasing\Plugin\EntityReferenceSelection;

use Drupal\views\Plugin\EntityReferenceSelection\ViewsSelection as SelectionBase;
use Drupal\Component\Utility\Unicode;

/**
 * Plugin implementation of the 'selection' entity_reference.
 *
 * @EntityReferenceSelection(
 *   id = "purchasing",
 *   label = @Translation("Purchasing: Filter by an entity reference view (with identifier)"),
 *   group = "purchasing",
 *   weight = 0
 * )
 */
class ViewsSelectionLabel extends SelectionBase {
  /**
   * {@inheritdoc}
   */
  public function getReferenceableEntities($match = NULL, $match_operator = 'CONTAINS', $limit = 0) {
    $display_name = $this->getConfiguration()['view']['display_name'];
    $arguments = $this->getConfiguration()['view']['arguments'];
    $result = [];
    if ($this->initializeView($match, $match_operator, $limit)) {
      // Get the results.
      $result = $this->view->executeDisplay($display_name, $arguments);
    }

    $return = [];
    if ($result) {
      foreach ($this->view->result as $row) {
        $entity = $row->_entity;

        $label = $entity->label();
        if ($entity->hasField('field_identifier')) {
          $label = Unicode::truncate($label, 48, TRUE, TRUE);
          $label .= ' (' . $entity->field_identifier->value . ')';
        }

        $return[$entity->bundle()][$entity->id()] = $label;
      }
    }
    return $return;
  }
}
