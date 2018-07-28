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

  public function businessOpportunities() {
    $output = [];

    $block = BlockContent::load('block_content:a42d9b3d-68c7-452c-b769-6fcd3c41321b');
    $output[] = \Drupal::entityTypeManager()->
      getViewBuilder('block_content')->view($block);

    return $output;
  }
}
