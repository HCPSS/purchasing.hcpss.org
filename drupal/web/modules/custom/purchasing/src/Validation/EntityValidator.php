<?php

namespace Drupal\purchasing\Validation;

use Drupal\Core\Entity\EntityInterface;

abstract class EntityValidator {

  /**
   * @var Violation[]
   */
  protected $violations = [];

  /**
   * @var EntityInterface
   */
  protected $entity;

  public function __construct(EntityInterface $entity) {
    $this->entity = $entity;
  }

  /**
   * Get the violations.
   *
   * @return Violation[]
   */
  public function getViolations() {
    return $this->violations;
  }

  /**
   * Add a violation.
   *
   * @param Violation $violation
   * @return EntityValidator
   */
  public function addViolation(Violation $violation) {
    $this->violations[] = $violation;

    return $this;
  }

  /**
   * Perform the validation.
   */
  abstract public function validate();
}
