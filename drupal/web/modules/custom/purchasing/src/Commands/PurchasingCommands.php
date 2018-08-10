<?php

namespace Drupal\purchasing\Commands;

use Drush\Commands\DrushCommands;
use Drupal\Component\Serialization\Yaml;
use Drupal\purchasing\Generator\Term\CategoryGenerator;
use Drupal\purchasing\Generator\Node\VendorGenerator;
use Drupal\purchasing\Generator\Node\SolicitationGenerator;
use Drupal\purchasing\Generator\Node\ContractGenerator;
use Drupal\purchasing\Generator\EntityGeneratorInterface;
use Drupal\purchasing\Generator\Node\AwardGenerator;
use Drupal\purchasing\Generator\Node\PageGenerator;
use Drupal\purchasing\Generator\Menu\MainMenuGenerator;
use Drupal\purchasing\Generator\Node\PricedLineItemGenerator;
use Drupal\purchasing\Generator\Node\DiscountedLineItemGenerator;
use Drupal\node\Entity\Node;
use Drupal\purchasing\Generator\User\UserGenerator;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Console\Input\InputOption;

/**
 * A Drush commandfile.
 */
class PurchasingCommands extends DrushCommands {

  /**
   * Delete all nodes of the given bundle.
   *
   * @command purchasing:node:delete
   * @param $bundle string
   *   The node bundle to delete.
   * @usage drush purchasing:node:delete priced_line_item
   *   Delete all nodes of priced_line_item bundle.
   */
  public function deleteAllNodes($bundle = NULL) {
    if (!$bundle) {
      $bundles_info = \Drupal::entityTypeManager()
        ->getStorage('node_type')
        ->loadMultiple();

      $bundles = [];
      foreach ($bundles_info as $bundle_info) {
        $bundles[] = $bundle_info->id();
      }
    } else {
      $bundles = [$bundle];
    }

    foreach ($bundles as $b) {
      $nids = \Drupal::entityQuery('node')
        ->condition('type', $b)
        ->execute();

      $nodes = Node::loadMultiple($nids);
      $num = 0;
      foreach ($nodes as $node) {
        $node->delete();
        $num++;
      }

      $this->output()->writeln("$num $b deleted.");
    }
  }

  /**
   * Generate awards, procurements and line items stuff.
   *
   * @usage purchasing:generate:data
   *   Generate awards, procurements and line items.
   *
   * @command purchasing:generate:data
   */
  public function generateData() {
    $this->generateVendors();
    $this->generateSolicitations();
    $this->generateContracts();
    $this->generateAwards();
    $this->generatePricedLineItems();
    $this->generateDiscountedLineItems();
  }

  /**
   * Generate all.
   *
   * @usage purchasing:generate:all
   *   Generate all content.
   *
   * @command purchasing:generate:all
   */
  public function generateAll() {
    $this->generateCategories();
    $this->generateUsers();
    $this->generateVendors();
    $this->generateSolicitations();
    $this->generateContracts();
    $this->generateAwards();
    $this->generatePricedLineItems();
    $this->generateDiscountedLineItems();
    $this->generatePages();
    $this->generateLinks();
  }

  /**
   * Generate only essential stuff.
   *
   * @usage purchasing:generate:slim
   *   Generate essential content.
   *
   * @command purchasing:generate:slim
   */
  public function generateSlim() {
    $this->generateCategories();
    $this->generateUsers();
    $this->generatePages();
    $this->generateLinks();
  }

  /**
   * Generate links.
   *
   * @usage purchasing:generate:links
   *   Generate links.
   *
   * @command purchasing:generate:links
   */
  public function generateLinks() {
    $this->generateFromGenerator('menu', MainMenuGenerator::class);
  }

  /**
   * Generate pages.
   *
   * @usage purchasing:generate:pages
   *   Generate pages.
   *
   * @command purchasing:generate:pages
   */
  public function generatePages() {
    $this->generateFromGenerator('page', PageGenerator::class);
  }

  /**
   * Generate priced line items.
   *
   * @usage purchasing:generate:priced-line-items
   *   Generate discounted line items.
   *
   * @command purchasing:generate:priced-line-items
   */
  public function generatePricedLineItems() {
    $this->generateFromGenerator('priced_line_item', PricedLineItemGenerator::class);
  }

  /**
   * Generate discounted line items.
   *
   * @usage purchasing:generate:discounted-line-items
   *   Generate discounted line items.
   *
   * @command purchasing:generate:discounted-line-items
   */
  public function generateDiscountedLineItems() {
    $this->generateFromGenerator('discounted_line_item', DiscountedLineItemGenerator::class);
  }

  /**
   * Generate awards.
   *
   * @usage purchasing:generate:awards
   *   Generate awards.
   *
   * @command purchasing:generate:awards
   */
  public function generateAwards() {
    $this->generateFromGenerator('award', AwardGenerator::class);
  }

  /**
   * Generate categories.
   *
   * @usage purchasing:generate:categories
   *   Generate categories.
   *
   * @command purchasing:generate:categories
   */
  public function generateCategories() {
    $this->generateFromGenerator('categories', CategoryGenerator::class);
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
    $this->generateFromGenerator('vendor', VendorGenerator::class);
  }

  /**
   * Generate solicitations.
   *
   * @usage purchasing:generate:solicitations
   *   Generate solicitations.
   *
   * @command purchasing:generate:solicitations
   */
  public function generateSolicitations() {
    $this->generateFromGenerator('solicitation', SolicitationGenerator::class);
  }

  /**
   * Generate contracts.
   *
   * @usage purchasing:generate:contracts
   *   Generate contracts.
   *
   * @command purchasing:generate:contracts
   */
  public function generateContracts() {
    $this->generateFromGenerator('contract', ContractGenerator::class);
  }

  /**
   * Generate users.
   *
   * @usage purchasing:generate:users
   *   Generate users.
   *
   * @command purchasing:generate:users
   */
  public function generateUsers() {
    $this->generateFromGenerator('user', UserGenerator::class);
  }

  /**
   * Import solicitations from a CSV.
   *
   * @usage purchasing:import:solicitations ~/home/solicitations.csv
   *   Import solicitations.
   *
   * @command purchasing:import:solicitations
   */
  public function importSolicitation($file, array $options = ['file-directory' => InputOption::VALUE_REQUIRED]) {
    $file_dir = $options['file-directory'] ?: $this->defaultFileDirectory();
    echo "File DIR: $file_dir\n";

    $encoder = new CsvEncoder();
    $data = $encoder->decode(file_get_contents($file), 'csv');

    $database = \Drupal::database();
    $transaction = $database->startTransaction();

    try {
      $num_created = 0;
      if (!array_key_exists(0, $data)) {
        $data = [$data];
      }
      foreach ($data as $d) {
        (new SolicitationGenerator($d, $file_dir))->generate();
        $num_created++;
      }
    } catch (\Exception $e) {
      $transaction->rollBack();
      watchdog_exception($e->getMessage(), $e);
      throw $e;
    }
  }

  /**
   * Get the default file directory.
   *
   * @return string
   */
  private function defaultFileDirectory() {
    return drupal_get_path('module', 'purchasing') . '/data/files/';
  }

  /**
   * Generate entities using an entity generator.
   *
   * @param string $dataType
   * @param EntityGeneratorInterface $generatorClass
   */
  private function generateFromGenerator($dataType, string $generatorClass) {
    $generatorClass::deleteAll();

    foreach ($this->getData($dataType) as $data) {
      $generator = new $generatorClass($data);
      $generator->generate();
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
