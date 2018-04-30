<?php

namespace Drupal\purchasing\Generator\Node;

use Drupal\purchasing\Generator\EntityGeneratorInterface;
use Drupal\node\Entity\Node;

class LineItemGenerator extends NodeGenerator implements EntityGeneratorInterface {

  /**
   * Delete line items.
   */
  public static function deleteAll() {
    parent::deleteAllOfBundle('priced_line_item');
    parent::deleteAllOfBundle('discounted_line_item');
  }

  /**
   * Create a line item node from an array of values.
   *
   * @param array $data
   *   Line Item values
   * @return Node
   */
  public static function createFromArray(array $data) {
    switch ($data['type']) {
      case 'priced':
        return self::createPricedLineItemFromArray($data);
      case 'discount':
        return self::createDiscountLineItemFromArray($data);
    }

    throw new \Exception('Can\'t generate ' . $data['type']);
  }

  /**
   * Load an Award given the HCPSS contract number.
   *
   * @param string $number
   * @return \Drupal\node\Entity\Node
   */
  private static function loadAwardFromNumber($number) {
    $nids = \Drupal::entityQuery('node')
      ->condition('type', 'award')
      ->condition('field_identifier', $number)
      ->execute();

    return Node::load(array_shift($nids));
  }

  /**
   * Create a discount line item from an array of values.
   *
   * @param array $data
   *   Line Item values
   * @return Node
   */
  private static function createDiscountLineItemFromArray(array $data) {
    $line_item = Node::create([
      'type' => 'discounted_line_item',
      'title' => ucwords(strtolower($data['title'])),
      'uid' => 1,
      'field_discount' => $data['discount'],
      'field_award' => self::loadAwardFromNumber($data['award']),
    ]);

    if (!empty($data['description'])) {
      $line_item->body = [
        'value' => $data['description'],
        'format' => 'basic_html',
      ];
    }

    if (!empty($data['exceptions'])) {
      $line_item->field_exceptions = $data['exceptions'];
    }

    $line_item->save();

    return $line_item;
  }

  /**
   * Create a priced line item from an array of values.
   *
   * @param array $data
   *   Line Item values
   * @return Node
   */
  private static function createPricedLineItemFromArray(array $data) {
    $line_item = Node::create([
      'type' => 'priced_line_item',
      'title' => ucwords(strtolower($data['title'])),
      'uid' => 1,
      'field_price' => $data['price'],
      'field_award' => self::loadAwardFromNumber($data['award']),
    ]);

    if (!empty($data['description'])) {
      $line_item->body = [
        'value' => $data['description'],
        'format' => 'basic_html',
      ];
    }

    $line_item->save();

    return $line_item;
  }
}
