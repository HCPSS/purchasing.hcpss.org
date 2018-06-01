<?php

namespace Drupal\purchasing\Plugin\Field\FieldWidget;

use Drupal\inline_entity_form\Plugin\Field\FieldWidget\InlineEntityFormComplex;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\Html;

/**
 * Complex inline widget with buttons.
 *
 * @FieldWidget(
 *   id = "purchasing_ief_buttons",
 *   label = @Translation("Inline entity form - Buttons"),
 *   field_types = {
 *     "entity_reference"
 *   },
 *   multiple_values = true
 * )
 */
class InlineEntityFormButtons extends InlineEntityFormComplex {

  /**
   * {@inheritDoc}
   * @see InlineEntityFormComplex::formElement()
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element = parent::formElement($items, $delta, $element, $form, $form_state);

    if (!empty($element['actions']['bundle'])) {
      foreach ($element['actions']['bundle']['#options'] as $id => $label) {
        $element['actions']['bundle_' . $id] = [
          '#type' => 'submit',
          // @todo ajax doesn't work yet.
          '#name' => 'ief-' . $this->getIefId() . '-bundle-' . $id,
          '#limit_validation_errors' => $element['actions']['ief_add']['#limit_validation_errors'],
          '#ajax' => $element['actions']['ief_add']['#ajax'],
          '#submit' => [get_class($this) . '::submitAddBundle'],
          '#ief_form' => 'add',
          '#value' => $this->t('Add new @bundle', ['@bundle' => $label]),
          '#bundle' => $id,
          '#dropbutton' => 'ief_add',
          '#pre_render' => [
              ['\Drupal\Core\Render\Element\Submit', 'preRenderButton'],
              ['\Drupal\Core\Render\Element\Submit', 'preRenderAjaxForm'],
            ],
          '#id' => Html::getUniqueId('bundle_' . $id),
        ];
      }

      $element['actions']['#process'][] = ['\Drupal\Core\Render\Element\Actions', 'preRenderActionsDropbutton'];
      $element['actions']['#theme_wrappers'] = ['container'];

      $element['actions']['ief_add']['#access'] = FALSE;

      unset($element['actions']['bundle']);
    }

    return $element;
  }

  /**
   * Submit add bundle form.
   *
   * @param array $form
   * @param FormStateInterface $form_state
   */
  public static function submitAddBundle($form, FormStateInterface $form_state) {
    $element = inline_entity_form_get_element($form, $form_state);
    $ief_id = $element['#ief_id'];
    $form_state->setRebuild();

    // Get the current form values.
    $parents = array_merge($element['#field_parents'], array($element['#field_name']));

    $triggering_element = $form_state->getTriggeringElement();
    $form_state->set(['inline_entity_form', $ief_id, 'form'], $triggering_element['#ief_form']);
    if (!empty($triggering_element['#bundle'])) {
      $form_state->set(['inline_entity_form', $ief_id, 'form settings'], array(
        'bundle' => $triggering_element['#bundle'],
      ));
    }
  }
}
