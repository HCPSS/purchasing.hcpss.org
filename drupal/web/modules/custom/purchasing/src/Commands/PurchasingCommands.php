<?php

namespace Drupal\purchasing\Commands;

use Drush\Commands\DrushCommands;
use Drupal\Component\Serialization\Yaml;
use Drupal\purchasing\Generator\Term\CategoryGenerator;
use Drupal\purchasing\Generator\Node\VendorGenerator;

/**
 * A Drush commandfile.
 */
class PurchasingCommands extends DrushCommands {

  /**
   * Generate categories.
   *
   * @usage purchasing:generate:categories
   *   Generate categories.
   *
   * @command purchasing:generate:categories
   */
  public function generateCategories() {
    CategoryGenerator::deleteAll();

    foreach ($this->getData('categories') as $data) {
      CategoryGenerator::createCategoryFromArray($data);
    }
  }

  /**
   * Generate vendors.
   *
   * @usage purchasing:generate:vendors
   *   Generate vendors.
   *
   * @command purchasing:generate:vendors
   */
  public function generateVendors() {
    VendorGenerator::deleteAll();

    foreach ($this->getData('vendor') as $data) {
      VendorGenerator::createFromArray($data);
    }
  }

  /**
   * Get data from a yaml file.
   *
   * @param string $type
   *   The type of data to get.
   * @return array
   *   An array of data.
   */
  private function getData($type) {
    $dataPath = vsprintf('%s/%s/data/%s.yml', [
      DRUPAL_ROOT,
      drupal_get_path('module', 'purchasing'),
      $type,
    ]);

    return Yaml::decode(file_get_contents($dataPath));
  }
}
