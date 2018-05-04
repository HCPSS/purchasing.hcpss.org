<?php

namespace Drupal\purchasing\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\purchasing\Form\DiscountedLineItemImportForm;

/**
 * @Block(
 *   id = "discounted_line_item_import_block",
 *   admin_label = @Translation("Priced Line Item Import Block")
 * )
 */
class DiscountedLineItemImportBlock extends BlockBase {

  /**
   * {@inheritDoc}
   * @see \Drupal\Core\Block\BlockPluginInterface::build()
   */
  public function build() {
    return \Drupal::formBuilder()->getForm(DiscountedLineItemImportForm::class);
  }

  /**
   * {@inheritDoc}
   * @see \Drupal\Core\Block\BlockBase::blockAccess()
   */
  protected function blockAccess(AccountInterface $account) {
    return AccessResult::allowedIf($account->hasPermission('create discounted_line_item content'));
  }
}
