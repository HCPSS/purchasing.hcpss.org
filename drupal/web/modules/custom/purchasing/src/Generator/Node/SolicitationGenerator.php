<?php

namespace Drupal\purchasing\Generator\Node;

use Drupal\node\Entity\Node;
use Drupal\file\Entity\File;
use Drupal\purchasing\Generator\EntityGeneratorInterface;

class SolicitationGenerator extends NodeGenerator implements EntityGeneratorInterface {

  /**
   * Delete solicitations.
   */
  public static function deleteAll() {
    parent::deleteAllOfBundle('solicitation');
  }

  /**
   * Create a solicitation node from an array of values.
   *
   * @param array $data
   *   Solicitation values
   * @return Node
   */
  public static function createFromArray(array $data) {
    // Make up some dates.
    $due = time() + rand(3600 * 24 * 7, 3600 * 24 * 60);
    $start = $due + 3600 * 24 * 30;
    $end = strtotime('+1 year', $start);

    $solicitation = Node::create([
      'type'             => 'solicitation',
      'title'            => $data['title'],
      'uid'              => 1,
      'field_identifier' => $data['bid_number'],
      'field_due_date'   => date("Y-m-d\Th:i:s", $due),
      'field_category'   => ['target_id' => self::getCategoryIdFromName($data['category'])],
    ]);

    $solicitation->field_dates_effective = [
      'value' => date('Y-m-d', $start),
      'end_value' => date('Y-m-d', $end),
    ];

    if (!empty($data['bid_tab'])) {
      $solicitation->field_bid_tabulation = self::createFile($data['bid_tab']);
    }

    if (!empty($data['documentation'])) {
      foreach ($data['documentation'] as $document) {
        $file = self::createFile($document['file']);
        $solicitation->field_documentation[] = [
          'description' => $document['name'],
          'file' => $file,
        ];
      }
    }

    $solicitation->save();
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
      'filename' => $data['bid_tab'],
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
