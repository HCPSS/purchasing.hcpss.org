<?php

namespace Drupal\purchasing\Generator;

use GuzzleHttp\Client;
use Drupal\file\Entity\File;

abstract class AbstractEntityGenerator implements EntityGeneratorInterface {
  /**
   * Path to the directory for relative file paths.
   *
   * @var string
   */
  protected $files_dir;

  /**
   * Solicitation data
   *
   * @var array
   */
  protected $data;

  public function __construct(array $data, $files_dir = NULL) {
    $this->data = $data;
    $this->files_dir = $files_dir;
  }

  /**
   * Resolve the filename which may be remote, absolute, or relative.
   *
   * @param string $filename
   * @return string
   */
  protected function resolveFilePath($filename) {
    if (strpos($filename, 'http://') === 0 || strpos($filename, 'https://') === 0) {
      // Remote file.
      $tempname = drupal_tempnam('solicitation', 'solicitation');
      (new Client())->get($filename, ['sink', $tempname]);
      $path = drupal_realpath($tempname);
    } else if (strpos($filename, '/') === 0) {
      // Absolute path to the file.
      $path = $filename;
    } else {
      // Relative path.
      $path = $this->files_dir . $filename;
    }

    return $path;
  }

  /**
   * Create a file from the given filename.
   *
   * @param string $fileName
   * @return \Drupal\file\FileInterface|false
   */
  protected function createFile($fileName) {
    $filePath = $this->resolveFilePath($fileName);

    $file = File::create([
      'uid'      => 1,
      'status'   => 1,
      'filename' => basename($fileName),
      'uri'      => $filePath,
    ]);

    if ($newFile = file_copy($file, 'public://' . basename($fileName))) {
      $newFile->setPermanent();
      $newFile->save();
    }

    return $newFile;
  }
}
