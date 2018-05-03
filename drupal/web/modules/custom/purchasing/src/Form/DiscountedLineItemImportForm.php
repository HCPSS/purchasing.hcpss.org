<?php

namespace Drupal\purchasing\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Drupal\file\Entity\File;
use Drupal\purchasing\Generator\Node\DiscountedLineItemGenerator;

class DiscountedLineItemImportForm extends FormBase {

  /**
   * {@inheritDoc}
   * @see \Drupal\Core\Form\FormInterface::getFormId()
   */
  public function getFormId() {
    return 'discounted_line_item_import_form';
  }

  /**
   * {@inheritDoc}
   * @see \Drupal\Core\Form\FormInterface::buildForm()
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['file'] = [
      '#type' => 'managed_file',
      '#required' => TRUE,
      '#title' => $this->t('Discounted Line Item File'),
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
        $generator = new DiscountedLineItemGenerator($d);
        $generator->generate();
        $num_created++;
      }
    } else {
      $generator = new DiscountedLineItemGenerator($data);
      $generator->generate();
      $num_created++;
    }

    \Drupal::messenger()->addMessage("$num_created Discounted Line Items created.");
  }
}
