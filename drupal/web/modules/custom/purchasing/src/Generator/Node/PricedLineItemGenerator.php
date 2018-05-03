<?php

namespace Drupal\purchasing\Generator\Node;

use Drupal\purchasing\Generator\EntityGeneratorInterface;
use Drupal\node\Entity\Node;

class PricedLineItemGenerator extends NodeGenerator implements EntityGeneratorInterface {

  private $data;

  public function __construct(array $data) {
    $this->data = $data;
  }

  /**
   * {@inheritDoc}
   * @see \Drupal\purchasing\Generator\Node\NodeGenerator::getBundle()
   */
  protected static function getBundle() {
    return 'priced_line_item';
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
   * Create a priced line item from an array of values.
   *
   * @param array $data
   *   Line Item values
   * @return Node
   */
  public function generate() {
    $line_item = Node::create([
      'type' => static::getBundle(),
      'title' => ucwords(strtolower($this->data['title'])),
      'uid' => 1,
      'field_price' => $this->data['price'],
      'field_award' => self::loadAwardFromNumber($this->data['award']),
    ]);

    if (!empty($this->data['description'])) {
      $line_item->body = [
        'value' => $this->data['description'],
        'format' => 'basic_html',
      ];
    }

    $line_item->save();

    return $line_item;
  }
}
