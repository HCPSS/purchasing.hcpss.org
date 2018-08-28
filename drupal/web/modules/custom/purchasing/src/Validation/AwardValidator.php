<?php

namespace Drupal\purchasing\Validation;

class AwardValidator extends EntityValidator {

  /**
   * {@inheritDoc}
   * @see EntityValidator::validate()
   */
  public function validate() {
    if (!$this->entity->field_identifier->value) {
      $this->addViolation(new Violation('Contract number is required.'));
    }

    if (!$this->entity->field_procurement_method->target_id) {
      $this->addViolation(new Violation('Procurement method is required.'));
    }

    if (!$this->entity->field_vendor->target_id) {
      $this->addViolation(new Violation('Vendor is required'));
    }
  }
}
