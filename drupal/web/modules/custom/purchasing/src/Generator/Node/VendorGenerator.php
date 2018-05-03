<?php

namespace Drupal\purchasing\Generator\Node;

use Drupal\node\Entity\Node;
use Drupal\purchasing\Generator\EntityGeneratorInterface;

class VendorGenerator extends NodeGenerator  implements EntityGeneratorInterface {

  private $data;

  public function __construct(array $data) {
    $this->data = $data;
  }

  protected static function getBundle() {
    return 'vendor';
  }

  /**
   * Format an address array for use as a field.
   *
   * @param array $data
   * @return array
   */
  private static function resolveAddress(array $data) {
    $address = [
      'country_code'        => 'US',
      'address_line1'       => $data['line1'],
      'locality'            => $data['city'],
      'administrative_area' => $data['state'],
      'postal_code'         => $data['zip'],
    ];

    if (!empty($data['line2'])) {
      $address['address_line1'] = $data['line1'];
    }

    return $address;
  }

  /**
   * Create a vendor node from an array of values.
   *
   * @param array $data
   *   Vendor values
   * @return Node
   */
  public function generate() {
    $vendor = Node::create([
      'type'  => static::getBundle(),
      'title' => $this->data['name'],
      'uid'   => \Drupal::currentUser()->id(),
    ]);

    if (!empty($this->data['address'])) {
      $vendor->field_address = self::resolveAddress($this->data['address']);
    }

    if (!empty($this->data['website'])) {
      $vendor->field_website = ['uri' => $this->data['website']];
    }

    if (!empty($this->data['id'])) {
      $vendor->field_pe_id = $this->data['id'];
    }

    if (!empty($this->data['phone'])) {
      $vendor->field_phone_number = $this->data['phone'];
    }

    if (!empty($this->data['fax'])) {
      $vendor->field_fax_number = $this->data['fax'];
    }

    if (!empty($this->data['email'])) {
      $vendor->field_email_address = $this->data['email'];
    }

    if (!empty($this->data['notes'])) {
      $vendor->field_notes = $this->data['notes'];
    }

    $vendor->save();

    return $vendor;
  }
}
