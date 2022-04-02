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
 * Provides a block to display 'Site branding' elements.
 *
 * @Block(
 *   id = "site_settings_site_leader_block",
 *   admin_label = @Translation("Site Leadership Block"),
 *   forms = {
 *     "settings_tray" = "Drupal\govcore_site_settings\Form\SiteSettingsOffCanvasForm",
 *   },
 * )
 */
class SiteSettingsSiteLeaderBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Stores the configuration factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Creates a SiteLeaderBlock instance.
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
      'use_site_leader_name' => TRUE,
      'use_site_leader_title' => TRUE,
      'use_site_leader_info' => TRUE,
      'use_site_leader_info_format' => TRUE,
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

    $form['site_settings_site_leader'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Toggle Site Leader elements'),
      '#description' => $this->t('Choose which site leader elements you want to show in this block instance.'),
    ];

    $form['site_settings_site_leader']['use_site_leader_name'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Site Leader name'),
      '#description' => $site_description,
      '#default_value' => $this->configuration['use_site_leader_name'],
    ];
    $form['site_settings_site_leader']['use_site_leader_title'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Site leader title'),
      '#description' => $site_description,
      '#default_value' => $this->configuration['use_site_leader_title'],
    ];
    $form['site_settings_site_leader']['use_site_leader_info'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Site leader info'),
      '#description' => $site_description,
      '#default_value' => $this->configuration['use_site_leader_info'],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $site_settings_site_leader = $form_state->getValue('site_settings_site_leader');
    $this->configuration['use_site_leader_name'] = $site_settings_site_leader['use_site_leader_name'];
    $this->configuration['use_site_leader_title'] = $site_settings_site_leader['use_site_leader_title'];
    $this->configuration['use_site_leader_info'] = $site_settings_site_leader['use_site_leader_info'];
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $site_config = \Drupal::configFactory()->getEditable('govcore_site_settings.settings');

    $site_leader_name = $site_config->get('site_leader_name');
    $site_leader_title = $site_config->get('site_leader_title');
    $site_leader_info = $site_config->get('site_leader_info');

    return [
      '#theme' => 'site_settings_site_leader',
      '#site_leader_name' => $site_leader_name,
      '#site_leader_title' => $site_leader_title,
      '#site_leader_info' => [
        '#type' => 'processed_text',
        '#text' => $site_leader_info,
        '#format' => 'rich_text',
      ],
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
