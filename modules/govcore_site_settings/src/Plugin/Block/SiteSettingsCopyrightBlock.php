<?php

namespace Drupal\govcore_site_settings\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\file\Entity\File;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;


/**
 * Provides a block to display 'Site Copyright' elements.
 *
 * @Block(
 *   id = "site_settings_copyright_block",
 *   admin_label = @Translation("Site Copyright Block"),
 *   forms = {
 *     "settings_tray" = "Drupal\govcore_site_settings\Form\SiteSettingsOffCanvasForm",
 *   },
 * )
 */
class SiteSettingsCopyrightBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Stores the configuration factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Creates a SiteSettingsCopyrightBlock instance.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactoryInterface $config_factory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'use_copyright_message' => TRUE,
      'label_display' => FALSE,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {

    $url_system_site_information_settings = new Url('system.site_information_settings');
    if ($url_system_site_information_settings->access()) {
      // Get paths to settings pages.
      $site_information_url = $url_system_site_information_settings->toString();

      // Provide link to Site Information page if the user has access to
      // administer site configuration.$site_description = $this->t('Defined on the <a href=":information">Site Information</a> page.', [':information' => $site_information_url]);
      $site_description = $this->t('Defined on the <a href=":information">Site Information</a> page.', [':information' => $site_information_url]);
    }
    else {
      // Explain that the user does not have access to the Site Information
      // page.$site_name_description = $this->t('Defined on the Site Information page. You do not have the appropriate permissions to change the site logo.');
      $site_description = $this->t('Defined on the Site Information page. You do not have the appropriate permissions to change the site logo.');
    }

    $form['site_settings_copyright'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Toggle copyright elements'),
      '#description' => $this->t('Choose which copyright elements you want to show in this block instance.'),
    ];
    $form['site_settings_copyright']['use_copyright_message'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Copyright message'),
      '#description' => $site_description,
      '#default_value' => $this->configuration['use_copyright_message'],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $block_copyright = $form_state->getValue('site_settings_copyright');
    $this->configuration['use_copyright_message'] = $block_copyright['use_copyright_message'];
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $site_config = \Drupal::configFactory()->getEditable('govcore_site_settings.settings');
    $site_name = \Drupal::config('system.site')->get('name');

    $copyright_default = '© Copyright '.date("Y") .', '. $site_name .'.';
    $copyright_message = $site_config->get('copyright_message');

    if (!empty($copyright_message) ) {
      $copyright_message = $site_config->get('copyright_message');
    } else {
      $copyright_message = $copyright_default;
    }
    return [
      '#theme' => 'site_settings_copyright',
      '#copyright_message' => $copyright_message,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    return Cache::mergeTags(
      parent::getCacheTags(),
      \Drupal::configFactory()->getEditable('govcore_site_settings.settings')->getCacheTags()
    );
  }
}
