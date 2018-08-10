<?php

namespace Drupal\purchasing\Generator\Node;

use Drupal\node\Entity\Node;
use Drupal\file\Entity\File;
use Drupal\purchasing\Generator\EntityGeneratorInterface;
use GuzzleHttp\Client;
use Drupal\purchasing\Generator\AbstractEntityGenerator;

class SolicitationGenerator extends NodeGenerator {

  /**
   * Due date timestamp.
   *
   * @var int
   */
  private $due;

  /**
   * Effective start date timestamp.
   *
   * @var int
   */
  private $start;

  /**
   * Effective end date timestamp.
   *
   * @var int
   */
  private $end;

  /**
   * Presentation to the board timestamp.
   *
   * @var int
   */
  private $presentation_to_the_board;

  public function __construct(array $data, $files_dir = NULL) {
    parent::__construct($data, $files_dir);

    $this->due = strtotime($data['bids_due']);

    if (!empty($data['effective']['start'])) {
      $this->start = strtotime($data['effective']['start']);
    }

    if (!empty($data['effective']['end'])) {
      $this->end = strtotime($data['effective']['end']);
    }

    if (!empty($data['presentation_to_the_board'])) {
      $this->presentation_to_the_board = strtotime($data['presentation_to_the_board']);
    }
  }

  /**
   * {@inheritDoc}
   * @see \Drupal\purchasing\Generator\Node\NodeGenerator::getBundle()
   */
  protected static function getBundle() {
    return 'solicitation';
  }

  /**
   * Create a solicitation node from an array of values.
   *
   * @param array $data
   *   Solicitation values
   * @return Node
   */
  public function generate() {
    $solicitation = Node::create([
      'type'                  => static::getBundle(),
      'title'                 => $this->data['title'],
      'uid'                   => \Drupal::currentUser()->id() ?: 1,
      'field_identifier'      => $this->data['bid_number'],
      'field_category'        => ['target_id' => self::getCategoryIdFromName($this->data['category'])],
      'field_due_date'        => date('Y-m-d\Th:i:s', $this->due),
    ]);

    if ($this->start && $this->end) {
      $solicitation->field_dates_effective = [
        'value' => date('Y-m-d', $this->start),
        'end_value' => date('Y-m-d', $this->end),
      ];
    }

    if ($this->presentation_to_the_board) {
      $solicitation->field_presentation_to_the_board = date('Y-m-d', $this->presentation_to_the_board);
    }

    if (!empty($this->data['bid_tab'])) {
      $solicitation->field_bid_tabulation = self::createFile($this->data['bid_tab']);
    }

    if (!empty($this->data['documentation'])) {
      foreach ($this->data['documentation'] as $index => $document) {
        if (empty($document['file'])) {
          continue;
        }

        $file = $this->createFile($document['file']);
        $solicitation->field_documentation[$index] = $file;
        $solicitation->field_documentation[$index]->description = $document['name'];
        $solicitation->field_documentation[$index]->display = 1;
      }
    }

    if (!empty($this->data['board']['report'])) {
      $solicitation->field_board_report = ['uri' => $this->data['board']['report']];
    }

    if (!empty($this->data['board']['minutes'])) {
      $solicitation->field_board_report = ['uri' => $this->data['board']['minutes']];
    }

    $solicitation->save();

    return $solicitation;
  }
}
