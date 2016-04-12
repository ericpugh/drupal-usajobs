<?php

/**
 * @file
 * Contains \Drupal\usajobs_search_api\Plugin\Block\UsajobsSearchApiBlock.
 */

namespace Drupal\usajobs_search_api\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Block\BlockBase;
use Drupal\usajobs_search_api\JobListingCollection;

/**
 * Provides a 'USAjobs' block.
 *
 * @Block(
 *   id = "usajobs_search_api_block",
 *   admin_label = @Translation("USAjobs Job Listings"),
 * )
 */
class UsajobsSearchApiBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {
    return AccessResult::allowedIfHasPermission($account, 'access content');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    return array(
      '#theme' => 'usajobs_search_api_block',
      '#jobs' => JobListingCollection::getJobListings(),
    );

  }

}
