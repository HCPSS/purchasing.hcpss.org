<?php

namespace Drupal\purchasing\Generator;

interface EntityGeneratorInterface {
  public function __construct(array $data, $files_dir);
  public function generate();
  public static function deleteAll();
}
