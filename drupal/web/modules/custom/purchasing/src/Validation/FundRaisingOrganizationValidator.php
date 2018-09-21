<?php

namespace Drupal\purchasing\Validation;

class FundRaisingOrganizationValidator extends EntityValidator {

  /**
   * {@inheritDoc}
   * @see \Drupal\purchasing\Validation\EntityValidator::validate()
   */
  public function validate() {
    if (!$this->entity->title->value) {
      $this->addViolation(new Violation('Company is required.'));
    }

    if (!$this->entity->field_code->target_id) {
      $this->addViolation(new Violation('Code is required.'));
    }

    $date = $this->entity->field_date_approved->value;
    if (!$date) {
      $this->addViolation(new Violation('Date approved is required.'));
    } else {
      $year  = intval(explode('-', $date)[0]);
      $month = intval(explode('-', $date)[1]);
      $day   = intval(explode('-', $date)[2]);

      if (!checkdate($month, $day, $year)) {
        $this->addViolation(new Violation("Date ($date) is invalid."));
      }
    }
  }
}
