<?php

namespace Drupal\govcore_social_sharing\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Creates an administrative form where a user can add site-wide default codes.
 */
class SocialSharingForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['govcore_social_sharing.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'govcore_social_sharing';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('govcore_social_sharing.settings');

    $share_options = [
      'facebook' => 'Facebook',
      'tiktok' => 'TikTok',
      'twitter' => 'Twitter',
      'linkedin' => 'Linkedin',
      'pinterest' => 'Pinterest',
      'email' => 'Email',
    ];

    $form['social_sharing'] = [
      '#type' => 'details',
      '#title' => $this->t('Social Sharing'),
      '#collapsible' => FALSE,
      '#tree' => FALSE,
      '#open' => TRUE,
      '#description' => $this->t('Select the social platforms that content can be shared on.'),
    ];

    $form['social_sharing']['social_share_items'] = [
      '#type' => 'checkboxes',
      '#title' => t('Available Social Integrations'),
      '#description' => t(''),
      '#options' => $share_options,
      '#default_value' => $config->get('social_share_items'),
    ];

    $social_options = [
      'facebook' => 'Facebook',
      'instagram' => 'Instagram',
      'linkedin' => 'LinkedIn',
      'twitter' => 'Twitter',
      'youtube' => 'YouTube',
    ];

    $form['social_links'] = [
      '#type' => 'details',
      '#title' => $this->t('Social Links'),
      '#collapsible' => FALSE,
      '#tree' => FALSE,
      '#open' => TRUE,
      '#description' => $this->t('Select social platform accounts that can be linked to.'),
    ];

    $form['social_links']['social_link_items'] = [
      '#type' => 'checkboxes',
      '#title' => t('Available social platforms'),
      '#options' => $social_options,
      '#default_value' => $config->get('social_link_items'),
    ];

    $form['social_links']['social_link_facebook'] = [
      '#type' => 'textfield',
      '#title' => t('Facebook URL'),
      '#description' => t('Enter the URL to your Facebook page.'),
      '#default_value' => $config->get('social_link_facebook'),
      '#states' => [
        'visible' => [
          'input[name="social_link_items[facebook]"]' => ['checked' => TRUE],
        ],
        'required' => [
          'input[name="social_link_items[facebook]"]' => ['checked' => TRUE],
        ],
      ],
    ];

    $form['social_links']['social_link_instagram'] = [
      '#type' => 'textfield',
      '#title' => t('Instagram URL'),
      '#description' => t('Enter the URL to your Instagram account.'),
      '#default_value' => $config->get('social_link_instagram'),
      '#states' => [
        'visible' => [
          'input[name="social_link_items[instagram]"]' => ['checked' => TRUE],
        ],
        'required' => [
          'input[name="social_link_items[instagram]"]' => ['checked' => TRUE],
        ],
      ],
    ];

    $form['social_links']['social_link_linkedin'] = [
      '#type' => 'textfield',
      '#title' => t('LinkedIn URL'),
      '#description' => t('Enter the URL to your LinkedIn page.'),
      '#default_value' => $config->get('social_link_linkedin'),
      '#states' => [
        'visible' => [
          'input[name="social_link_items[linkedin]"]' => ['checked' => TRUE],
        ],
        'required' => [
          'input[name="social_link_items[linkedin]"]' => ['checked' => TRUE],
        ],
      ],
    ];

    $form['social_links']['social_link_twitter'] = [
      '#type' => 'textfield',
      '#title' => t('Twitter URL'),
      '#description' => t('Enter the URL to your Twitter account.'),
      '#default_value' => $config->get('social_link_twitter'),
      '#states' => [
        'visible' => [
          'input[name="social_link_items[twitter]"]' => ['checked' => TRUE],
        ],
        'required' => [
          'input[name="social_link_items[twitter]"]' => ['checked' => TRUE],
        ],
      ],
    ];

    $form['social_links']['social_link_youtube'] = [
      '#type' => 'textfield',
      '#title' => t('YouTube URL'),
      '#description' => t('Enter the URL to your YouTube channel.'),
      '#default_value' => $config->get('social_link_youtube'),
      '#states' => [
        'visible' => [
          'input[name="social_link_items[youtube]"]' => ['checked' => TRUE],
        ],
        'required' => [
          'input[name="social_link_items[youtube]"]' => ['checked' => TRUE],
        ],
      ],
    ];

    $data_options = [
      'arcgis' => 'ArcGIS',
      'socrata' => 'Socrata',
      'github' => 'Github',
      'drupal' => 'Drupal.org',
    ];

    $form['data_links'] = [
      '#type' => 'details',
      '#title' => $this->t('Data Links'),
      '#collapsible' => FALSE,
      '#tree' => FALSE,
      '#open' => TRUE,
      '#description' => $this->t('Select data platform accounts that can be linked to.'),
    ];

    $form['data_links']['data_link_items'] = [
      '#type' => 'checkboxes',
      '#title' => t('Available data platforms'),
      '#options' => $data_options,
      '#default_value' => $config->get('data_link_items'),
    ];
    $form['data_links']['data_link_arcgis'] = [
      '#type' => 'textfield',
      '#title' => t('ArcGIS URL'),
      '#description' => t('Enter the URL to an ArcGIS instance.'),
      '#default_value' => $config->get('data_link_arcgis'),
      '#states' => [
        'visible' => [
          'input[name="data_link_items[arcgis]"]' => ['checked' => TRUE],
        ],
        'required' => [
          'input[name="data_link_items[arcgis]"]' => ['checked' => TRUE],
        ],
      ],
    ];

    $form['data_links']['data_link_socrata'] = [
      '#type' => 'textfield',
      '#title' => t('Socrata URL'),
      '#description' => t('Enter the URL to your Socrata instance.'),
      '#default_value' => $config->get('data_link_socrata'),
      '#states' => [
        'visible' => [
          'input[name="data_link_items[socrata]"]' => ['checked' => TRUE],
        ],
        'required' => [
          'input[name="data_link_items[socrata]"]' => ['checked' => TRUE],
        ],
      ],
    ];

    $form['data_links']['data_link_github'] = [
      '#type' => 'textfield',
      '#title' => t('Github URL'),
      '#description' => t('Enter the URL to your Github repository.'),
      '#default_value' => $config->get('data_link_github'),
      '#states' => [
        'visible' => [
          'input[name="data_link_items[github]"]' => ['checked' => TRUE],
        ],
        'required' => [
          'input[name="data_link_items[github]"]' => ['checked' => TRUE],
        ],
      ],
    ];

    $form['data_links']['data_link_drupal'] = [
      '#type' => 'textfield',
      '#title' => t('Drupal.org URL'),
      '#description' => t('Enter the URL to your Drupal.org profile.'),
      '#default_value' => $config->get('data_link_drupal'),
      '#states' => [
        'visible' => [
          'input[name="data_link_items[drupal]"]' => ['checked' => TRUE],
        ],
        'required' => [
          'input[name="data_link_items[drupal]"]' => ['checked' => TRUE],
        ],
      ],
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    // Retrieve configuration.
    $config = $this->config('govcore_social_sharing.settings');

    // Set configuration.
    $config
      ->set('social_share_items', $form_state->getValue('social_share_items'))
      ->set('social_link_items', $form_state->getValue('social_link_items'))
      ->set('social_link_facebook', $form_state->getValue('social_link_facebook'))
      ->set('social_link_instagram', $form_state->getValue('social_link_instagram'))
      ->set('social_link_linkedin', $form_state->getValue('social_link_linkedin'))
      ->set('social_link_twitter', $form_state->getValue('social_link_twitter'))
      ->set('social_link_youtube', $form_state->getValue('social_link_youtube'))
      ->set('data_link_items', $form_state->getValue('data_link_items'))
      ->set('data_link_arcgis', $form_state->getValue('data_link_arcgis'))
      ->set('data_link_socrata', $form_state->getValue('data_link_socrata'))
      ->set('data_link_github', $form_state->getValue('data_link_github'))
      ->set('data_link_drupal', $form_state->getValue('data_link_drupal'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
