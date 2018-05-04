<?php

namespace Drupal\purchasing\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\purchasing\Form\VendorImportForm;

/**
 * @Block(
 *   id = "vendor_import_block",
 *   admin_label = @Translation("Vendor Import Block")
 * )
 */
class VendorImportBlock extends BlockBase {

  /**
   * {@inheritDoc}
   * @see \Drupal\Core\Block\BlockPluginInterface::build()
   */
  public function build() {
    return \Drupal::formBuilder()->getForm(VendorImportForm::class);
  }

  /**
   * {@inheritDoc}
   * @see \Drupal\Core\Block\BlockBase::blockAccess()
   */
  protected function blockAccess(AccountInterface $account) {
    return AccessResult::allowedIf($account->hasPermission('create vendor content'));
  }
}
