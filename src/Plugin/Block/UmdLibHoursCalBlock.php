<?php
/**
 * @file
 * Definition of Drupal\umdlib_hours\Plugin\Block\UmdLibHoursCalBlock
 */

namespace Drupal\umdlib_hours\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\umdlib_hours\Controller\UmdLibHoursCalController;

/**
 * Implements the UmdLibHoursBlock
 * 
 * @Block(
 *   id = "umdlib_hours",
 *   admin_label = @Translation("UmdLibHours Events"),
 *   category = @Translation("custom"),
 * )
 */
class UmdLibHoursCalBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $blockConfig = $this->getConfiguration();
    $libCalController = new UmdLibHoursCalController();
    $events = $libCalController->getEvents($blockConfig['limit']);
    return [
      '#theme' => 'umdlib_calendar_block',
      '#events' => $events,
      '#cache' => [
        'max-age' => 3600,
      ]
    ];
  }

  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);
    $config = $this->getConfiguration();

    $form['limit'] = [
      '#type' => 'textfield',
      '#title' => t('Limit'),
      '#description' => t('Number of calendar events to display.'),
      '#default_value' =>  isset($config['limit']) ? $config['limit'] : '3',
      '#required' => TRUE
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->setConfigurationValue('limit', $form_state->getValue('limit'));
  }

}
