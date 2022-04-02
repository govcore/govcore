<?php

namespace Drupal\govcore_social_sharing\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Data Links' Block.
 *
 * @Block(
 *   id = "govcore_data_links_block",
 *   admin_label = @Translation("Data Links Block")
 * )
 */
class DataLinksBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = \Drupal::config('govcore_social_sharing.settings');
    $data_link_items = $config->get('data_link_items');
    $arcgis = $config->get('data_link_arcgis');
    $socrata = $config->get('data_link_socrata');
    $github = $config->get('data_link_github');
    $drupal = $config->get('data_link_drupal');
    return [
      '#theme' => 'govcore_data_links',
      '#data_link_items' => $data_link_items,
      '#arcgis' => $arcgis,
      '#socrata' => $socrata,
      '#github' => $github,
      '#drupal' => $drupal,
    ];
  }
}
