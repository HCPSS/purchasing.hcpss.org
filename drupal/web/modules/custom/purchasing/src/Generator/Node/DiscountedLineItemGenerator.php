<?php

namespace Drupal\purchasing\Generator\Node;

use Drupal\purchasing\Generator\EntityGeneratorInterface;
use Drupal\node\Entity\Node;

class DiscountedLineItemGenerator extends NodeGenerator implements EntityGeneratorInterface {

  private $data;

  public function __construct(array $data) {
    $this->data = $data;
  }

  /**
   * {@inheritDoc}
   * @see \Drupal\purchasing\Generator\Node\NodeGenerator::getBundle()
   */
  protected static function getBundle() {
    return 'discounted_line_item';
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
  public function generate() {
    $line_item = Node::create([
      'type' => static::getBundle(),
      'title' => ucwords(strtolower($this->data['title'])),
      'uid' => \Drupal::currentUser()->id(),
      'field_discount' => $this->data['discount'],
      'field_award' => self::loadAwardFromNumber($this->data['award']),
    ]);

    if (!empty($this->data['description'])) {
      $line_item->body = [
        'value' => $this->data['description'],
        'format' => 'basic_html',
      ];
    }

    if (!empty($this->data['exceptions'])) {
      $line_item->field_exceptions = $this->data['exceptions'];
    }

    $line_item->save();

    return $line_item;
  }
}
