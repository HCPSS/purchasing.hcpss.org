<?php

namespace Drupal\purchasing\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\purchasing\Form\SolicitationImportForm;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * @Block(
 *   id = "solicitation_import_block",
 *   admin_label = @Translation("Solicitation Import Block")
 * )
 */
class SolicitationImportBlock extends BlockBase {

  /**
   * {@inheritDoc}
   * @see \Drupal\Core\Block\BlockPluginInterface::build()
   */
  public function build() {
    return \Drupal::formBuilder()->getForm(SolicitationImportForm::class);
  }

  /**
   * {@inheritDoc}
   * @see \Drupal\Core\Block\BlockBase::blockAccess()
   */
  protected function blockAccess(AccountInterface $account) {
    return AccessResult::allowedIf($account->hasPermission('create solicitation content'));
  }
}
