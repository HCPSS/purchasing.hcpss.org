<?php

namespace Drupal\purchasing\Validation;

class Violation {

  /**
   * @var string
   */
  protected $message;

  public function __construct($message) {
    $this->message = $message;
  }

  /**
   * Get the violation message.
   *
   * @return string
   */
  public function getMessage() {
    return $this->message;
  }
}
