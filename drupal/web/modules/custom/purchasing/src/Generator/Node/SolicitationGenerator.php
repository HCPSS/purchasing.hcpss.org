<?php

namespace Drupal\purchasing\Generator\Node;

use Drupal\node\Entity\Node;
use Drupal\file\Entity\File;
use Drupal\purchasing\Generator\EntityGeneratorInterface;

class SolicitationGenerator extends NodeGenerator implements EntityGeneratorInterface {

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
   * Solicitation data
   *
   * @var array
   */
  private $data;

  public function __construct(array $data) {
    if (!empty($data['bids_due'])) {
      $this->due = strtotime($data['bids_due']);
    } else {
      $this->due = time() + rand(3600 * 24 * 7, 3600 * 24 * 60);
    }

    if (!empty($data['effective']['start'])) {
      $this->start = strtotime($data['effective']['start']);
    } else {
      $this->start = $this->due + 3600 * 24 * 30;
    }

    if (!empty($data['effective']['end'])) {
      $this->end = strtotime($data['effective']['end']);
    } else {
      $this->end = strtotime('+1 year', $this->start);
    }

    $this->data = $data;
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
      'uid'                   => \Drupal::currentUser()->id(),
      'field_identifier'      => $this->data['bid_number'],
      'field_category'        => ['target_id' => self::getCategoryIdFromName($this->data['category'])],
      'field_due_date'        => date('Y-m-d\Th:i:s', $this->due),
      'field_dates_effective' => ['value' => date('Y-m-d', $this->start), 'end_value' => date('Y-m-d', $this->end)],
    ]);

    if (!empty($this->data['bid_tab'])) {
      $solicitation->field_bid_tabulation = self::createFile($this->data['bid_tab']);
    }

    if (!empty($this->data['documentation'])) {
      foreach ($this->data['documentation'] as $document) {
        $file = self::createFile($document['file']);
        $solicitation->field_documentation[] = [
          'description' => $document['name'],
          'file' => $file,
        ];
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

  /**
   * Create a file from the given filename.
   *
   * @param string $fileName
   * @return \Drupal\file\FileInterface|false
   */
  private static function createFile($fileName) {
    $fileFolder = drupal_get_path('module', 'purchasing') . '/data/files/';
    $file = File::create([
      'uid'      => 1,
      'status'   => 1,
      'filename' => $fileName,
      'uri'      => $fileFolder . $fileName,
    ]);

    $newFile = file_copy($file, 'public://', $fileName);

    if (!$newFile) {
      echo "$fileName\n";
      die;
    }

    $newFile->setPermanent();
    $newFile->save();

    return $newFile;
  }
}
