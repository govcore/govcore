<?php

namespace Drupal\govcore_site_settings\Form;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\PluginFormBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The settings_tray form handler for the SystemBrandingBlock.
 *
 * @internal
 */
class SiteSettingsOffCanvasForm extends PluginFormBase implements ContainerInjectionInterface {

  use StringTranslationTrait;

  /**
   * The block plugin.
   *
   * @var \Drupal\Core\Block\BlockPluginInterface
   */
  protected $plugin;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * SystemBrandingOffCanvasForm constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current user.
   */
  public function __construct(ConfigFactoryInterface $config_factory, AccountInterface $current_user) {
    $this->configFactory = $config_factory;
    $this->currentUser = $current_user;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('current_user')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = $this->plugin->buildConfigurationForm($form, $form_state);

    $config = \Drupal::configFactory()->getEditable('govcore_site_settings.settings');
    $site_name = \Drupal::config('system.site')->get('name');

    $form['site_settings']['#type'] = 'details';
    $form['site_settings']['#weight'] = 10;


    // Load the immutable config to load the overrides.

    $form['site_settings']['site_leader'] = [
      '#type' => 'details',
      '#title' => t('Site Leader'),
      '#description' => t('Information about the site\'s leadership.'),
      '#open' => TRUE,
    ];
    $default_image = $config->get('site_leader_image');
    $form['site_settings']['site_leader']['site_leader_image'] = [
      '#type' => 'managed_file',
      '#weight' => 5,
      '#title' => 'Image',
      '#name' => 'site_leader_image',
      '#description' => t('Upload an image.'),
      '#default_value' => array($default_image),
      '#upload_location' => 'public://'
    ];
    $form['site_settings']['site_leader']['site_leader_name'] = [
      '#type' => 'textarea',
      '#rows' => 1,
      '#weight' => 1,
      '#title' => t("Name"),
      '#description' => t('The leader\'s name.'),
      '#default_value' => $config->get('site_leader_name'),
    ];
    $form['site_settings']['site_leader']['site_leader_title'] = [
      '#type' => 'textarea',
      '#rows' => 1,
      '#weight' => 3,
      '#title' => t("Title"),
      '#description' => t('The leader\'s title.'),
      '#default_value' => $config->get('site_leader_title'),
    ];
    $form['site_settings']['site_leader']['site_leader_info'] = [
      '#type' => 'text_format',
      '#rows' => 1,
      '#weight' => 4,
      '#title' => t("Department"),
      '#description' => t('The leader\'s info.'),
      '#default_value' => $config->get('site_leader_info'),
      '#format' => $config->get('site_leader_info_format'),
    ];
    $form['site_settings']['copyright'] = [
      '#type' => 'details',
      '#title' => t('Copyright'),
      '#description' => t('Information about the site\'s leadership.'),
      '#open' => TRUE,
    ];
    $form['site_settings']['copyright']['copyright_message'] = [
      '#type' => 'textarea',
      '#rows' => 3,
      '#title' => $this->t('Copyright message'),
      '#default_value' => $config->get('copyright_message'),
      '#description' => $this->t('Provide a copyright statement for the footer.'),
      '#attributes' => [
        'placeholder' => '<p>&copy; Copyright '.date("Y") .'&nbsp;'. $site_name .'</p>',
      ],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateConfigurationForm(array &$form, FormStateInterface $form_state) {
    $this->plugin->validateConfigurationForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    if(isset($form_state->getValue('site_leader_image')[0])) {
      // Set file status to permanent.
      $image = $form_state->getValue('site_leader_image')[0];
      if ($image) {
        $file = File::load($image);
        $file->setPermanent();
        $file->save();
      }
    }

    \Drupal::configFactory()->getEditable('govcore_site_settings.settings')
        ->set('site_leader_image', $form_state->getValue('site_leader_image')[0])
        ->set('site_leader_name', $form_state->getValue('site_leader_name'))
        ->set('site_leader_title', $form_state->getValue('site_leader_title'))
        ->set('site_leader_info', $form_state->getValue('site_leader_info')['value'])
        ->set('site_leader_info_format', $form_state->getValue('site_leader_info')['format'])
        ->set('copyright_message', $form_state->getValue('copyright_message'))
        ->save();


    $this->plugin->submitConfigurationForm($form, $form_state);
  }

}
