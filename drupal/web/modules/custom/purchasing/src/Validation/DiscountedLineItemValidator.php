<?php

namespace Drupal\purchasing\Validation;

class DiscountedLineItemValidator extends EntityValidator {

  /**
   * {@inheritDoc}
   * @see EntityValidator::validate()
   */
  public function validate() {
    if (!$this->entity->field_award->target_id) {
      $this->addViolation(new Violation('Awards is required.'));
    }

    if (!$this->entity->getTitle()) {
      $this->addViolation(new Violation('Title is required.'));
    }

    $discount = $this->entity->field_discount->value;
    if (!$discount) {
      $this->addViolation(new Violation('Discount is required'));
    } else if (!is_numeric($discount)) {
      $this->addViolation(new Violation('Discount must be a numner ('.$discount.' given)'));
    }
  }
}
