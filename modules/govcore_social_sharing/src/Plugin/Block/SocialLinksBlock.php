<?php

namespace Drupal\govcore_social_sharing\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Social Links' Block.
 *
 * @Block(
 *   id = "govcore_social_links_block",
 *   admin_label = @Translation("Social Links Block")
 * )
 */
class SocialLinksBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = \Drupal::config('govcore_social_sharing.settings');
    $social_link_items = $config->get('social_link_items');
    $facebook = $config->get('social_link_facebook');
    $instagram = $config->get('social_link_instagram');
    $linkedin = $config->get('social_link_linkedin');
    $twitter = $config->get('social_link_twitter');
    $youtube = $config->get('social_link_youtube');
    return [
      '#theme' => 'govcore_social_links',
      '#social_link_items' => $social_link_items,
      '#facebook' => $facebook,
      '#instagram' => $instagram,
      '#linkedin' => $linkedin,
      '#twitter' => $twitter,
      '#youtube' => $youtube,
    ];
  }
}
