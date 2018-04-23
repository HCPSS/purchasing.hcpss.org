<?php

namespace Drupal\purchasing\Generator\Node;

use Drupal\purchasing\Generator\EntityGeneratorInterface;
use Drupal\node\Entity\Node;

class AwardGenerator extends NodeGenerator implements EntityGeneratorInterface {

  private static $contractNumber = 1;

  /**
   * Delete awards.
   */
  public static function deleteAll() {
    parent::deleteAllOfBundle('award');
  }

  /**
   * Create an award node from an array of values.
   *
   * @param array $data
   *   Award values
   * @return Node
   */
  public static function createFromArray(array $data) {
    $procurement = self::loadProcurementMethod(
      $data['procurement_method']['name'],
      $data['procurement_method']['id']
    );

    $vendor = self::loadVendorFromName($data['vendor']);

    $award = Node::create([
      'type' => 'award',
      'uid' => 1,
      'field_vendor' => $vendor,
      'field_procurement_method' => $procurement,
    ]);

    if (!empty($data['id'])) {
      $award->field_identifier = $data['id'];
    } else {
      $award->field_identifier =  self::generateHcpssContractNumber();
    }

    if (!empty($data['notes'])) {
      $award->field_notes = $data['notes'];
    }

    if (!empty($data['ordering_instructions'])) {
      $award->field_ordering_instructions = $data['ordering_instructions'];
    }

    if (!empty($data['reference'])) {
      $award->field_reference_number = $data['reference'];
    }

    $award->save();

    return $award;
  }

  /**
   * Load a vendor from it's name.
   *
   * @param string $name
   * @return \Drupal\node\Entity\Node
   */
  private static function loadVendorFromName($name) {
    $nids = \Drupal::entityQuery('node')
      ->condition('type', 'vendor')
      ->condition('title', $name)
      ->execute();

    return Node::load(array_shift($nids));
  }

  /**
   * Load the procurement method.
   *
   * @param string $type
   *   The bundle of the procurement method.
   * @param string $id
   *   All procurement methods have a field_identifier field. Pass that value.
   * @return \Drupal\node\Entity\Node
   */
  private static function loadProcurementMethod($type, $id) {
    $nids = \Drupal::entityQuery('node')
      ->condition('type', $type)
      ->condition('field_identifier', $id)
      ->execute();

    return Node::load(array_shift($nids));
  }

  /**
   * Make a fake HCPSS contract number.
   *
   * @return string
   */
  private static function generateHcpssContractNumber() {
    $procurementMethod = [
      'PC', 'E', 'G', 'L', 'M', 'PO', 'PR', 'Q', 'RP', 'PS', 'T',
    ];

    return implode('.', [
      sprintf('%03d', self::$contractNumber++),
      18,
      rand(1, 5),
      $procurementMethod[array_rand($procurementMethod)],
      '01',
      sprintf('%04d', rand(0, 9999)),
    ]);
  }
}
