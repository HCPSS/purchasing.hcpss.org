<?php

namespace Drupal\purchasing\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\block_content\Entity\BlockContent;

class DefaultController extends ControllerBase {

  /**
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   */
  public function login() {
    return $this->redirect('simplesamlphp_auth.saml_login');
  }
}
