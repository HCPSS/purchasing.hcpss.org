<?php

namespace Drupal\purchasing\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;

class Http4xxController extends ControllerBase {

  /**
   * The default 403 content.
   *
   * @return array
   *   A render array containing the message to display for 403 pages.
   */
  public function on403() {
    $return = [];
    $return['message']['#markup'] = $this->t('You are not authorized to access this page.');

    if (\Drupal::currentUser()->isAnonymous()) {
      $return['login']['#markup'] = $this->t(' <a href="@url">Try logging in</a>.', [
        '@url' => Url::fromRoute('purchasing.login')->toString(),
      ]);
    }

    return $return;
  }
}
