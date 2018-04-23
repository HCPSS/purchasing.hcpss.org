<?php

namespace Drupal\purchasing\Generator\Node;

use Drupal\node\Entity\Node;
use Drupal\purchasing\Generator\EntityGeneratorInterface;

class ContractGenerator extends NodeGenerator  implements EntityGeneratorInterface {

  /**
   * Delete solicitations.
   */
  public static function deleteAll() {
    parent::deleteAllOfBundle('contract');
  }

  /**
   * Create a solicitation node from an array of values.
   *
   * @param array $data
   *   Solicitation values
   * @return Node
   */
  public static function createFromArray(array $data) {
    $start = strtotime($data['dates_effective']['start']);
    $end =  strtotime($data['dates_effective']['end']);

    $contract = Node::create([
      'type' => 'contract',
      'uid' => 1,
      'title' => $data['title'],
      'field_identifier' => $data['contract_number'],
      'field_category' => ['target_id' => self::getCategoryIdFromName($data['category'])],
      'field_dates_effective' => [
        'value' => date("Y-m-d", $start),
        'end_value' => date("Y-m-d", $end),
      ],
    ]);

    $contract->save();

    return $contract;
  }
}
