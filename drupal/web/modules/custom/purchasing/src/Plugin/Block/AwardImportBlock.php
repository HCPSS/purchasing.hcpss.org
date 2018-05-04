<?php

namespace Drupal\purchasing\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\purchasing\Form\AwardImportForm;

/**
 * @Block(
 *   id = "award_import_block",
 *   admin_label = @Translation("Award Import Block")
 * )
 */
class AwardImportBlock extends BlockBase {

  /**
   * {@inheritDoc}
   * @see \Drupal\Core\Block\BlockPluginInterface::build()
   */
  public function build() {
    return \Drupal::formBuilder()->getForm(AwardImportForm::class);
  }

  /**
   * {@inheritDoc}
   * @see \Drupal\Core\Block\BlockBase::blockAccess()
   */
  protected function blockAccess(AccountInterface $account) {
    return AccessResult::allowedIf($account->hasPermission('create award content'));
  }
}
