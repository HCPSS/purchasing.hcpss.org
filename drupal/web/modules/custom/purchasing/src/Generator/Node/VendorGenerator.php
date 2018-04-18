<?php

namespace Drupal\purchasing\Generator\Node;

use Drupal\node\Entity\Node;

class VendorGenerator {

  /**
   * Delete vendors.
   */
  public static function deleteAll() {
    $nids = \Drupal::entityQuery('node')
      ->condition('type', 'vendor')
      ->execute();

    $nodes = Node::loadMultiple($nids);

    foreach ($nodes as $node) {
      $node->delete();
    }
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
