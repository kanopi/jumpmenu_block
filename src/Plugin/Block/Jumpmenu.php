<?php

namespace Drupal\jumpmenu_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigManagerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a block to display a jumpmenu.
 *
 * @Block(
 *   id = "jumpmenu_block",
 *   admin_label = @Translation("Jumpmenu Block"),
 *   category = @Translation("Custom"),
 * )
 */
class Jumpmenu extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Constructs a new SearchLocalTask.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'label_display' => FALSE,
      'text' => NULL,
      'selector' => NULL,
      'headings' => NULL,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $formState) {
    $config = $this->getConfiguration();

    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Jumpmenu Title'),
      '#description' => $this->t('Title to display above jumpmenu. Leave empty for no title.'),
      '#maxlength' => 64,
      '#default_value' => isset($config['title']) ? $config['title'] : '',
      '#weight' => 60,
    ];
    $form['toc'] = [
      '#type' => 'details',
      '#title' => $this->t('Options'),
      '#open' => TRUE,
      '#weight' => 65,
    ];

    $form['toc']['selector'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Selector'),
      '#description' => $this->t('A selector where the plugin will look for headings to build the jumpmenu. The default value is <em>.layout__region--second</em>.'),
      '#maxlength' => 64,
      '#default_value' => isset($config['selector']) ? $config['selector'] : '.layout__region--second',
    ];

    $form['toc']['headings'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Headings'),
      '#description' => $this->t('is a string with a comma-separated list of selectors to be used as headings, in the order which defines their relative hierarchy level. The default value is <em>h1,h2,h3</em>.'),
      '#maxlength' => 64,
      '#default_value' => isset($config['headings']) ? $config['headings'] : 'h2,h3,h4',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $formState) {
    $this->configuration['title'] = $formState->getValue('title');
    $this->configuration['selector'] = $formState->getValue([
      'toc',
      'selector',
    ]);
    $this->configuration['headings'] = $formState->getValue([
      'toc',
      'headings',
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = $this->getConfiguration();

    $build = [
      '#theme' => 'jumpmenu',
      '#title' => [
        '#markup' => $config['title'],
      ],
      '#attached' => [
        'library' => [
          'jumpmenu_block/jumpmenu',
        ],
        'drupalSettings' => [
          'jumpmenu' => [
            'selector' => $config['selector'],
            'headings' => $config['headings'],
          ],
        ],
      ],
    ];

    return $build;
  }
}
