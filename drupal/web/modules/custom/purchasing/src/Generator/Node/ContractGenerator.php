<?php

namespace Drupal\purchasing\Generator\Node;

use Drupal\node\Entity\Node;

class ContractGenerator extends NodeGenerator {

  protected static function getBundle() {
    return 'contract';
  }

  /**
   * Create a solicitation node from an array of values.
   *
   * @param array $data
   *   Solicitation values
   * @return Node
   */
  public function generate() {
    $start = strtotime($this->data['dates_effective']['start']);
    $end =  strtotime($this->data['dates_effective']['end']);

    $contract = Node::create([
      'type' => static::getBundle(),
      'uid' => \Drupal::currentUser()->id() ?: 1,
      'title' => $this->data['title'],
      'field_identifier' => $this->data['contract_number'],
      'field_category' => ['target_id' => self::getCategoryIdFromName($this->data['category'])],
      'field_dates_effective' => [
        'value' => date("Y-m-d", $start),
        'end_value' => date("Y-m-d", $end),
      ],
    ]);

    $contract->save();

    return $contract;
  }
}
