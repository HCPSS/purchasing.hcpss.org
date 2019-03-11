<?php

namespace Drupal\purchasing\Generator\Node;

use Drupal\node\Entity\Node;
use Drupal\purchasing\Validation\FundRaisingOrganizationValidator;

class FundRaisingOrganizationGenerator extends NodeGenerator {

  /**
   * Cached list of codes and ids.
   *
   * @var array
   */
  protected static $vendorCodeTermIds = [];

  /**
   * Get the bundle.
   *
   * @return string
   */
  protected static function getBundle() {
    return 'fund_raising_organization';
  }

  /**
   * Get the term id of the vendor code given the code.
   *
   * @param string $code
   */
  protected static function getVendorCodeTermId($code) {
    if (empty(self::$vendorCodeTermIds)) {
      $terms = \Drupal::entityTypeManager()
        ->getStorage('taxonomy_term')
        ->loadTree('fundraising_vendor_codes');

      foreach ($terms as $term) {
        self::$vendorCodeTermIds[$term->name] = $term->tid;
      }
    }

    return self::$vendorCodeTermIds[$code];
  }

  /**
   * Format m/y as Y-m-d.
   *
   * @param string $monthYear
   * @return string
   */
  private function formatDate($date) {
    return date('Y-m-d', strtotime($date));
  }

  /**
   * {@inheritDoc}
   * @see \Drupal\purchasing\Generator\EntityGeneratorInterface::generate()
   */
  public function generate() {
    $fro = Node::create([
      'type' => static::getBundle(),
      'title' => $this->data['company'],
      'field_date_approved' => $this->formatDate($this->data['approved']),
      'field_code' => ['target_id' => $this->getVendorCodeTermId($this->data['code'])],
      'field_address' => [
        'address_line1' => $this->data['address'],
        'locality' => $this->data['city'],
        'administrative_area' => $this->data['state'],
        'postal_code' => $this->data['zip'],

        // Common address settings.
        'country_code' => 'US',
        'dependent_locality' => null,
        'sorting_code' => null,
        'address_line2' => '',
        'organization' => null,
        'given_name' => null,
        'additional_name' => null,
        'family_name' => null,
      ],
    ]);

    if (!empty($this->data['phone'])) {
      $fro->field_phone_number = $this->data['phone'];
    }

    if (!empty($this->data['contactperson'])) {
      $fro->field_contact_person = $this->data['contactperson'];
    }

    $validator = new FundRaisingOrganizationValidator($fro);
    $validator->validate();
    $violations = $validator->getViolations();
    if (!empty($violations)) {
      $message = 'Error validating the fund raising organization.';
      foreach ($violations as $violation) {
        $message .= ' ' . $violation->getMessage();
      }

      throw new \Exception($message);
    }

    $fro->save();

    return $fro;
  }
}
