<?php
/**
 * @file
 * Contains \Drupal\purchasing\Plugin\Block\AddressBlock
 */

namespace Drupal\purchasing\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * @Block(
 *   id = "iframe_block",
 *   admin_label = @Translation("iFrame Block")
 * )
 */
class IframeBlock extends BlockBase {
  
  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);
    
    $config = $this->getConfiguration();

    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#default_value' => $config['title'] ?? '',
    ];
    
    $form['source'] = [
      '#type' => 'textfield',
      '#title' => $this->t('iFrame Source'),
      '#default_value' => $config['source'] ?? '',
    ];
    
    $form['width'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Width'),
      '#default_value' => $config['width'] ?? '',
    ];
    
    $form['height'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Height'),
      '#default_value' => $config['height'] ?? '',
    ];
    
    return $form;
  }
  
  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);
    $values = $form_state->getValues();
    
    $this->configuration['title']  = $values['title'];
    $this->configuration['source'] = $values['source'];
    $this->configuration['width']  = $values['width'];
    $this->configuration['height'] = $values['height'];
  }
  
  
  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#type' => 'inline_template',
      '#template' => '
        <div class="iframe-block-wrapper">
          <iframe 
            width="{{ width }}" 
            height="{{ height }}" 
            src="{{ source }}" 
            title="{{ title }}"></iframe>
        </div>
      ',
      '#context' => $this->getConfiguration(),
    ];
  }
}
