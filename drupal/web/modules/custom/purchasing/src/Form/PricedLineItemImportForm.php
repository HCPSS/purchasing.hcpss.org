<?php

namespace Drupal\purchasing\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Drupal\purchasing\Generator\Node\PricedLineItemGenerator;
use Drupal\file\Entity\File;
use Drupal\Core\Url;

class PricedLineItemImportForm extends FormBase {

  /**
   * {@inheritDoc}
   * @see \Drupal\Core\Form\FormInterface::getFormId()
   */
  public function getFormId() {
    return 'priced_line_item_import_form';
  }

  /**
   * {@inheritDoc}
   * @see \Drupal\Core\Form\FormInterface::buildForm()
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['file'] = [
      '#type' => 'managed_file',
      '#required' => TRUE,
      '#title' => $this->t('Priced Line Item File'),
      '#upload_location' => 'private://imports',
      '#upload_validators' => [
        'file_validate_extensions' => ['csv']
      ],
      '#description' => $this->t('<a href="@url">Download an example csv.</a>', [
        '@url' => Url::fromRoute('purchasing.example_csv', ['node_type' => 'priced_line_item'])->toString(),
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
      if (array_key_exists(0, $data)) {
        foreach ($data as $d) {
          $generator = new PricedLineItemGenerator($d);
          $generator->generate();
          $num_created++;
        }
      } else {
        $generator = new PricedLineItemGenerator($data);
        $generator->generate();
        $num_created++;
      }
    } catch (\Exception $e) {
      $transaction->rollBack();
      watchdog_exception($e->getMessage(), $e);
      throw $e;
    }

    \Drupal::messenger()->addMessage("$num_created Priced Line Items created.");
  }
}
