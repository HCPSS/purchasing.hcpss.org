<?php

namespace Drupal\purchasing\Generator\Node;

use Drupal\node\Entity\Node;

class AwardGenerator extends NodeGenerator {

  private static $contractNumber = 1;

  protected static function getBundle() {
    return 'award';
  }

  /**
   * Create an award node from an array of values.
   *
   * @param array $data
   *   Award values
   * @return Node
   */
  public function generate() {
    $procurement = self::loadProcurementMethod(
      $this->data['procurement_method']['name'],
      $this->data['procurement_method']['id']
    );

    $vendor = self::loadVendorFromName($this->data['vendor']);

    $award = Node::create([
      'type' => static::getBundle(),
      'uid' => \Drupal::currentUser()->id() ?: 1,
      'field_vendor' => $vendor,
      'field_procurement_method' => $procurement,
    ]);

    if (!empty($this->data['id'])) {
      $award->field_identifier = $this->data['id'];
    } else {
      $award->field_identifier =  self::generateHcpssContractNumber();
    }

    if (!empty($this->data['notes'])) {
      $award->field_notes = [
        'value' => $this->data['notes'],
        'format' => 'basic_html',
      ];
    }

    if (!empty($this->data['ordering_instructions'])) {
      $award->field_ordering_instructions = [
        'value' => $this->data['ordering_instructions'],
        'format' => 'basic_html',
      ];
    }

    if (!empty($this->data['reference'])) {
      $award->field_reference_number = $this->data['reference'];
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
    if (!is_string($name)) {
      throw new \InvalidArgumentException('Name must be a string.');
    }

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
  public static function generateHcpssContractNumber() {
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
