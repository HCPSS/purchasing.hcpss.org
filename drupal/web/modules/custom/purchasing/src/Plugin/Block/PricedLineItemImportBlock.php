<?php

namespace Drupal\purchasing\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\purchasing\Form\PricedLineItemImportForm;

/**
 * @Block(
 *   id = "priced_line_item_import_block",
 *   admin_label = @Translation("Priced Line Item Import Block")
 * )
 */
class PricedLineItemImportBlock extends BlockBase {

  /**
   * {@inheritDoc}
   * @see \Drupal\Core\Block\BlockPluginInterface::build()
   */
  public function build() {
    return \Drupal::formBuilder()->getForm(PricedLineItemImportForm::class);
  }

  /**
   * {@inheritDoc}
   * @see \Drupal\Core\Block\BlockBase::blockAccess()
   */
  protected function blockAccess(AccountInterface $account) {
    return AccessResult::allowedIf($account->hasPermission('create priced_line_item content'));
  }
}
