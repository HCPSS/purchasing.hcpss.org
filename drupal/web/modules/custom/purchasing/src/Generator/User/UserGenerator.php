<?php

namespace Drupal\purchasing\Generator\User;

use Drupal\purchasing\Generator\EntityGeneratorInterface;
use Drupal\user\Entity\User;
use Drupal\purchasing\Generator\AbstractEntityGenerator;

class UserGenerator extends AbstractEntityGenerator {

  /**
   * {@inheritDoc}
   * @see EntityGeneratorInterface::deleteAll()
   */
  public static function deleteAll() {
    $users = User::loadMultiple();
    foreach ($users as $user) {
      if ($user->id() != 1) {
        $user->delete();
      }
    }
  }

  /**
   * {@inheritDoc}
   * @see EntityGeneratorInterface::generate()
   */
  public function generate() {
    $user = User::create([
      'field_display_name' => $this->data['display_name'],
      'status' => 1,
    ]);

    $user
      ->setPassword(user_password(32))
      ->enforceIsNew()
      ->setEmail($this->data['email'])
      ->setUsername($this->data['username']);

    if (!empty($this->data['roles'])) {
      foreach ($this->data['roles'] as $role) {
        $user->addRole($role);
      }
    }

    if (!empty($this->data['title'])) {
      $user->set('field_title', $this->data['title']);
    }

    if (!empty($this->data['phone'])) {
      $user->set('field_phone', $this->data['phone']);
    }

    $user->save();

    \Drupal::modulehandler()
      ->alter('simplesamlphp_auth_account_authname', $user->getAccountName(), $user);
    \Drupal::service('externalauth.externalauth')
      ->linkExistingAccount($user->getAccountName(), 'simplesamlphp_auth', $user);

    return $user;
  }
}
