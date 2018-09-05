<?php

namespace Drupal\purchasing\Form;

use Drupal\Core\Form\FormBase;
use Drupal\file\Entity\File;
use Drupal\Core\Form\FormStateInterface;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use Drupal\node\Entity\Node;
use Drupal\purchasing\Generator\Node\FundRaisingOrganizationGenerator;

class FundRaisingOrganizationImportForm extends FormBase {



  /**
   * {@inheritDoc}
   * @see \Drupal\Core\Form\FormInterface::getFormId()
   */
  public function getFormId() {
    return 'fund_raising_organization_import_form';
  }

  /**
   * {@inheritDoc}
   * @see \Drupal\Core\Form\FormInterface::buildForm()
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['file'] = [
      '#type' => 'managed_file',
      '#required' => TRUE,
      '#title' => $this->t('Fund Rasing Organizations File'),
      '#upload_location' => 'private://imports',
      '#upload_validators' => [
        'file_validate_extensions' => ['csv xls xlsx']
      ],
      '#description' => $this->t('
        <strong>Caution:</strong> existing fund raising organizations will be
        deleted and replaced with the ones in your import file.
      '),
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
    $file    = File::load($file_id);
    $uri     = \Drupal::service('file_system')->realpath($file->getFileUri());
    $parts   = explode('.', $uri);
    $ext     = array_pop($parts);

    switch ($ext) {
      case 'csv':
        $reader = new Csv();
        break;
      case 'xls':
        $reader = new Xls();
        break;
      case 'xlsx':
        $reader = new Xlsx();
        break;
      default:
        throw new \Exception("Could not find reader for $ext extension.");
    }

    $spreadsheet        = $reader->load($uri);
    $worksheet          = $spreadsheet->getAllSheets()[0];
    $highestRow         = $worksheet->getHighestRow();
    $highestColumn      = $worksheet->getHighestColumn();
    $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);

    $data = [];
    $headers = [];
    for ($row = 1; $row <= $highestRow; $row++) {
      for ($col = 1; $col <= $highestColumnIndex; $col++) {
        $cell = $worksheet->getCellByColumnAndRow($col, $row);
        $value = $cell->getFormattedValue();

        if ($row == 1 && $value) {
          $value = preg_replace("/[^A-Za-z0-9 ]/", '', $value);
          $value = str_replace(' ', '_', $value);
          $value = strtolower($value);
          $headers[$col] = $value;
        }

        if ($row != 1 && array_key_exists($col, $headers)) {
          $data[$row][$headers[$col]] = $value;
        }
      }
    }

    $numCreated = $numDeleted = 0;
    $database = \Drupal::database();
    $transaction = $database->startTransaction();
    try {
      $numDeleted = $this->deleteAllFundRaisingOrganizations();

      foreach ($data as $d) {
        if (empty($d['code']) && empty($d['company'])) {
          // We are dealing with free wheeling spreadsheets here so we need to
          // detect when the data has ended.
          break;
        }

        $generator = new FundRaisingOrganizationGenerator($d);
        $generator->generate();

        $numCreated++;
      }
    } catch (\Exception $e) {
      $transaction->rollBack();
      watchdog_exception($e->getMessage(), $e);
      \Drupal::messenger()->addError($e->getMessage());
      return;
    }

    \Drupal::messenger()->addMessage("$numDeleted Fund Raising Organizations deleted and $numCreated created.");
  }

  /**
   * Delete all Fund raising organization nodes.
   */
  private function deleteAllFundRaisingOrganizations() {
    $fids = \Drupal::entityQuery('node')
      ->condition('type', 'fund_raising_organization')
      ->execute();

    $fros = Node::loadMultiple($fids);
    foreach ($fros as $fro) {
      $fro->delete();
    }

    return count($fids);
  }
}
