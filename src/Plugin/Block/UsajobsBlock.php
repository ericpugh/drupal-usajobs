<?php

/**
 * @file
 * Contains \Drupal\usajobs\Plugin\Block\UsajobsBlock.
 */

namespace Drupal\usajobs\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Block\BlockBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'USAjobs' block.
 *
 * @Block(
 *   id = "usajobs_block",
 *   admin_label = @Translation("USAjobs Block"),
 * )
 */
class UsajobsBlock extends BlockBase {

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
      '#theme' => 'usajobs_block',
//      '#jobs' => array('title' => 'the job title'),
      '#jobs' => 'the jobs element in build method',
    );

  }

}
