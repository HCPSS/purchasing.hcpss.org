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
    return [
      'solicitations'         => $this->formBuilder()->getForm(SolicitationImportForm::class),
      'vendors'               => $this->formBuilder()->getForm(VendorImportForm::class),
      'award'                 => $this->formBuilder()->getForm(AwardImportForm::class),
      'priced_line_items'     => $this->formBuilder()->getForm(PricedLineItemImportForm::class),
      'discounted_line_items' => $this->formBuilder()->getForm(DiscountedLineItemImportForm::class),
    ];
  }
}
