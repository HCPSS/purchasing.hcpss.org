<?php

namespace Drupal\purchasing\Generator\Node;

use Drupal\node\Entity\Node;
use Drupal\purchasing\Generator\EntityGeneratorInterface;

class VendorGenerator extends NodeGenerator  implements EntityGeneratorInterface {

  /**
   * Delete vendors.
   */
  public static function deleteAll() {
    parent::deleteAllOfBundle('vendor');
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
  public static function createFromArray(array $data) {
    $vendor = Node::create([
      'type'  => 'vendor',
      'title' => $data['name'],
      'uid'   => 1,
    ]);

    if (!empty($data['address'])) {
      $vendor->field_address = self::resolveAddress($data['address']);
    }

    if (!empty($data['website'])) {
      $vendor->field_website = ['uri' => $data['website']];
    }

    if (!empty($data['id'])) {
      $vendor->field_pe_id = $data['id'];
    }

    if (!empty($data['phone'])) {
      $vendor->field_phone_number = $data['phone'];
    }

    if (!empty($data['fax'])) {
      $vendor->field_fax_number = $data['fax'];
    }

    if (!empty($data['email'])) {
      $vendor->field_email_address = $data['email'];
    }

    if (!empty($data['notes'])) {
      $vendor->field_notes = $data['notes'];
    }

    $vendor->save();

    return $vendor;
  }
}
