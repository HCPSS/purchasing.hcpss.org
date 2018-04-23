<?php

namespace Drupal\purchasing\Generator;

interface EntityGeneratorInterface {
  public static function createFromArray(array $data);
  public static function deleteAll();
}