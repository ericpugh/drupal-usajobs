<?php

/**
 * @file
 * Contains \Drupal\usajobs_integration\Plugin\Block\UsajobsIntegrationBlock.
 */

namespace Drupal\usajobs_integration\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Block\BlockBase;
use Drupal\usajobs_integration\JobListingCollection;

/**
 * Provides a 'USAjobs' block.
 *
 * @Block(
 *   id = "usajobs_integration_block",
 *   admin_label = @Translation("USAjobs Job Listings"),
 * )
 */
class UsajobsIntegrationBlock extends BlockBase {

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
      '#theme' => 'usajobs_integration_block',
      '#jobs' => JobListingCollection::getJobListings(),
    );

  }

}
