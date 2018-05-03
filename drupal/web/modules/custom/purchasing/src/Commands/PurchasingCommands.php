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
use Drupal\purchasing\Generator\Node\LineItemGenerator;
use Drupal\purchasing\Generator\Node\PageGenerator;
use Drupal\purchasing\Generator\Menu\MainMenuGenerator;
use Drupal\purchasing\Generator\Node\PricedLineItemGenerator;
use Drupal\purchasing\Generator\Node\DiscountedLineItemGenerator;

/**
 * A Drush commandfile.
 */
class PurchasingCommands extends DrushCommands {

  /**
   * Generate categories.
   *
   * @usage purchasing:generate:all
   *   Generate all content.
   *
   * @command purchasing:generate:all
   */
  public function generateAll() {
    $this->generateCategories();
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
