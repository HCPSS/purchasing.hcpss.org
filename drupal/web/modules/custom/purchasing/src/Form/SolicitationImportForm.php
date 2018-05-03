<?php

namespace Drupal\purchasing\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Drupal\purchasing\Generator\Node\SolicitationGenerator;

class SolicitationImportForm extends FormBase {

  /**
   * {@inheritDoc}
   * @see \Drupal\Core\Form\FormInterface::getFormId()
   */
  public function getFormId() {
    return 'solicitation_import_form';
  }

  /**
   * {@inheritDoc}
   * @see \Drupal\Core\Form\FormInterface::buildForm()
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['file'] = [
      '#type' => 'managed_file',
      '#required' => TRUE,
      '#title' => $this->t('Solicitation File'),
      '#upload_location' => 'private://imports',
      '#upload_validators' => [
        'file_validate_extensions' => ['csv']
      ],
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];

    return $form;
  }

  /**
   * {@inheritDoc}
   * @see \Drupal\Core\Form\FormInterface::submitForm()
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $file_id = $form_state->getValue('file')[0];
    $file = File::load($file_id);

    $encoder = new CsvEncoder();
    $data = $encoder->decode(file_get_contents($file->getFileUri()), 'csv');

    $num_created = 0;
    if (array_key_exists(0, $data)) {
      foreach ($data as $d) {
        $generator = new SolicitationGenerator($d);
        $solicitation = $generator->generate();
        $num_created++;
      }
    } else {
      $generator = new SolicitationGenerator($data);
      $solicitation = $generator->generate();
      $num_created++;
    }

    \Drupal::messenger()->addMessage("$num_created Solicitation created.");
  }
}
