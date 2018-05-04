<?php

namespace Drupal\purchasing\Generator\Node;

use Drupal\purchasing\Generator\EntityGeneratorInterface;
use Drupal\node\Entity\Node;
use Drupal\paragraphs\Entity\Paragraph;

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
    if (trim($this->data['title']) == 'Bulletin Board, 4 x 8') {
      dpm($this->data);
    }

    if (!is_numeric($this->data['price'])) {
      throw new \Exception('Price must be a number. '. $this->data['price'] .' given');
    }

    $line_item = Node::create([
      'type' => static::getBundle(),
      'title' => ucwords(strtolower($this->data['title'])),
      'uid' => \Drupal::currentUser()->id(),
      'field_price' => $this->data['price'],
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

    if (!empty($this->data['install_charge'])) {
      if (!is_numeric($this->data['install_charge'])) {
        throw new \Exception('Install charge must be a number. '. $this->data['install_charge'] .' given');
      }

      setlocale(LC_MONETARY, 'en_US.UTF-8');
      $charge = Paragraph::create([
        'type' => 'other_charge',
        'field_name' => 'Installation',
        'field_charge' => '$' . money_format('%.2n', (float)$this->data['install_charge']),
      ]);

      $charge->save();

      $line_item->field_additional_charges[] = [
        'target_id' => $charge->id(),
        'target_revision_id' => $charge->getRevisionId(),
      ];
    }

    $line_item->save();

    return $line_item;
  }
}
