<?php

namespace Drupal\purchasing\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Drupal\purchasing\Generator\Node\SolicitationGenerator;
use Drupal\Core\Url;

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
      '#description' => $this->t('<a href="@url">Download an example csv.</a>', [
        '@url' => Url::fromRoute('purchasing.example_csv', ['node_type' => 'solicitation'])->toString(),
      ]),
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Import'),
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
    $csv = file_get_contents($file->getFileUri());

    // Remove BOM.
    $bom = pack('H*','EFBBBF');
    $csv = preg_replace("/^$bom/", '', $csv);

    $data = $encoder->decode($csv, 'csv');

    $database = \Drupal::database();
    $transaction = $database->startTransaction();
    try {
      $num_created = 0;
      if (!array_key_exists(0, $data)) {
        $data = [$data];
      }
      foreach ($data as $d) {
        $generator = new SolicitationGenerator($d);
        $procurement = $generator->generate();

        $num_created++;
      }
    } catch (\Exception $e) {
      $transaction->rollBack();
      watchdog_exception($e->getMessage(), $e);
      \Drupal::messenger()->addError($e->getMessage());
      return;
    }

    \Drupal::messenger()->addMessage("$num_created Solicitation created.");
  }
}
