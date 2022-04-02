<?php

namespace Drupal\govcore_social_sharing\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Social Sharing' Block.
 *
 * @Block(
 *   id = "govcore_social_sharing_block",
 *   admin_label = @Translation("Social Sharing Block")
 * )
 */
class SocialSharingBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = \Drupal::config('govcore_social_sharing.settings');
    $social_share_items = $config->get('social_share_items');

    return [
      '#theme' => 'govcore_social_sharing',
      '#social_share_items' => $social_share_items,
    ];
  }
}
