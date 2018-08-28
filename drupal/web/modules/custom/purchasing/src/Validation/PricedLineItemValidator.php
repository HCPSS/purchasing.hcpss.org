<?php

namespace Drupal\purchasing\Validation;

class PricedLineItemValidator extends EntityValidator {

  /**
   * {@inheritDoc}
   * @see EntityValidator::validate()
   */
  public function validate() {
    if (!$this->entity->field_award->target_id) {
      $this->addViolation(new Violation('Award is required.'));
    }

    if (!$this->entity->getTitle()) {
      $this->addViolation(new Violation('Title is required.'));
    }

    $price = $this->entity->field_price->value;
    if (!$price) {
      $this->addViolation(new Violation('Price is required'));
    } else if (!is_numeric($price)) {
      $this->addViolation(new Violation('Price must be a numner ('.$price.' given)'));
    }
  }
}
