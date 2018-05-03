<?php

namespace Drupal\purchasing\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\purchasing\Form\AwardImportForm;
use Drupal\purchasing\Form\PricedLineItemImportForm;
use Drupal\purchasing\Form\DiscountedLineItemImportForm;
use Drupal\purchasing\Form\SolicitationImportForm;
use Drupal\purchasing\Form\VendorImportForm;

class ImportController extends ControllerBase {

  /**
   * @return array
   */
  public function index() {
    $build['solicitation'] = [
      '#type' => 'container',
    ];
    $build['solicitation']['header'] = [
      '#type' => 'markup',
      '#markup' => '<h3>Solicitations</h3>',
    ];
    $build['solicitation']['form'] = $this->formBuilder()->getForm(SolicitationImportForm::class);

    $build['vendors'] = [
      '#type' => 'container',
    ];
    $build['vendors']['header'] = [
      '#type' => 'markup',
      '#markup' => '<h3>Vendors</h3>',
    ];
    $build['vendors']['form'] = $this->formBuilder()->getForm(VendorImportForm::class);

    $build['award'] = [
      '#type' => 'container',
    ];
    $build['award']['header'] = [
      '#type' => 'markup',
      '#markup' => '<h3>Awards</h3>',
    ];
    $build['award']['form'] = $this->formBuilder()->getForm(AwardImportForm::class);

    $build['priced_line_item'] = [
      '#type' => 'container',
    ];
    $build['priced_line_item']['header'] = [
      '#type' => 'markup',
      '#markup' => '<h3>Priced Line Items</h3>',
    ];
    $build['priced_line_item']['form'] = $this->formBuilder()->getForm(PricedLineItemImportForm::class);

    $build['discounted_line_item'] = [
      '#type' => 'container',
    ];
    $build['discounted_line_item']['header'] = [
      '#type' => 'markup',
      '#markup' => '<h3>Discounted Line Items</h3>',
    ];
    $build['discounted_line_item']['form'] = $this->formBuilder()->getForm(DiscountedLineItemImportForm::class);

    return $build;
  }
}
